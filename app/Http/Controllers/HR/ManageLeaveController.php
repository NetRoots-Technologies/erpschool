<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\LeaveRequest;
use App\Models\HR\Quotta;
use App\Models\HR\WorkShift;
use App\Models\HRM\Employees;
use App\Services\ManageLeaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ManageLeaveController extends Controller
{

    public function __construct(ManageLeaveService $manageLeaveService)
    {
        $this->ManageLeaveService = $manageLeaveService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('hr.manage_leaves.index');
    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $LeaveRequest = $this->ManageLeaveService->getdata();
        return $LeaveRequest;
    }

    public function changeStatus(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->ManageLeaveService->status($request, $id);

        return redirect()->back()->with(['success' => 'Status Updated']);
    }

    public function detail($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $employees = Employees::all();
        $leavetypes = Quotta::all();
        $workshifts = WorkShift::all();
        $LeaveRequest = LeaveRequest::with('workShift', 'quota', 'employee')->find($id);
        return view('hr.manage_leaves.detail', compact('employees', 'leavetypes', 'workshifts', 'LeaveRequest'));
    }

    public function leave_balance(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $result = $this->ManageLeaveService->leave_balance($request);

        $dataAvailable = isset($result['employeeName']) && isset($result['leaveType']);

        return view('hr.manage_leaves.leave_balance', compact('result', 'dataAvailable'));
    }

    public function add_compensatory_leaves()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->ManageLeaveService->add_compensatory_leaves();

        return 'done';
    }
}

