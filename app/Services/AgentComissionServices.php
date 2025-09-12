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
use App\Models\HR\AgentComissionPlan;


class AgentComissionServices
{

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('hr.agentcommissionplan.index');
    }


    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $agent_type = AgentType::all();
        return view('hr.agentcommissionplan.create', compact('agent_type'));

    }

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $agent_comission = new AgentComissionPlan();
        $agent_comission->slab_name = $request->slab_name;
        $agent_comission->agent_type_id = $request->agent_type_id;
        $agent_comission->min = $request->min;
        $agent_comission->max = $request->max;
        $agent_comission->comission = $request->comission;
        $agent_comission->slab_type = $request->slab_type;
        $agent_comission->save();

    }



    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = AgentComissionPlan::with('agent_type')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("hr.agent_comission.destroy", $row->id) . '"> ';

                $btn = $btn . '<a href="' . route("hr.agent_comission.edit", $row->id) . '" class="btn btn-primary  ml-2 mr-2 btn-sm">Edit</a>';
                $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })->addColumn('agent_type', function ($row) {

                return $row->agent_type->name;

            })->addColumn('slab_type', function ($row) {
                if ($row->slab_type == 1) {
                    return 'Comission';
                }if ($row->slab_type == 2) {
                    return 'Recovery';
                }
            })
            ->rawColumns(['action', 'agent_type', 'slab_type'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return AgentComissionPlan::find($id);


    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $agent_comission = AgentComissionPlan::find($id);
        $agent_comission->slab_name = $request->slab_name;
        $agent_comission->agent_type_id = $request->agent_type_id;
        $agent_comission->min = $request->min;
        $agent_comission->max = $request->max;
        $agent_comission->comission = $request->comission;
        $agent_comission->slab_type = $request->slab_type;
        $agent_comission->save();

    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $agent_comission = AgentComissionPlan::findOrFail($id);
        if ($agent_comission)
            $agent_comission->delete();

        return redirect()->route('hr.agent_comission.index');
    }
}

