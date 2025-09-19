<?php

namespace App\Services;

use Config;
use DataTables;
use Illuminate\Support\Facades\Gate;
use App\Models\HR\TeacherAssignSession;


class TeacherAssignSessionServices
{

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return TeacherAssignSession::all();
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
        $agent = new TeacherAssignSession();
        $agent->session_id = $request->input('session_id');
        $agent->teacher_id = $request->input('teacher_id');
        $agent->save();

    }


    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        $data = TeacherAssignSession::with('sessions', 'users')->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('status', function ($row) {

                return ($row->status == 1) ? 'Active' : 'Inactive';

            })
            ->addColumn('action', function ($row) {
                $btn = ' <form class="delete_form" data-route="' . route("hr.teacher_assign_session.destroy", $row->id) . '"   id="Teacher-' . $row->id . '"  method="POST"> ';

                //  $btn = $btn . '<a  data-id="' . $row->id . '"  class="btn btn-primary  btn-sm teacher_assign_session_edit"  data-teacher_assign_session-edit=\'' . $row . '\'>Edit</a>';
    
                $btn = $btn . ' <button data-id="Teacher-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';

                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;

            })->addColumn('sessions', function ($row) {
                if (isset($row->sessions)) {
                    return $row->sessions->title;

                } else {

                    return 'N/A';
                }

            })->addColumn('users', function ($row) {
                if (isset($row->users)) {

                    return $row->users->name;

                } else {

                    return 'N/A';
                }

            })->rawColumns(['status', 'action', 'sessions'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return TeacherAssignSession::find($id);


    }

    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $agent = TeacherAssignSession::find($request->id);
        $agent->session_id = $request->input('session_id');
        $agent->teacher_id = $request->input('teacher_id');

        $agent->save();
        dd('okay');
    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $teacher = TeacherAssignSession::findOrFail($id);

        if ($teacher)
            $teacher->delete();

    }
}


