<?php
namespace App\Http\Controllers\HR;

use Illuminate\Http\Request;
use App\Models\HR\LeaveRequest;
use App\Models\HR\LeaveApproval;
use App\Models\HR\EmployeeLeaves;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApproveLeaveController extends Controller {

    public function index() {
        $leaves = LeaveRequest::with(['employee','approval'])->get();
        return view('hr.approve_leaves.index', compact('leaves'));
    }

    public function approve($id, Request $request)
    {
        $leave = LeaveRequest::findOrFail($id);

        // ❌ Stop duplicate approval
        if ($leave->status == 1 && $leave->hr_approved == 1) {
            return response()->json([
                'status' => 0,
                'message' => 'Leave already approved'
            ]);
        }

        // ✅ Create approval record
        LeaveApproval::create([
            'leave_request_id' => $leave->id,
            'approved_by'      => Auth::id(),
            'status'           => 'Approved',
            'remarks'          => $request->remarks
        ]);

        // Update main tables
        EmployeeLeaves::where('id', $leave->id)
            ->update([
                'hr_approval' => 1
            ]);

        LeaveRequest::where('id', $leave->id)
            ->update(['status' => 1, 'hr_approved' => 1]);

        return response()->json([
            'status'  => 1,
            'message' => 'Leave Approved Successfully'
        ]);
    }


    public function reject($id, Request $request)
    {
        $leave = LeaveRequest::findOrFail($id);

        // Stop if already processed
        if ($leave->status != 0) {
            return response()->json([
                'status' => 0,
                'message' => 'Leave already processed'
            ]);
        }

        // Create rejection record
        LeaveApproval::create([
            'leave_request_id' => $leave->id,
            'approved_by'      => Auth::id(),
            'status'           => 'Rejected',
            'remarks'          => $request->remarks
        ]);

        // Update leave_requests table
        $leave->update([
            'status'      => 2, // Rejected
            'hr_approved' => 0
        ]);

        // Update employee_leaves table
        EmployeeLeaves::where('employee_id', $leave->hrm_employee_id)
            ->where('leave_type', $leave->hr_quota_setting_id)
            ->where('leave_date', $leave->start_date . ' to ' . $leave->end_date)
            ->update([
                'hr_approval' => 0
            ]);

        return response()->json([
            'status'  => 1,
            'message' => 'Leave Rejected Successfully'
        ]);
    }


}