<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Admin\Branches;
use App\Services\LedgerService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class SupplierController extends Controller
{


    protected $type = [];
    protected $ledgerService;

    public function __construct(Request $request, LedgerService $ledgerService)
    {
        $this->type['food'] = 'F';
        $this->type['stationary'] = 'S';

        $this->ledgerService = $ledgerService;

    }

    public function index($type)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        $branches = Branches::active()->get();
        $query = Item::query()->active();
        $query = $type == 'food' ? $query->food() : $query->stationary();
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
        if (!Gate::allows('Dashboard-list')) {
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
        ]);

        try {
            $fixedGroup = config("constants.FixedGroups");
            $supplier = Supplier::firstOrNew(["id" => $request->id]);

            $supplier->name = $request->name;
            $supplier->contact = $request->contact;
            $supplier->address = $request->address;
            $supplier->email = $request->email;
            $supplier->type = $this->type[$request->type];
            $supplier->type == 'F' ? $group_id = $fixedGroup['Food'] : $group_id = $fixedGroup['Stationary'];
            $supplier->save();
            $supplier->branches()->sync($request->branches);
            $supplier->items()->sync($request->items);

            foreach ($request->branches as $branch_id) {
                $this->ledgerService->createAutoLedgers([$group_id], $supplier->name, $branch_id, Supplier::class, $supplier->id);
            }

            DB::commit();
            return response()->json(["success" => true, "message" => 'Data stored successfully'], 200);
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
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $supplier->delete();
        return response()->json(["success" => true, 'message' => 'Item Deleted Successfully', 'data' => []], 200);
    }

    public function getData(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $query = Supplier::query();

        $query = $request->type == 'food' ? $query->food() : $query->stationary();

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
}

