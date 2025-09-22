<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\User;
use App\Models\HR\Agent;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;


class AgentServices
{

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Agent::all();
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
        $agent = new Agent();
        $agent->name = $request->input('name');
        $agent->email = $request->input('email');
        $agent->address = $request->input('address');
        $agent->mobile = $request->input('mobile');
        $agent->parent_id = $request->input('parent_id');
        $agent->agent_type_id = $request->input('agent_type_id');
        $fileNameToStore = null;
        if ($request->hasfile('image')) {

            $file = $request->file('image');
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename = preg_replace("/\s+/", '-', $filename);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $destinationPath = 'agent_files';
            $file->move($destinationPath, $fileNameToStore);
            $agent->image = $destinationPath . '/' . $fileNameToStore;
        }
        $agent->save();


        $agent_user = User::where('email', $agent->email)->first();
        if (!$agent_user) {
            $agent_user = new User();
        }
        $agent_user->email = $agent->email;
        $agent_user->name = $agent->name;
        $agent_user->password = Hash::make('12345678');
        $agent_user->agent_id = $agent->id;
        $agent_user->role_id = Role::where('name', 'Agent')->first()->id;
        $agent_user->assignRole([$agent_user->role_id]);
        $agent_user->save();


    }

    public function user_deactive($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $user = User::find($id);
        if ($user) {
            $user->active = 0;
            $user->save();
        }
    }


    public function user_active($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $user = User::find($id);
        if ($user) {
            $user->active = 1;
            $user->save();
        }

    }


    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Agent::with('agent_type')->get();
        return Datatables::of($data)->addIndexColumn()
            ->setRowClass(function ($row) {
                if ($row->status == 0) {
                    return 'bg-danger text-white';
                }
            })
            ->addColumn('status', function ($row) {

                return ($row->status == 1) ? 'Active' : 'Inactive';


            })->addColumn('agent_type', function ($row) {
                if ($row->agent_type) {
                    return $row->agent_type->name;
                } else {
                    return 'N/A';
                }
            })->addColumn('parent_id', function ($row) {
                if ($row->agent_type_id == 1) {


                    $name = Agent::find($row->parent_id);
                    if (isset($name)) {
                        return $name->name;
                    } else {
                        return 'N/A';
                    }

                } else {
                    return 'N/A';
                }
            })
            ->addColumn('action', function ($row) {
                $btn = ' <form class="delete_form" data-route="' . route("hr.agent.destroy", $row->id) . '"   id="Agent-' . $row->id . '"  method="POST"> ';

                $btn = $btn . '<a  data-id="' . $row->id . '"  class="btn btn-primary  btn-sm agent_edit"  data-agent-edit=\'' . $row . '\'>Edit</a>';

                $btn = $btn . ' <button data-id="Agent-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';

                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })->addColumn('comission', function ($row) {

                return ($row->comission) . '%';

            })
            ->rawColumns(['status', 'action', 'agent_type'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Agent::find($id);


    }

    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $agent = Agent::find($request->id);
        $agent->name = $request->input('name');
        $agent->email = $request->input('email');
        $agent->address = $request->input('address');
        $agent->parent_id = $request->input('parent_id');
        $agent->mobile = $request->input('mobile');
        $agent->agent_type_id = $request->input('agent_type_id');
        $agent->status = $request->status;

        $fileNameToStore = null;
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename = preg_replace("/\s+/", '-', $filename);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $destinationPath = 'agent_files';
            $file->move($destinationPath, $fileNameToStore);
            $agent->image = $destinationPath . '/' . $fileNameToStore;
        }

        $agent->save();

    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $agent = Agent::findOrFail($id);
        $user_agent = User::where('agent_id', $id)->first();

        if ($agent)
            $agent->delete();

        if ($user_agent)
            $user_agent->delete();

    }
}


