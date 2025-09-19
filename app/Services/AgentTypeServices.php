<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\User;
use App\Models\HR\Agent;
use App\Models\HR\AgentType;
use App\Models\HRM\Employees;
use App\Models\HRM\EmployeeTypes;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;


class AgentTypeServices
{

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return AgentType::all();
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
        $agent_type = new AgentType();
        $agent_type->name = $request->name;

        $agent_type->save();

    }



    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = AgentType::get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("hr.agent_type.destroy", $row->id) . '"> ';

                $btn = $btn . '<a href="' . route("hr.agent_type.edit", $row->id) . '" class="btn btn-primary  ml-2 mr-2 btn-sm">Edit</a>';
                $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['action', 'role_name', 'agent_name'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return AgentType::find($id);


    }

    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $agent_type = AgentType::find($id);
        $agent_type->name = $request->name;
        $agent_type->save();

    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $agent_type = AgentType::findOrFail($id);
        if ($agent_type)
            $agent_type->delete();

    }
}


