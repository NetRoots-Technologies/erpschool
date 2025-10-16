<?php

namespace App\Services;


use App\Models\Admin\Branch;
use App\Models\Admin\Department;
use App\Models\HR\Attendance;
use App\Models\HR\EmployeeAttendance;
use App\Models\HRM\Employees;
use Dflydev\DotAccessData\Data;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\MessageBag;
use Yajra\DataTables\DataTables;

class AttendanceService
{

    public function fetchDepartment($request)
    {
    
        $departments = Department::where('branch_id', $request->branch_id)->get();
        return $departments;

    }

    public function fetchEmployee($request)
    {
    
        $employees = Employees::where('status', 1)->where('department_id', $request->department_id)->get();
        return $employees;
    }

    public function store($request)
    {
    
        $errors = new MessageBag();

        foreach ($request->get('employee_id') as $key => $employeeId) {
            $employee = Employees::where('id', $employeeId)->first();
            if ($employee) {
                $employeeJoin = $employee->start_date;
                if ($employeeJoin < $request->start_date) {
                    if ($request->status[$key] !== '0' && $request->status[$key] !== '2') {
                        Attendance::create([
                            'branch_id' => $request->branch_id,
                            'attendance_date' => $request->start_date,
                            'employee_id' => $employeeId,
                            'status' => $request->status[$key],
                            'timeIn' => $request->timeIn[$key],
                            'timeOut' => $request->timeOut[$key],
                            'remarks' => $request->remarks[$key],
                            'machine_status' => 0,
                        ]);
                    }
                } else {
                    $errors->add('error', 'Employee join date is after attendance date.');
                }
            } else {
                $errors->add('error', 'Employee not found.');
            }
        }

        return $errors->isEmpty() ? null : $errors;
    }



    public function getdata($request)
    {
    
        if (!empty($request->from_date)) {
            $data = Attendance::with('branch', 'employee')
                ->whereBetween('attendance_date', array($request->from_date, $request->to_date))
                ->get();
        } else {
            $data = Attendance::with('branch', 'employee')->get();
        }
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("hr.attendance.destroy", $row->id) . '"> ';
                if (Gate::allows('EmployeeAttendance-edit'))
                    $btn = $btn . '<a href="' . route("hr.attendance.edit", $row->id) . '" class="btn btn-primary  ml-2 mr-2 btn-sm">Edit</a>';
                if (Gate::allows('EmployeeAttendance-delete'))
                    $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->addColumn('branch', function ($row) {
                if ($row->branch) {
                    return $row->branch->name;
                } else {
                    return 'N/A';
                }
            })->addColumn('employee', function ($row) {
                if ($row->employee) {
                    return $row->employee->name;
                } else {
                    return 'N/A';
                }
            })->addColumn('attendance_date', function ($row) {
                if ($row->attendance_date) {
                    return $row->attendance_date;
                } else {
                    return 'N/A';
                }
            })
            ->addColumn('timeIn', function ($row) {
                if ($row->timeIn) {
                    return $row->timeIn;
                } else {
                    return 'N/A';
                }
            })->addColumn('timeOut', function ($row) {
                if ($row->timeOut) {
                    return $row->timeOut;
                } else {
                    return 'N/A';
                }
            })->addColumn('status', function ($row) {
                if ($row->status == 1) {
                    return 'Present';
                } else {
                    return 'Absent';
                }
            })
            ->rawColumns(['action', 'branch', 'employee', 'attendance_date', 'timeIn', 'timeOut', 'status'])
            ->make(true);
    }

    public function update($request, $id)
    {
    
        $attendance = Attendance::find($id);

        $attendance_data = [
            'branch_id' => $request->branch_id,
            'attendance_date' => $request->start_date,
            'employee_id' => $request->employee_id,
            'status' => $request->status,
            'timeIn' => $request->timeIn,
            'timeOut' => $request->timeOut,
            'remarks' => $request->remarks,
            'machine_status' => 0,
        ];

        $attendance->update($attendance_data);
    }

    public function destroy($id)
    {
    
        $attendance = Attendance::find($id);
        if ($attendance) {
            $attendance->delete();
        }
    }

}

