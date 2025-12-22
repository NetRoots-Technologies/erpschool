<?php
namespace App\Http\Controllers\HR;

use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\HR\LeaveRequest;
use App\Models\HR\LeaveApproval;
use App\Models\HR\EmployeeLeaves;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\HR\Attendance;
use Illuminate\Support\Facades\Log;

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

             /*
            |--------------------------------------------------------------------------
            | AUTO ATTENDANCE (PRESENT)
            |--------------------------------------------------------------------------
            */

            $startDate = Carbon::parse($request->start_date);
            $endDate   = Carbon::parse($request->end_date);

            $period = CarbonPeriod::create($startDate, $endDate);

            foreach ($period as $date) {

                $attendance = Attendance::updateOrCreate(
                    [
                        'employee_id'     => $leave->hrm_employee_id,
                        'attendance_date' => $date->format('Y-m-d'),
                    ],
                    [
                        'branch_id'      => $leave->employee->branch_id,
                        'status'         => 2,
                        'timeIn'         => '08:00:00',
                        'timeOut'        => '15:00:00',
                        'remarks'        => 'Auto Present (Leave Approved)',
                        'machine_status' => 0,
                    ]
                );

                Log::info('Attendance marked', [
                    'employee_id' => $leave->hrm_employee_id,
                    'date'        => $date->format('Y-m-d'),
                    'attendance_id' => $attendance->id
                ]);
            }

            Log::info('Leave approved successfully with attendance', [
                'leave_id' => $leave->id
            ]);
            

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