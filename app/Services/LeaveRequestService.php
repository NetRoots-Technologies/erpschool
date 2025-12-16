<?php

namespace App\Services;


use App\Helpers\ImageHelper;
use App\Helpers\UserHelper;
use App\Models\HR\LeaveRequest;
use App\Models\HR\Quotta;
use App\Models\HR\WorkShift;
use App\Models\HRM\EmployeeCompensatoryLeaves;
use App\Models\HRM\Employees;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ApprovalAuthority;
use App\Models\ApprovalRequest;
use App\Models\ApprovalRole;
class LeaveRequestService
{

    public function store($request)
    {

        // dd($request->all());
       
        $uploadedFile = $request->file('employee_image');
        $fileNameToStore = null;

        //for leave balance
        $accumulatedDays = 0;

        $approved_leaves = LeaveRequest::with('employee', 'quota')
            ->where('hrm_employee_id', $request->employee_id)
            ->where('hr_quota_setting_id', $request->leave_type_id)
            ->where('status', 1)
            ->get();

        foreach ($approved_leaves as $item) {
            $accumulatedDays += $item->days;
        }

        $leaves = Quotta::find($request->leave_type_id);

        if ($leaves->compensatory_status == 1) {
            $compensatoryLeaveCount = EmployeeCompensatoryLeaves::where('employee_id', $request->employee_id)->first();
            $days = ($compensatoryLeaveCount->past_leaves ?? 0) + ($compensatoryLeaveCount->current_leaves ?? 0);
        } else {
            $days = $leaves->permitted_days;
        }

        $leaves_balance = $days - $accumulatedDays;

        if ($leaves_balance > 0) {
            $to_be_applied = $leaves_balance - $request->days_number;
            if ($to_be_applied < 0) {
                throw ValidationException::withMessages([
                    'message' => "Cannot add more than remaining Leaves"
                ]);
            }
        } else {
            throw ValidationException::withMessages([
                'message' => "There are no leaves available for selected Type"
            ]);
        }

        if ($uploadedFile) {
            $fileNameToStore = ImageHelper::uploadImage($uploadedFile, 'employee_files');
        }

        $leaverequest = LeaveRequest::with('quota')->where('hrm_employee_id', $request->get('employee_id'))
            ->where(function ($query) {
                $query->where('status', 1)
                    ->orWhere('status', 0);
            })->get();


        $dates = UserHelper::getDatesBetween($request->start_date, $request->end_date);

        foreach ($dates as $date) {
            $matchFound = false;

            foreach ($leaverequest as $leave) {
                $leave_start_date = $leave->start_date;
                $leave_end_date = $leave->end_date;
                $leavedate = UserHelper::getDatesBetween($leave_start_date, $leave_end_date);

                if (in_array($date, $leavedate)) {
                    $matchFound = true;
                    $conflictingDate = $date;
                    break;
                }
            }

            if ($matchFound) {
                throw ValidationException::withMessages([
                    'message' => "There is a leave request for the selected date range. Conflict on date: $conflictingDate"
                ]);
            }
        }

        //        for leaves in one year
        $currentYear = now()->year;

        $totalLeaveDaysThisYear = LeaveRequest::where('hrm_employee_id', $request->employee_id)
            ->where('hr_quota_setting_id', $request->get('leave_type_id'))
            ->where(function ($query) use ($currentYear) {
                $query->whereBetween('start_date', ["{$currentYear}-01-01", "{$currentYear}-12-31"])
                    ->orWhereBetween('end_date', ["{$currentYear}-01-01", "{$currentYear}-12-31"]);
            })->sum('days');


        $employee = Employees::where('status', 1)->with('department')->find($request->employee_id);

        $maxLeaveDays = $days;

        if (($totalLeaveDaysThisYear + $request->days_number) > $maxLeaveDays) {
            throw ValidationException::withMessages([
                'message' => "Maximum allowed leave days ($maxLeaveDays) exceeded for " . $employee->name . $currentYear
            ]);
        }

        $leaveRequestData = [
            'hrm_employee_id' => $request->get('employee_id'),
            'hr_quota_setting_id' => $request->get('leave_type_id'),
            'work_shift_id' => $request->get('work_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'days' => $request->get('days_number'),
            //            'paid_leaves' => $request->get('paid_leaves'),
//            'unpaid_leaves' => $request->get('unpaid_leaves'),
            'duration' => $request->get('duration'),
            'start_time' => $request->get('start_time'),
            'end_time' => $request->get('end_time'),
            'responsible_employee' => $request->get('responsible_employee'),
            'comments' => $request->get('comment'),
            'evidence' => $fileNameToStore,
        ];

        $leaveRequest = LeaveRequest::create($leaveRequestData);

        $employee = Employees::findOrFail($request->employee_id);

        $branchId = $employee->branch_id;
        $companyId = $employee->company_id;
        $approvalAuthorities = ApprovalAuthority::where('company_id', $companyId)
            ->where('branch_id', $branchId)
            ->where('module', 'leave')
            ->whereHas('role', function ($query) {
                $query->where('level', 1);
            })
            ->orderBy('approval_role_id')
            ->get();

        if ($approvalAuthorities->isNotEmpty()) {
            $firstLevelAuthority = $approvalAuthorities->first(); // Level 1 only
            ApprovalRequest::create([
                'leave_request_id' => $leaveRequest->id,
                'approval_authority_id' => $firstLevelAuthority->id,
                'status' => 'pending',
                'remarks' => 'Test Remarks',
            ]);
        }


        return $leaveRequest;
    }

    public function getData()
    {
       
        $data = LeaveRequest::with('workShift', 'quota', 'employee')->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                if ($row->status == 0) {

                    $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("hr.leave_requests.destroy", $row->id) . '"> ';
                    if (Gate::allows('Leaves-edit'))
                        $btn = $btn . '<a href="' . route("hr.leave_requests.edit", $row->id) . '" class="btn btn-primary  ml-2 mr-2 btn-sm">Edit</a>';

                    $btn .= '<a style ="margin-left : 3px" href="' . route("hr.manage_leaves.detail", $row->id) . '" class="btn btn-primary  ml-2 btn-sm"><i class="fas fa-eye"></i></a>';
                    if (Gate::allows('Leaves-delete'))
                        $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
                    $btn = $btn . method_field('DELETE') . '' . csrf_field();
                    $btn = $btn . ' </form>';
                    return $btn;
                } else {
                    if ($row->status == 1) {
                        return "Approved" . '' . $btn = '<a style ="margin-left : 5px" href="' . route("hr.manage_leaves.detail", $row->id) . '" class="btn btn-primary  ml-2 btn-sm"><i class="fas fa-eye"></i></a>';

                    } else {
                        return "Rejected" . '' . $btn = '<a style ="margin-left : 5px" href="' . route("hr.manage_leaves.detail", $row->id) . '" class="btn btn-primary  ml-2 btn-sm"><i class="fas fa-eye"></i></a>';
                        ;
                    }
                }
            })->addColumn('employee', function ($row) {


                if ($row->employee) {
                    return $row->employee->name;

                } else {
                    return "N/A";
                }


            })->addColumn('Leave Type', function ($row) {


                if ($row->quota) {
                    return $row->quota->leave_type;

                } else {
                    return "N/A";
                }


            })->addColumn('evidence', function ($row) {


                if ($row->evidence) {
                    return 'Given';

                } else {
                    return "Not Given";
                }


            })
            ->rawColumns(['action', 'employee', 'Leave Type', 'evidence'])
            ->make(true);
    }

    public function edit($id)
    {
       
        $leaveRequests = LeaveRequest::with('workShift', 'quota', 'employee')->find($id);
        return $leaveRequests;
    }

    public function update($request, $id)
    {
       
        $leaveRequest = LeaveRequest::find($id);
        $uploadedFile = $request->file('employee_image');
        $fileNameToStore = null;

        if ($uploadedFile) {
            $fileNameToStore = ImageHelper::uploadImage($uploadedFile, 'employee_files');
        }

        $updatedData = [
            'hrm_employee_id' => $request->get('employee_id'),
            'hr_quota_setting_id' => $request->get('leave_type_id'),
            'work_shift_id' => $request->get('work_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'days' => $request->get('days_number'),
            //            'paid_leaves' => $request->get('paid_leaves'),
//            'unpaid_leaves' => $request->get('unpaid_leaves'),
            'duration' => $request->get('duration'),
            'start_time' => $request->get('start_time'),
            'end_time' => $request->get('end_time'),
            'responsible_employee' => $request->get('responsible_employee'),
            'comments' => $request->get('comment'),
            'evidence' => $fileNameToStore,
        ];

        $leaveRequest->update($updatedData);

        return $leaveRequest;
    }


    public function destroy($id)
    {
       
        $leaveRequest = LeaveRequest::find($id);
        $leaveRequest->delete();
    }

    public function employee_leave($request)
    {
       
        $compensatoryQuota = Quotta::where('compensatory_status', 1)->get();

        $employee = Employees::find($request->employee_id);
        // $quotaSettings = Quotta::whereHas('department', function ($query) use ($employee) {
        //     $query->where('department_id', $employee->department_id);
        // })->get();
        $quotaSettings = Quotta::whereHas('department', function ($query) use ($employee) {
            $query->where('departments', $employee->department_id);
        })->get();


        $quotaSettings = $compensatoryQuota->merge($quotaSettings);

        return $quotaSettings;
    }

}

