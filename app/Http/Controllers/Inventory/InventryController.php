<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Item;
use App\Models\Product;
use App\Models\Inventry;
use Illuminate\Http\Request;
use App\Models\Account\Ledger;
use App\Models\PurchaseHistory;
use App\Services\LedgerService;
use App\Models\Student\Students;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class InventryController extends Controller
{
    protected $ledgerService;

    public function __construct(LedgerService $ledgerService)
    {
        $this->ledgerService = $ledgerService;
    }
    public function index($type)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('admin.inventory_management.inventry.index', compact('type'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        // if (!Gate::allows('inventory-store')) {
        //     return response()->json(["success" => false, "message" => 'You Don\'t have permission to perform this action'], 403);
        // }
        DB::beginTransaction();

        $request->validate([
            'sale_price' => 'required|min:1',
            'expiry_date' => 'nullable',
        ]);

        try {
            $inventry = Inventry::firstOrNew(["id" => $request->id]);
            $inventry->sale_price = $request->sale_price;
            $inventry->expiry_date = $request->expiry_date;
            $inventry->save();
            DB::commit();
            return response()->json(["success" => true, "message" => 'Data stored successfully'], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $ex->getMessage()], 500);
        }
    }

    public function getData(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $query = Inventry::latest()->with('branch');
        $query = $request->type == 'food' ? $query->food()->orWhere('type', 'p') : $query->stationary()->orWhere('type', 'SP');
        return response()->json(["success" => true, 'message' => 'Listing', 'data' => $query->get()], 200);

    }

    public function view($type = 'food')
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('admin.inventory_management.inventry.pos', compact('type'));
    }

    public function listing(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $inventory = Inventry::select([
            'id',
            'name',
            'branch_id',
            'quantity',
            'unit_price',
            'cost_price',
            'sale_price',
            'measuring_unit',
            'type'
        ]);
        if ($request->type == "food") {
            $inventory = $inventory->Canteen();
        }
        if ($request->type == "uniform") {
            $inventory = $inventory->where('name', 'like', "%uniform%");
        }
        $inventory = $inventory->latest()->get();

        return response()->json(["success" => true, "message" => "Message", "data" => $inventory], 200);

    }

    public function save(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $request->validate([
            "name" => 'required|array',
            "inventory_id" => 'required|array',
            "price" => 'required|array',
            "quantity" => 'required|array',
            "total" => 'numeric|string',
            "discount" => 'nullable|string|between:0,100',
            "payment_method" => 'required|in:cash,card,voucher',
            "voucher" => 'nullable|string',
            "card" => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        try {
            $purchaseHistory = new PurchaseHistory();

            $purchaseHistory->customer_name = $request->customer_name;
            $purchaseHistory->voucher_id = $request->voucher;
            $purchaseHistory->card_number = $request->card;
            $purchaseHistory->purchase_date = date('Y-m-d');
            $purchaseHistory->total_sum = $request->total;
            $purchaseHistory->payment_method = $request->payment_method;
            $purchaseHistory->total_price = $request->total_price;
            $purchaseHistory->status = "completed";
            $purchaseHistory->discount_applied = $request->discount;
            $purchaseHistory->created_by = auth()->id();

            $data = [
                "purchase_id" => $purchaseHistory->id,
                "total" => $request->total,
                "discount" => $request->discount,
                "sum" => $request->total_price,
                "payment_method" => $request->payment_method,
                "voucher" => $request->voucher,
                "card" => $request->card,
            ];

            foreach ($request->inventory_id as $key => $inventory_id) {

                $inventory = Inventry::find($inventory_id);
                $inventory->quantity = $inventory->quantity - $request->quantity[$key];
                $inventory->save();
                $data[$inventory->id] = [
                    "inventory_id" => $inventory_id,
                    "item_name" => $inventory->name,
                    "quantity" => $request->quantity[$key],
                    "price" => $request->price[$key],
                ];

            }
            $purchaseHistory->item_lists = json_encode($data);
            $purchaseHistory->save();

            // Maitaining Ledgers for POS
            // Inventory items to be Cr by cast price
            // Cash in hand to be Dr by Sale Price
            // Profit/loss to be  Dr bt the difference1 

            //Entry
            $data = [
                "amount" => $request->total,
                "narration" => "Pos Transaction",
                "branch_id" => auth()->user()->employee->branch_id ?? 1,
                "entry_type_id" => 8
            ];

            $entry = $this->ledgerService->createEntry($data);

            $cashLedger = Ledger::first();
            $data = [
                "entry_type_id" => 8,
                "entry_id" => $entry->id,
                "ledger_id" => $cashLedger->id,
                "amount" => $request->total,
                "balanceType" => 'd',
                "narration" => "Pos Transaction on cash ledger",
            ];

            $this->ledgerService->createEntryItems($data);

            foreach ($request->inventory_id as $key => $inventory_id) {
                $inventory = Inventry::find($inventory_id);
                $id = $inventory->product_id ?? $inventory->item_id;
                $model = $inventory->product_id ? Product::class : Item::class;

                $itemLedger = $this->ledgerService->getLedger($model, $id);

                $data = [
                    "entry_type_id" => 8,
                    "entry_id" => $entry->id,
                    "ledger_id" => $itemLedger->id,
                    "amount" => $request->price[$key],
                    "balanceType" => 'c',
                    "narration" => "Pos Transaction on Inventory $inventory->name",
                ];

                $this->ledgerService->createEntryItems($data);
            }


            DB::commit();
            return response()->json(["success" => true, "message" => "Order Completed"], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $ex->getMessage(), "data" => ""], 500);
        }
    }

    public function purchaseHistory()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $purchaseHistory = PurchaseHistory::latest()->get();
        return view('admin.inventory_management.inventry.purchase_history', compact('purchaseHistory'));
    }
}

