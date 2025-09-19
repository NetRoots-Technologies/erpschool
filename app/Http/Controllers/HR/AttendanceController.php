<?php

namespace App\Http\Controllers\HR;

use App\Exports\AttendanceExport;
use App\Http\Controllers\Controller;
use App\Models\Admin\Branch;
use App\Models\Admin\Department;
use App\Models\HR\Attendance;
use App\Models\HRM\Employees;
use App\Models\HRM\HrmEmployeeAttendance;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Dompdf\Dompdf;
use Excel;



class AttendanceController extends Controller
{


    public function __construct(AttendanceService $attendanceService)
    {
        $this->AttendanceService = $attendanceService;
    }

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }


        return view('hr.attendance_managment.index');
        //        $attendance = $this->EmployeeServices->employee_attendance();
//        return $attendance;
    }

    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }


        $branches = Branch::where('status', 1)->get();
        //        dd($branches);

        return view('hr.attendance_managment.create', compact('branches'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        try {

            $errors = $this->AttendanceService->store($request);

            if (!empty($errors)) {
                $errorMessage = $errors->first('error');
                return redirect()->back()->with('error', $errorMessage);
            }

            return redirect()->route('hr.attendance.index')->with('success', 'Attendance stored successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Please Load the Employees first ');
        }
    }



    public function show($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }

    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $attendance = Attendance::with('employee')->find($id);
        if (!$attendance) {
            return redirect()->back()->with('error', 'Did not find any Attendance');
        }
        $branches = Branch::where('status', 1)->get();
        $departments = Department::all();

        return view('hr.attendance_managment.edit', compact('attendance', 'branches', 'departments'));
    }


    public function update(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        $this->AttendanceService->update($request, $id);

        return redirect()->route('hr.attendance.index')
            ->with('success', 'Attendance updated successfully');
    }


    public function destroy($id)
    {
        if (!Gate::allows('attendance-delete')) {
            return abort(503);
        }

        $this->AttendanceService->destroy($id);

        return redirect()->route('hr.attendance.index')
            ->with('success', 'Attendance Deleted successfully');

    }

    public function fetchDepartment(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        try {
            $departments = $this->AttendanceService->fetchDepartment($request);
            return response()->json($departments);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function fetchEmployee(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        try {
            $employees = $this->AttendanceService->fetchEmployee($request);
            return response()->json($employees);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function employeesAttendance(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $request->all();

        $start_date = $data['date'];
        $branch_id = $data['branch_id'];
        $department_id = $data['department_id'];
        $employee_id = $data['employee_id'];

        if ($branch_id == null || $department_id == null) {
            return response()->json([
                'success' => false,
                'message' => 'Please select branch and department'
            ]);
        }

        if ($employee_id) {
            $employee = Employees::find($employee_id);
            return view('hr.attendance_managment.employee_attendance', compact('employee', 'start_date'));
        } else {
            $employees = Employees::where('branch_id', $branch_id)->orWhere('department_id', $department_id)->get();
            return view('hr.attendance_managment.employee_attendance', compact('employees', 'start_date'));
        }
    }

    public function getData(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $attendance = $this->AttendanceService->getData($request);
        return $attendance;
    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Attendance::where('id', $id)->delete();
        }

        return response()->json(['message' => 'Bulk action completed successfully']);
    }

    public function exportExcel()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Excel::download(new AttendanceExport, 'attendance.xlsx');
    }

}

