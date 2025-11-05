<?php

namespace App\Http\Controllers\Inventory;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Quote;
use App\Models\Supplier;
use App\Models\QuoteItem;
use Illuminate\Http\Request;
use App\Models\Admin\Branches;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class QuoteController extends Controller
{
    protected $type = [];
    public function __construct(Request $request)
    {
        $this->type['food'] = 'F';
        $this->type['stationary'] = 'S';
        $this->type['uniform'] = 'U';
    }

    public function index($type)
    {
        if (!Gate::allows('Quotes-list')) {
            return abort(503);
        }
        // $branches = Branches::active()->with(['suppliers:id,name', 'suppliers.items:id,name,measuring_unit'])->select(['id', 'name'])->get();

        $branches = Branches::active()
            ->with([
                'suppliers' => function ($query) use ($type) {
                    $query->select(['suppliers.id', 'name', 'type']);

                    if ($type == 'food') {
                        $query->food();
                    }elseif($type == 'stationary') {
                        $query->stationary();
                    }else {
                        $query->uniform();
                    }

                    $query->with([
                        'items' => function ($query) use ($type) {
                            $query->select(['items.id', 'name', 'measuring_unit', 'type']);

                            if ($type == 'food') {
                                $query->food();
                            } else {
                                $query->stationary();
                            }
                        }
                    ]);
                }
            ])
            ->select(['id', 'name'])
            ->get();

        return view('admin.inventory_management.quote.index', compact('branches', 'type'));
    }

    public function show($id)
    {
         if (!Gate::allows('Quotes-list')) {
            return abort(503);
        }
        $quote = Quote::with(['quoteItems.item', 'branch', 'supplier'])->findOrFail($id);
        return view('admin.inventory_management.quote.show', compact('quote'));
    }




    public function approval()
    {
        
        return view('admin.inventory_management.requisition.approval');
    }

    public function store(Request $request)
    {


        if (!Gate::allows('Quotes-create')) {
            return abort(503);
        }
        DB::beginTransaction();
        $request->validate([
            'supplier_id' => 'required|integer|exists:suppliers,id',
            "branch_id" => 'required|integer|exists:branches,id',
            "item_id" => 'required|array|exists:items,id',
            "quantity" => 'required|array',
            "price" => 'required|array',
            "total" => 'required|array',
            "comments" => 'nullable|string',
            "quote_date" => 'required|date',
            "due_date" => 'required|date',
        ]);

        try {
            $data = Quote::firstOrNew(['id' => $request->id]);
            $data->supplier_id = $request->supplier_id;
            $data->branch_id = $request->branch_id;
            $data->quote_date = $request->quote_date;
            $data->due_date = $request->due_date;
            $data->comments = $request->comments;
            // $data->type = $this->type[$request->type];
            if ($request->type == 'food') {
                $data->type = 'F';
            } elseif ($request->type == 'stationary') {
                $data->type = 'S';
            } else {
                $data->type = 'U';
            }       
            $data->save();

            $data->quoteItems()->delete();
            foreach ($request->item_id as $key => $item) {
                $quote_item = new QuoteItem();
                $quote_item->quote_id = $data->id;
                $quote_item->item_id = $item;
                $quote_item->quantity = $request->quantity[$key];
                $quote_item->unit_price = $request->price[$key];
                $quote_item->total_price = $request->total[$key];
                $quote_item->save();
            }


            DB::commit();
            return response()->json(["success" => true, "message" => 'Data stored successfully'], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $ex->getMessage()], 500);
        }
    }

    public function destroy(Quote $quote)
    {
        if (!Gate::allows('Quotes-delete')) {
            return abort(503);
        }
        try {
            $quote->delete();
            return response()->json(["success" => true, 'message' => 'Deleted Successfully', 'data' => []], 200);
        } catch (\Exception $ex) {
            return response()->json(["success" => false, "message" => $ex->getMessage()], 500);
        }
    }

    public function getData(Request $request)
    {


        if (!Gate::allows('Quotes-list')) {
            return abort(503);
        }
        $supplier = Quote::select([
            "id",
            "supplier_id",
            "branch_id",
            "quote_date",
            "due_date",
            "comments"
        ])
            ->with([
                "supplier:id,name",
                "branch:id,name",
                "items:id,name,measuring_unit",
                "items.quoteItem:id,quote_id,item_id,quantity,unit_price,total_price",
                "quoteItems.item",
            ]);

        // $supplier = $request->type == 'food' ? $supplier->food() : $supplier->stationary();

        if ($request->type == 'food') {
            $supplier = $supplier->food();
        }
        elseif ($request->type == 'stationary') {
            $supplier = $supplier->stationary();
        }
        elseif ($request->type == 'uniform') {
            $supplier = $supplier->uniform();
        }

        $supplier = $supplier->get();



        return response()->json(["success" => true, 'message' => 'Listing', 'data' => $supplier], 200);
    }
    public function changeStatus(Request $request, Quote $requisition)
    {
        if (!Gate::allows('Quotes-list')) {
            return abort(503);
        }
        $requisition->status = $request->status;
        $msg = '';
        if ($request->status == "APPROVED") {
            $requisition->is_approved = true;
            $requisition->approved_date = Carbon::now();
            $msg = "Request Approved";
        }
        if ($request->status == "REJECTED") {
            $requisition->comments = $request->comments;
            $msg = "Request Rejected";
        }
        $requisition->approved_by = auth()->id();
        $requisition->save();

        return response()->json(["success" => true, 'message' => $msg, 'data' => []], 200);
    }

    public function getQuote(Request $request)
    {

        if (!Gate::allows('Quotes-list')) {
            return abort(503);
        }
        $date = Date('Y-m-d');
    
        // $items = Item::select(
        //     "items.id",
        //     "items.name",
        //     "items.measuring_unit",
        //     "quote_items.unit_price",
        //     "quote_items.id as quote_item_id",
        //     "quotes.id as quote_id"
        // )
        // ->join('supplier_items', function ($join) use ($request) {
        //     $join->on('items.id', '=', 'supplier_items.item_id')
        //         ->where('supplier_items.supplier_id', $request['supplier_id']);
        // })
        // ->leftJoin('quote_items', function ($join) {
        //     $join->on('items.id', '=', 'quote_items.item_id');
        // })
        // ->leftJoin('quotes', function ($join) use ($date, $request) {
        //     $join->on('quote_items.quote_id', '=', 'quotes.id')
        //         ->where('quotes.supplier_id', $request['supplier_id'])
        //         ->where('quotes.branch_id', $request['branch_id']);
        //         // ->whereDate('quotes.quote_date', '<=', $date)
        //         // ->whereDate('quotes.due_date', '>=', $date);
        // })
        // ->get();

        $items = Item::select(
            'items.id',
            'items.name',
            'items.measuring_unit',
            'qi.unit_price',
            'qi.quantity',
            'qi.id as quote_item_id',
            'q.id as quote_id'
        )
            
            ->join('supplier_items', function ($join) use ($request) {
                $join->on('items.id', '=', 'supplier_items.item_id')
                    ->where('supplier_items.supplier_id', $request['supplier_id']);
            })
            
            ->leftJoin('quote_items as qi', function ($join) use ($request) {
                $join->on('qi.item_id', '=', 'items.id')
                    ->whereExists(function ($q) use ($request) {
                        $q->select(DB::raw(1))
                            ->from('quotes as qx')
                            ->whereColumn('qx.id', 'qi.quote_id')
                            ->where('qx.supplier_id', $request['supplier_id'])
                            ->where('qx.branch_id', $request['branch_id']);
                        // ->whereDate('qx.quote_date', '<=', $date)
                        // ->whereDate('qx.due_date', '>=', $date);
                    });
            })
            
            ->leftJoin('quotes as q', function ($join) use ($request) {
                $join->on('q.id', '=', 'qi.quote_id')
                    ->where('q.supplier_id', $request['supplier_id'])
                    ->where('q.branch_id', $request['branch_id']);
            })
            ->get();
        return response()->json(["success" => true, 'message' => "Items Found", 'data' => $items], 200);
    }
}
