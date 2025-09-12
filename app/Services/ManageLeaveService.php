<?php

namespace App\Services;

use App\Helpers\GeneralSettingsHelper;
use App\Models\HR\Attendance;
use App\Models\HR\GeneralSetting;
use App\Models\HR\LeaveRequest;
use App\Models\HR\Quotta;
use App\Models\HRM\EmployeeCompensatoryLeaves;
use App\Models\HRM\Employees;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\ApprovalRequest;

class ManageLeaveService
{

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = LeaveRequest::with(['workShift', 'quota', 'employee', 'approvalRequests.approvalAuthority.user'])->get();
        //dd($data);
        return Datatables::of($data)->addIndexColumn()
            // ->addColumn('action', function ($row) {
            //     $user = Auth::user();
            //     $hideClass = ($row->status == 1 || $row->status == 2) ? 'hide' : '';

            //     $btn = '<div style="display: inline-flex">';
            //     $btn .= '<a href="' . route("hr.manage_leaves.status", [$row->id, 'status' => 1]) . '" class="btn btn-success accept_btn btn-sm ' . $hideClass . '" style="margin-right: 3px"><i class="fas fa-check"></i></a>';
            //     $btn .= '<a href="' . route("hr.manage_leaves.status", [$row->id, 'status' => 2]) . '" class="btn btn-danger reject_btn btn-sm ' . $hideClass . '"  style="margin-right: 3px;" ><i class="fas fa-times"></i></a>';
            //     $btn .= '<a href="' . route("hr.manage_leaves.detail", $row->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>';
            //     $btn .= '</div>';

            //     if ($user->hasRole('Admin')) {
            //         return $btn;
            //     } elseif ($user->hasRole('team_lead') && ($row->hr_approved == 1 || $row->team_lead_approved == 1 || $row->head_cord_approved == 1)) {
            //         return $btn;
            //     }

            //     return '';
            // })
            ->addColumn('employee', function ($row) {
                return $row->employee ? $row->employee->name : "N/A";
            })
            ->addColumn('Leave Type', function ($row) {
                return $row->quota ? $row->quota->leave_type : "N/A";
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 0) {
                    return Config::get('constants.leave_all_status_name.pending');
                } elseif ($row->status == 1) {
                    return Config::get('constants.leave_all_status_name.approved');
                } else {
                    return Config::get('constants.leave_all_status_name.rejected');
                }
            })
            ->addColumn('approved_by', function ($row) {
                if ($row->hr_approved == 1) {
                    return 'HR';
                } elseif ($row->team_lead_approved == 1) {
                    return "TeamLead";
                } elseif ($row->head_cord_approved == 1) {
                    return "Head Coordinate";
                } elseif ($row->hco_approved == 1) {
                return "Head office";
                } else {
                    return "N/A";
                }
            })
           
            ->addColumn('approval_info', function ($row) {
                if ($row->approvalRequest) {
                    return [
                        'status' => $row->approvalRequest->status ?? 'N/A',
                        'remarks' => $row->approvalRequest->remarks ?? 'No comment',
                        'created_at' => $row->approvalRequest->created_at ?? 'N/A',
                    ];
                } else {
                    return null;
                }
            })
            ->addColumn('approval_requests', function ($row) {
                return json_encode($row->approvalRequests->map(function ($item) {
                    return [
                        'status' => $item->status,
                        'remarks' => $item->remarks,
                        'created_at' => $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : 'N/A',
                        'approved_by' => $item->approved_by ?? 'N/A',
                        'approver_name' => $item->approvalAuthority->user->name ?? 'N/A', // This will now work
                    ];
                }));
            })

            //->rawColumns(['action', 'employee', 'Leave Type', 'status', 'approved_by', 'approval_info', 'approval_requests'])
            ->rawColumns(['employee', 'Leave Type', 'status', 'approved_by', 'approval_info', 'approval_requests'])
            ->make(true);
    }



    public function status($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $leaveRequest = LeaveRequest::find($id);

        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            $leaveRequest->hr_approved = $request->get('status');
        } elseif ($user->hasRole('team_lead')) {
            $leaveRequest->team_lead_approved = $request->get('status');
        } elseif ($user->hasRole('head_cord')) {
            $leaveRequest->head_cord_approved = $request->get('status');
        } elseif ($user->hasRole('hco')) {
            $leaveRequest->hco_approved = $request->get('status');
        }

        $leaveRequest->status = $request->get('status');

        $leaveRequest->update();

        return $leaveRequest;

    }

    public function leave_balance($request)
    {
        $result = [];

        $leaveRequests = LeaveRequest::with('employee', 'quota')
            ->where('hrm_employee_id', $request->employee_id)
            ->where('hr_quota_setting_id', $request->leave_type)
            //  ->selectRaw('status, count(*) as status_count')
            //->groupBy('status')
            ->get();

        $leaves = $leaveRequests->where('status', 1);
        $approved_days = 0;
        foreach ($leaves as $leave) {
            $approved_days += $leave->days;
        }


        //        dd($approved_days);

        if (count($leaveRequests) > 0) {
            $totalPending = $leaveRequests->where('status', 0)->count();
            $totalApproved = $leaveRequests->where('status', 1)->count();
            $totalRejected = $leaveRequests->where('status', 2)->count();
        } else {
            $totalPending = 0;
            $totalApproved = 0;
            $totalRejected = 0;
        }

        $employee = Employees::with('designation', 'department')->find($request->employee_id);

        if ($employee) {
            $employeeName = $employee->name;
            $employeeDesignation = optional($employee->designation)->name;

            $leaves = Quotta::find($request->leave_type);

            if ($leaves) {
                $leavetype = $leaves->leave_type;

                if ($leaves->compensatory_status == 1) {
                    $compensatoryLeaveCount = EmployeeCompensatoryLeaves::where('employee_id', $request->employee_id)->first();
                    $total_days = ($compensatoryLeaveCount->past_leaves ?? 0) + ($compensatoryLeaveCount->current_leaves ?? 0);
                } else {
                    $total_days = $leaves->permitted_days;
                }

                $result['employeeName'] = $employeeName;
                $result['employeeDesignation'] = $employeeDesignation;
                $result['leaveType'] = $leavetype;
                $result['total_days'] = $total_days;
                $result['approved_days'] = $approved_days;
                $result['totalPending'] = $totalPending;
                $result['totalApproved'] = $totalApproved;
                $result['totalRejected'] = $totalRejected;

                return $result;
            }
        }

        return [];
    }

    public function add_compensatory_leaves()
    {
        $overtimeDayCounts = array();
        $overtimeDayCountsTest = array();
        $overtimeCount = 0;
        $overtimeDesignations = GeneralSettingsHelper::getSetting('designations_for_compensatory_leaves');

        $employees = Employees::with('workShifts.workdays')->whereIn('designation_id', $overtimeDesignations)
            ->where('job_seeking', 'full_time')->get();

        foreach ($employees as $emp) {

            $gracePeriod = GeneralSettingsHelper::getGeneral('grace_period');

            $employee_shift = $emp->workShifts;
            $startTime = $employee_shift->start_time;

            $overtimeCalculateAfterHours = GeneralSettingsHelper::getGeneral('hours_to_calculate_overtime_after_for_compensatory_leaves');
            $endTime = $employee_shift->end_time;
            $newEndTimeTimestamp = strtotime($endTime) + ($overtimeCalculateAfterHours * 3600);
            $endTime = date('H:i:s', $newEndTimeTimestamp);

            $work_days = $employee_shift->workdays;

            $all_attendance = Attendance::where('employee_id', $emp->id)->groupBy('attendance_date')->orderBy('attendance_date', 'asc')->get();

            foreach ($all_attendance as $attendance) {

                $overtime = 0;
                $date = $attendance->attendance_date;
                $day = date('D', strtotime($date));

                if ($work_days[$day] == "1") {
                    if ($attendance && $attendance->timeIn && $attendance->timeOut) {

                        $checkin_time = $attendance->timeIn;
                        $checkout_time = $attendance->timeOut;

                        $lateAndOvertime = GeneralSettingsHelper::calculateHourlyOvertime(
                            $startTime,
                            $endTime,
                            $checkin_time,
                            $checkout_time,
                            $gracePeriod
                        );

                        $overtime = $lateAndOvertime['overtime'];
                    }
                }
                if ($overtime > 0) {
                    $overtimeDayCounts[] = 1;
                    $overtimeDayCountsTest[$date] = $day;
                } else {
                    $overtimeDayCounts[] = 0;
                    $overtimeDayCountsTest[$date] = $day;
                }
            }

            $check = 0;
            $consecutive_days_count = GeneralSettingsHelper::getGeneral('compensatory_leaves_generate_after_consecutive_days');
            foreach ($overtimeDayCounts as $overtimeDayCount) {
                if ($overtimeDayCount == 1) {
                    ++$check;
                } elseif ($overtimeDayCount == 0) {
                    $check = 0;
                }

                if ($check == $consecutive_days_count) {
                    ++$overtimeCount;
                    $check = 0;
                }
            }

            $currentDate = date('Y-m-d');
            $lastDayOfMonth = date('Y-m-t', strtotime($currentDate));
            $alreadyExist = EmployeeCompensatoryLeaves::where('employee_id', $emp->id)->first();
            if ($alreadyExist) {
                if ($currentDate === $lastDayOfMonth) {
                    $alreadyExist->past_leaves += $overtimeCount;
                    $alreadyExist->current_leaves = 0;
                    $alreadyExist->save();

                    Attendance::where('employee_id', $emp->id)->where('status', 0)->update(['status' => 1]);

                } else {
                    $alreadyExist->current_leaves = $overtimeCount;
                    $alreadyExist->save();
                }
            } else {
                $data1 = [
                    'past_leaves' => 0,
                    'current_leaves' => $overtimeCount,
                    'employee_id' => $emp->id,
                ];
                EmployeeCompensatoryLeaves::create($data1);
            }
        }
    }
}
