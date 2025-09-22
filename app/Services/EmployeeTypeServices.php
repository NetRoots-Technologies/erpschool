<?php

namespace App\Services;

use App\Models\HRM\Employees;
use App\Models\HRM\EmployeeTypes;
use App\Models\User;
use Config;
use App\Models\HR\Agent;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class EmployeeTypeServices
{

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return EmployeeTypes::all();
    }


    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

    }

    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $employee_type = new EmployeeTypes();
        $employee_type->name = $request->name;
        $employee_type->working_hours = $request->working_hours;
        $employee_type->save();

    }


    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = EmployeeTypes::get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("hr.employee_type.destroy", $row->id) . '"> ';
                if (Gate::allows('employee_type-edit'))
                    $btn = $btn . '<a href="' . route("hr.employee_type.edit", $row->id) . '" class="btn btn-primary  ml-2 mr-2 btn-sm">Edit</a>';
                if (Gate::allows('employee_type-delete'))
                    $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })->addColumn('working_hours', function ($row) {

                return $row->working_hours . ' hrs';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return EmployeeTypes::find($id);


    }

    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $employee_type = EmployeeTypes::find($id);
        $employee_type->name = $request->name;
        $employee_type->working_hours = $request->working_hours;
        $employee_type->save();

    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $employee_type = EmployeeTypes::findOrFail($id);
        if ($employee_type)
            $employee_type->delete();

    }
}


