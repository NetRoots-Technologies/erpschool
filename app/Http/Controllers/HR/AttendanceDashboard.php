<?php

namespace App\Http\Controllers\HR;

use App\Helpers\GeneralSettingsHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Branch;
use App\Models\Admin\Department;
use App\Models\HR\Attendance;
use App\Models\HR\Holiday;
use App\Models\HR\LeaveRequest;
use App\Models\HR\WorkShift;
use App\Models\HRM\Employees;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use ZipStream\Test\DataDescriptorTest;
use function Psr\Log\error;

class AttendanceDashboard extends Controller
{
    public function index(Request $request)
    {
if (!Gate::allows('students')) {
            return abort(503);
        }
        //        try {
        if (isset($request['month_year']) && $request['month_year'] !== null) {
            $timestamp = strtotime($request['month_year']);
        } else {
            $timestamp = time();
        }

        $year = date('Y', $timestamp);
        $month = date('m', $timestamp);
        $date = date('F Y', $timestamp);
        $current_date = date('Y-m-d', $timestamp);


        $first_second = date('Y-m-24', $timestamp);
        $first_second_for_increment = strtotime($first_second);


        if ($current_date <= $first_second) {
            $start_date = date('Y-m-24', strtotime('-1 month', $first_second_for_increment));
            $end_date = $first_second;
        } else {
            $start_date = $first_second;
            $end_date = date('Y-m-24', strtotime('+1 month', $first_second_for_increment));
        }

        $dates_array = [];
        $current_month = date('F Y', $timestamp);

        for ($current_date = strtotime($start_date); $current_date <= strtotime($end_date); $current_date = strtotime('+1 day', $current_date)) {
            $day = date('Y-m-d', $current_date);
            $dates_array[] = $day;
        }

        //       dd($dates_array);

        $employees_data = [];

        $employees = Employees::with('workShifts.workdays');

        $gracePeriod = GeneralSettingsHelper::getGeneral('grace_period');


        if ((isset($request['department_id']) && $request['department_id'] != null)) {
            $employees->where('department_id', $request['department_id']);
        }

        if ((isset($request['employee_id']) && $request['employee_id'] != null)) {
            $employees->where('id', $request['employee_id']);
        }

        if ((isset($request['branch_id']) && $request['branch_id'] != null)) {
            $employees->where('branch_id', $request['branch_id']);
        }
        //dd($employees->get());

        $employees = $employees->get();

        //       dd($employees);

        $checkin_time = '00:00:00';
        $checkout_time = '00:00:00';
        $lateTime = '00:00:00';
        $lateAndOvertime = ['total_hours_worked' => 0, 'overtime_hours' => 0];
        foreach ($employees as $employee) {

            $employee_data = [
                'id' => $employee->id,
                'name' => $employee->name,
                'attendance' => [],
            ];

            $employee_shift = $employee->workShifts ?? null;

            if ($employee_shift === null) {
                continue;
            }

            //dd($employee_shift);

            $startTime = @$employee_shift->start_time;
            $endTime = @$employee_shift->end_time;
            $work_days = @$employee_shift->workdays;

            foreach ($dates_array as $date) {
                $present = false;
                $absent = false;
                $leave = false;
                $offDay = false;
                $late = false;
                $overtime = false;
                $leave_dates = '';
                $holiday_name = '';

                $day = date('D', strtotime($date));
                ;
                if (now() >= date('Y-m-d', strtotime($date))) {

                    if ($work_days[$day] == "1") {


                        $attendance = Attendance::where('employee_id', $employee->id)
                            ->whereDate('attendance_date', $date)
                            ->first();

                        if ($attendance) {
                            $present = true;
                            if ($attendance->timeIn && $attendance->timeOut) {

                                $checkin_time = $attendance->timeIn;
                                $checkout_time = $attendance->timeOut;
                                // dd($startTime,$endTime);

                                $lateAndOvertime = GeneralSettingsHelper::calculateLateAndOvertime(
                                    $startTime,
                                    $endTime,
                                    $checkin_time,
                                    $checkout_time,
                                    $gracePeriod,
                                    $date,
                                );

                                $late = $lateAndOvertime['late'];
                                $lateTime = $lateAndOvertime['lateTime'];
                                if ($late != null) {
                                    $late = true;
                                } else {
                                    $late = false;
                                }


                                $overtime = $lateAndOvertime['overtime'];
                                if ($overtime != null) {
                                    $overtime = true;
                                } else {
                                    $overtime = false;
                                }

                            }
                        } else {

                            $present = false;
                            $absent = true;
                            if ($absent == true) {
                                //dd($date);
                                $leave_status = LeaveRequest::where('hrm_employee_id', $employee->id)
                                    ->where('hr_approved', 1)
                                    ->where(function ($query) use ($date) {
                                        $query->whereDate('start_date', '<=', $date)
                                            ->whereDate('end_date', '>=', $date);
                                    })
                                    ->first();
                                if ($leave_status != null) {
                                    $leave_dates = $leave_status['start_date'] . ',' . $leave_status['start_date'];
                                    $leave = true;
                                }

                                $holidays = Holiday::whereDate('holiday_date', '<=', $date)
                                    ->whereDate('holiday_date_to', '>=', $date)->get();
                                $holidays = $holidays->whereIn('branch_id', [0, $employee->branch_id]);
                                $holidays = $holidays->whereIn('department_id', [0, $employee->department_id]);
                                $holidays = $holidays->whereIn('employee_id', [0, $employee->id]);

                                if ($holidays->isNotEmpty()) {
                                    foreach ($holidays as $key => $holiday) {
                                        $holiday_name = $holiday['name'];
                                    }

                                    $offDay = true;
                                }
                                $checkin_time = '00:00:00';
                                $checkout_time = '00:00:00';
                                $late = false;
                                $lateTime = '00:00:00';
                                $overtime = false;
                                $lateAndOvertime = ['total_hours_worked' => 0, 'overtime_hours' => 0];

                            }
                        }

                    } else {
                        $offDay = true;
                    }
                }

                $employee_data['attendance'][$date] = [
                    'present' => $present,
                    'absent' => $absent,
                    'leave' => $leave,
                    'checkin_time' => $checkin_time,
                    'checkout_time' => $checkout_time,
                    'late' => $late,
                    'leave_dates' => $leave_dates,
                    'total_hours_worked' => $lateAndOvertime['total_hours_worked'],
                    'overtime' => $overtime,
                    'lateTime' => $lateTime,
                    'offDay' => $offDay,
                    'holiday_name' => $holiday_name,
                    'overtime_hours' => $lateAndOvertime['overtime_hours'],

                ];
            }

            $employees_data[$employee->id] = $employee_data;
        }

        $branches = Branch::with('department')->where('status', 1)->get();

        return view('hr.attendanceDashboard.index')->with([
            'branches' => $branches,
            'dates_array' => $dates_array,
            'year' => $year,
            'month' => $month,
            'date' => $date,
            'employees_data' => $employees_data,
            'data' => $request->all(),
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        //        } catch (\Exception $e) {
//            return redirect()->back()->with('error', 'An error occurred');
//        }
    }


    public function attendanceDetail(Request $request, $employeeId = 0)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {

            if ($employeeId > 0 || $request->employee_id != null) {

                if ($employeeId != 0) {
                    $employeeData = Employees::with('branch', 'department', 'workShifts.workdays')->find($employeeId);
                    if (isset($employeeData->department)) {
                        $department = $employeeData->department;
                    } else {
                        $department = null;
                    }
                    if (isset($employeeData->branch->id)) {
                        $selectedBranch = $employeeData->branch->id;
                    } else {
                        $selectedBranch = null;
                    }
                } else {
                    $employeeData = Employees::with('branch', 'department', 'workShifts.workdays')->find($request->employee_id);
                    $department = $employeeData->department;
                    if (isset($employeeData->branch->id)) {
                        $selectedBranch = $employeeData->branch->id;
                    } else {
                        $selectedBranch = null;
                    }
                }
            } else {
                $employeeData = null;
                $department = null;
                $branch = null;
                $selectedBranch = null;
            }

            $timestamp = time();

            $year = date('Y', $timestamp);

            $month = date('m', $timestamp);
            $date = date('F Y', $timestamp);
            $current_date = date('Y-m-d', $timestamp);


            $first_second = date('Y-m-24', $timestamp);
            $first_second_for_increment = strtotime($first_second);

            $start_date = $request['start_date'];
            $end_date = $request['end_date'];


            $dates_array = [];

            $current_month = date('F Y', $timestamp);

            for ($current_date = strtotime($start_date); $current_date <= strtotime($end_date); $current_date = strtotime('+1 day', $current_date)) {
                $day = date('Y-m-d', $current_date);
                $dates_array[] = $day;
            }

            $employees_data = [];

            $gracePeriod = GeneralSettingsHelper::getGeneral('grace_period');

            $checkin_time = '00:00:00';
            $checkout_time = '00:00:00';
            $late = false;
            $lateTime = '00:00:00';
            $overtime = false;
            $lateAndOvertime = ['total_hours_worked' => 0, 'overtime_hours' => 0];

            if ($employeeData != null) {

                $employee_data = [
                    'id' => $employeeData->id,
                    'name' => $employeeData->name,
                    'attendance' => [],
                ];


                $employee_shift = $employeeData->workShifts;
                $startTime = $employee_shift->start_time;
                $endTime = $employee_shift->end_time;

                $work_days = $employee_shift->workdays;


                foreach ($dates_array as $date) {
                    $present = false;
                    $absent = true;
                    $leave = false;
                    $offDay = false;

                    $day = date('D', strtotime($date));

                    if (now() >= date('Y-m-d', strtotime($date))) {
                        if ($work_days[$day] == "1") {
                            $offDay = false;
                            $attendance = Attendance::where('employee_id', $employeeData->id)
                                ->whereDate('attendance_date', $date)
                                ->first();

                            if ($attendance && $attendance->timeIn && $attendance->timeOut) {

                                $present = true;
                                $absent = false;
                                $leave = false;

                                $checkin_time = $attendance->timeIn;
                                $checkout_time = $attendance->timeOut;

                                $lateAndOvertime = GeneralSettingsHelper::calculateLateAndOvertime(
                                    $startTime,
                                    $endTime,
                                    $checkin_time,
                                    $checkout_time,
                                    $gracePeriod,
                                    $date,
                                );
                                $late = $lateAndOvertime['late'];

                                $lateTime = $lateAndOvertime['lateTime'];

                                $overtime = $lateAndOvertime['overtime'];

                            }
                        } else {
                            $offDay = true;
                        }
                    }

                    $employee_data['attendance'][$date] = [
                        'checkin_time' => $checkin_time,
                        'checkout_time' => $checkout_time,
                        'late' => $late,
                        'total_hours_worked' => $lateAndOvertime['total_hours_worked'],
                        'overtime' => $overtime,
                        'lateTime' => $lateTime,
                        'offDay' => $offDay,
                        'overtime_hours' => $lateAndOvertime['overtime_hours'],
                        'present' => $present,
                        'absent' => $absent,
                        'leave' => $leave,
                    ];
                }
                $employees_data[$employeeData->id] = $employee_data;
            }

            $employees = Employees::all();
            $branches = Branch::with('department')->where('status', 1)->get();

            return view('hr.attendanceDashboard.detail')->with([

                'dates_array' => $dates_array,
                'year' => $year,
                'month' => $month,
                'date' => $date,
                'employees_data' => $employees_data,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'department' => $department,
                'employees' => $employees,
                'employeeData' => $employeeData,
                'branches' => $branches,
                'selectedBranch' => $selectedBranch,

            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred');
        }
    }


    public function exportPdf(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        //        try {
        $data = $this->attendanceDetail($request, $request->employee_id);

        $employeeName = $data['employeeData']->name;
        $data = $data->getData();
        $html = view('hr.attendanceDashboard.attendance_detail_pdf', compact('data', 'employeeName'))->render();

        $pdf = new Dompdf();

        $pdf->loadHtml($html);

        $pdf->setPaper('A4', 'portrait');

        $pdf->render();

        return $pdf->stream('attendance_detail.pdf');
        //        } catch (\Exception $e) {
//            return redirect()->back()->with('error', 'An error occurred');
//        }
    }



    public function exportPdf1(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            $data = $this->index($request);
            $data = $data->getData();

            $html = view('hr.attendanceDashboard.attendance_dashboard_pdf', compact('data'))->render();

            $pdf = new Dompdf();
            $pdf->loadHtml($html);
            $pdf->setPaper('A4', 'portrait');
            $pdf->render();

            return $pdf->stream('attendance_bulk.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred');
        }
    }

}
