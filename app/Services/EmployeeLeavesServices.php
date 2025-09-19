<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\User;
use App\Models\HR\Agent;
use App\Models\HR\AgentType;
use App\Models\HRM\Employees;
use App\Models\HR\EmployeeLeaves;
use App\Models\HRM\EmployeeTypes;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Models\HR\AgentComissionPlan;


class EmployeeLeavesServices
{

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('hr.employee_leaves.index');
    }


    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $users = User::all();
        $employee = Employees::all();
        return view('hr.employee_leaves.create', compact('employee', 'users'));

    }

    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $employee_leaves = new EmployeeLeaves();
        $employee_leaves->leave_title = $request->leave_title;
        $employee_leaves->employee_id = $request->employee_id;
        $employee_leaves->leave_type = $request->leave_type;
        $employee_leaves->leave_reason = $request->leave_reason;
        $employee_leaves->leave_date = date('Y-m-d', strtotime($request->leave_date));
        ;

        $employee_leaves->hod_approval = $request->hod_approval;
        $employee_leaves->hr_approval = $request->hr_approval;
        $employee_leaves->team_lead_approval = $request->team_lead_approval;
        $employee_leaves->admin_approval = $request->admin_approval;
        $employee_leaves->save();

    }


    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = EmployeeLeaves::with('employee_name')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("hr.employee_leaves.destroy", $row->id) . '"> ';

                $btn = $btn . '<a href="' . route("hr.employee_leaves.edit", $row->id) . '" class="btn btn-primary  ml-2 mr-2 btn-sm mb-2">Edit</a>';
                $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })->addColumn('employee_name', function ($row) {

                return $row->employee_name->name;

            })->addColumn('hod_approval', function ($row) {
                if (isset($row->hod_approval))
                    if ($row->hod_approval == 1) {
                        $btn = '<p style="color: green;font-weight: bold">' . "Approved" . '</p>';
                        return $btn;
                    }
                if ($row->hod_approval == 2) {
                    $btn = '<p style="color: red;font-weight: bold">' . "Not Approved" . '</p>';
                    return $btn;
                } else {
                    return "Pending";
                }
            })->addColumn('hr_approval', function ($row) {
                if (isset($row->hr_approval))
                    if ($row->hr_approval == 1) {
                        $btn = '<p style="color: green;font-weight: bold">' . "Approved" . '</p>';
                        return $btn;
                    }
                if ($row->hr_approval == 2) {
                    $btn = '<p style="color: red;font-weight: bold">' . "Not Approved" . '</p>';
                    return $btn;
                } else {
                    return 'Pending';
                }
            })->addColumn('team_lead_approval', function ($row) {
                if (isset($row->team_lead_approval))
                    if ($row->team_lead_approval == 1) {
                        $btn = '<p style="color: green;font-weight: bold">' . "Approved" . '</p>';
                        return $btn;
                    }
                if ($row->team_lead_approval == 2) {
                    $btn = '<p style="color: red;font-weight: bold">' . "Not Approved" . '</p>';
                    return $btn;
                } else {
                    return 'Pending';
                }
            })->addColumn('admin_approval', function ($row) {
                if (isset($row->admin_approval))
                    if ($row->admin_approval == 1) {
                        $btn = '<p style="color: green;font-weight: bold">' . "Approved" . '</p>';
                        return $btn;
                    }
                if ($row->admin_approval == 2) {
                    $btn = '<p style="color: red;font-weight: bold">' . "Not Approved" . '</p>';
                    return $btn;
                } else {
                    return 'Pending';
                }
            })->addColumn('leave_type', function ($row) {
                if ($row->leave_type == 1) {
                    return 'Casual Leave';
                }
                if ($row->leave_type == 2) {
                    return 'Half Leave';
                }
                if ($row->leave_type == 3) {
                    return 'Annual Leave';
                }
            })
            ->rawColumns(['action', 'employee_name', 'leave_type', 'hod_approval', 'hr_approval', 'team_lead_approval', 'admin_approval'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $employee_leaves = EmployeeLeaves::find($id);
        $employee = Employees::all();
        return view('hr.employee_leaves.edit', compact('employee', 'employee_leaves'));


    }

    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $employee_leaves = EmployeeLeaves::find($id);
        $employee_leaves->leave_title = $request->leave_title;
        $employee_leaves->employee_id = $request->employee_id;
        $employee_leaves->leave_type = $request->leave_type;
        $employee_leaves->leave_reason = $request->leave_reason;
        $employee_leaves->leave_date = $request->leave_date;
        $employee_leaves->hod_approval = $request->hod_approval;
        $employee_leaves->hr_approval = $request->hr_approval;
        $employee_leaves->team_lead_approval = $request->team_lead_approval;
        $employee_leaves->admin_approval = $request->admin_approval;
        $employee_leaves->save();

    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $employee_leaves = EmployeeLeaves::findOrFail($id);
        if ($employee_leaves)
            $employee_leaves->delete();

        return redirect()->route('hr.employee_leaves.index');
    }
}


