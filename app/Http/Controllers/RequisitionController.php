<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Inventry;
use App\Models\Supplier;
use App\Models\Requisition;
use Illuminate\Http\Request;
use App\Models\Admin\Branches;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Services\NotificationService;

class RequisitionController extends Controller
{

    protected $type = [];
    public function __construct(Request $request)
    {
        $this->type['food'] = 'F';
        $this->type['stationary'] = 'S';
    }

    public function index($type)
    {
        if (!Gate::allows('Requisitions-list')) {
            return abort(503);
        }
        $branches = Branches::active()->get();

        $query = Item::active();
        $query = $type == 'food' ? $query->food() : $query->stationary();
        $items = $query->get();

        $statuses = config('constants.status');
        $priorities = config('constants.priority');
        $requisition = Requisition::with('item')->get();
        return view('admin.inventory_management.requisition.index', compact('branches', "items", "statuses", "priorities", "type"));
    }
    public function approval($type)
    {
        if (!Gate::allows('RequisitionApproval-list')) {
            return abort(503);
        }


        return view('admin.inventory_management.requisition.approval', compact('type'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('Requisitions-create')) {
            return abort(503);
        }
        DB::beginTransaction();
        $request->validate([
            "item_id" => 'required|integer|exists:items,id',
            "branch_id" => 'required|integer|exists:branches,id',
            "type" => 'required',
            "quantity" => 'required|integer|min:1',
            "priority" => 'required',
            "justification" => 'required|string',
        ]);

        try {
            $data = Requisition::firstOrNew(['id' => $request->id]);
            $data->requester_id = auth()->id();
            $data->item_id = (int) $request->item_id;
            $data->branch_id = (int) $request->branch_id;
            $data->type = $this->type[$request->type];
            $data->quantity = $request->quantity;
            $data->requisition_to = $request->requisition_to;
            $data->priority = $request->priority;
            $data->justification = $request->justification;
            $data->save();

            DB::commit();
            return response()->json(["success" => true, "message" => 'Data stored successfully'], 200);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $ex->getMessage()], 500);
        }
    }

    public function destroy(Requisition $requisition)
    {
        if (!Gate::allows('Requisitions-delete')) {
            return abort(503);
        }
        $requisition->delete();
        return response()->json(["success" => true, 'message' => 'Deleted Successfully', 'data' => []], 200);
    }

    public function getData(Request $request)
    {

        // dd($request);
        if (!Gate::allows('Requisitions-list')) {
            return abort(503);
        }
        $query = Requisition::latest()
            ->with([
                "employee",
                "item:id,name",
                "branch:id,name",
                "item.inventory",
            ]);

       
        $query = $request->type == 'food' ? $query->food() : $query->stationary();

        $query = $query->get();

        
        return response()->json(["success" => true, 'message' => 'Listing', 'data' => $query], 200);

    }
    public function changeStatus(Request $request, Requisition $requisition)
    {
        if (!Gate::allows('RequisitionApproval-edit')) {
            return abort(503);
        }
        $requisition->status = $request->status;

        $inv =  Inventry::where('item_id' , $requisition->item_id)->first();
            if ($inv) {
                $inv->quantity =$inv->quantity - $requisition->quantity;
                $inv->save();
            }

            
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


       

        NotificationService::sendNotification(auth()->id(), $requisition->requester_id, "Request Status", "Your request Have been $request->status");

        return response()->json(["success" => true, 'message' => $msg, 'data' => []], 200);
    }
}

