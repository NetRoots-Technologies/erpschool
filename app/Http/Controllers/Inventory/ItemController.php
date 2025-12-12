<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Admin\Branches;
use App\Services\LedgerService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ItemController extends Controller
{
    protected $type = [];
    protected $ledgerService;

    public function __construct(Request $request,LedgerService $ledgerService)
    {
        $this->ledgerService = $ledgerService;
        $this->type['food'] = 'F';
        $this->type['stationary'] = 'S';
        $this->type['uniform'] = 'U'; 
        $this->type['general'] = 'G';
    }
    public function index($type)
    {
        
        if (!Gate::allows('RawMaterialItems-list')) {
            return abort(503);
        }
        // if (!Gate::allows('item')) {
        //     abort(503);
        // }
        $UNITS = config('constants.UNITS');

        return view('admin.inventory_management.item.index',compact('UNITS','type'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('RawMaterialItems-create')) {
            return abort(503);
        }
        // if (!Gate::allows('item-store')) {
        //     return response()->json(["success" => false, "message" => 'You Don\'t have permission to perform this action'], 403);
        // }
        DB::beginTransaction();

        $request->validate([
            'name' => 'required|min:3',
        ]);
       // dd($request->all());
        try {
            $item = Item::firstOrNew(["id" => $request->id]);
            $item->name = $request->name;
            $item->type = $this->type[$request->type];
            $item->item_code = $request->item_code; 
            $item->measuring_unit = $request->measuring_unit ?? 'PCS';
            $item->save();

            if($request->type == "food"){
                $group_id = config('constants.FixedGroups.Cafe_Inventory_Items');
            }elseif($request->type == "stationary"){
                $group_id = config('constants.FixedGroups.Stationery_Inventory_Items');
            }elseif($request->type == "uniform"){
                $group_id = config('constants.FixedGroups.Uniform_Inventory_Items');
            }else{
                $group_id = config('constants.FixedGroups.General_Inventory_Items');
            }
            $this->ledgerService->createAutoLedgers([$group_id], "$item->name"."[$item->measuring_unit]", 0 , Item::class, $item->id);

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
    public function destroy(Item $item)
    {
        if (!Gate::allows('RawMaterialItems-delete')) {
            return abort(503);
        }
        // if (!Gate::allows('item-delete')) {
        //     return response()->json(["success" => false, "message" => 'You Don\'t have permission to perform this action'], 403);
        // }
        DB::beginTransaction();
        try {

            $item->delete();
            $item->supplierItems()->delete();

            DB::commit();
            return response()->json(["success" => true, 'message' => 'Item Deleted Successfully', 'data' => []], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["success" => false, 'message' => 'Something Went Wrong', 'data' => []], 500);
        }
    }

    public function getData(Request $request)
    {
        if (!Gate::allows('RawMaterialItems-list')) {
            return abort(503);
        }
        $query = Item::query();
        // $query = $request->type == 'food' ? $query->food() : $query->stationary();
        if ($request->type == 'food') {
            $query = $query->food();
        } elseif ($request->type == 'stationary') {
            $query = $query->stationary();
        } elseif ($request->type == 'uniform') {
            $query = $query->uniform();
        } elseif ($request->type == 'general') {
            $query = $query->general();
        }

        
        $items = $query->latest()->get();
        return response()->json(["success" => true, 'message' => 'Item Listing', 'data' => $items], 200);
    }
    public function changeStatus(Item $item)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $item->status = !$item->status;
        $item->save();
        return response()->json(["success" => true, 'message' => 'Status Changed', 'data' => []], 200);
    }
}

