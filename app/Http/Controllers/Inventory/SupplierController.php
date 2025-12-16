<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Admin\Branches;
use App\Imports\SupplierImport;
use App\Services\LedgerService;
use App\Imports\SupplierVendorImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends Controller
{


    protected $type = [];
    protected $ledgerService;

    public function __construct(Request $request, LedgerService $ledgerService)
    {
        $this->type['food'] = 'F';
        $this->type['stationary'] = 'S';
        $this->type['uniform'] = 'U';
        $this->type['general'] = 'G';

        $this->ledgerService = $ledgerService;

    }

    public function index($type)
    {
        if (!Gate::allows('Supplier-list')) {
            return abort(503);
        }

        $branches = Branches::active()->get();
        $query = Item::query()->active();
        // $query = $type == 'food' ? $query->food() : $query->stationary();
        if ($type == 'food') {
            $query = $query->food();
        } elseif ($type == 'stationary') {
            $query = $query->stationary();
        }elseif ($type == 'uniform') {
            $query = $query->uniform();
        }elseif ($type == 'general') {
            $query = $query->general();
        }
        $items = $query->get();

        return view('admin.inventory_management.supplier.index', compact('branches', "items", 'type'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('Supplier-create')) {
            return abort(503);
        }

        DB::beginTransaction();

        $request->validate([
            'name' => 'required|min:3',
            'branches' => 'required',
            'items' => 'required',
            'contact' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'ntn_number' => 'required|string|max:50',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
                ]);


        try {
            $fixedGroup = config("constants.FixedGroups");
            $supplier = Supplier::firstOrNew(["id" => $request->id]);

            $supplier->name = $request->name;
            $supplier->contact = $request->contact;
            $supplier->address = $request->address;
            $supplier->email = $request->email;
            $supplier->ntn_number = $request->ntn_number;
            $supplier->tax_percentage = $request->tax_percentage ?? 0;  
            $supplier->type = $this->type[$request->type];
            // $supplier->type == 'F' ? $group_id = $fixedGroup['Food'] : $group_id = $fixedGroup['Stationary'];
            if ($request->type == 'food') {
                $group_id = $fixedGroup['Food'];
            } elseif ($request->type == 'stationary') {
                $group_id = $fixedGroup['Stationary'];
            }else{
                $group_id = $fixedGroup['Uniform'];
            }
            $supplier->save();
            $supplier->branches()->sync($request->branches);
            $supplier->items()->sync($request->items);

           

            foreach ($request->branches as $branch_id) {
                $this->ledgerService->createAutoLedgersForSuppliers([$group_id], $supplier->name, $branch_id, Supplier::class, $supplier->id , $request->all());
            }

            DB::commit();

            return redirect()->route('inventory.suppliers.index', ['type' => $request->type])->with('success', 'Supplier  Successfully');
            // return response()->json(["success" => true, "message" => 'Data stored successfully'], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $ex->getMessage()], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        if (!Gate::allows('Supplier-delete')) {
            return abort(503);
        }
        $supplier->delete();
        return response()->json(["success" => true, 'message' => 'Item Deleted Successfully', 'data' => []], 200);
    }

    public function getData(Request $request)
    {
        if (!Gate::allows('Supplier-list')) {
            return abort(503);
        }
        $query = Supplier::query();

        // $query = $request->type == 'food' ? $query->food() : $query->stationary();
        if ($request->type == 'food') {
            $query = $query->food();
        } elseif ($request->type == 'stationary') {
            $query = $query->stationary();
        }elseif ($request->type == 'uniform') {
            $query = $query->uniform();
        }elseif ($request->type == 'general') {
            $query = $query->general();
        }
        
        $supplier = $query->latest()->with([
            'branches:id,name',
            'items:id,name'
            ])->get();
            
        return response()->json(["success" => true, 'message' => 'Item Listing', 'data' => $supplier], 200);
    }
    public function changeStatus(Supplier $supplier)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $supplier->status = !$supplier->status;
        $supplier->save();
        return response()->json(["success" => true, 'message' => 'Status Changed', 'data' => []], 200);
    }

    
    public function import(Request $request)
{
    $import = new SupplierVendorImport();

    Excel::import($import, $request->file('file'));

    if ($import->failures()->isNotEmpty()) {
        return back()->with([
            'import_errors' => $import->failures()
        ]);
    }

    return back()->with('success', 'Suppliers imported successfully');
}
}

