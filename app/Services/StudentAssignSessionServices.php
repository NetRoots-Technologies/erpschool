<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\Student\Students;
use Illuminate\Support\Facades\Gate;
use App\Models\HR\TeacherAssignSession;



class StudentAssignSessionServices
{

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Students::all();
    }



    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

    }

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        //        $agent = new TeacherAssignSession();
//        $agent->session_id = $request->input('session_id');
//        $agent->teacher_id = $request->input('teacher_id');
//        $agent->save();

    }




    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Students::join('sessions', 'sessions.id', '=', 'students.session_id')->select('students.id as id', 'sessions.title as session_title', 'students.name as name', 'students.status as status')->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('status', function ($row) {

                return ($row->status == 1) ? 'Active' : 'Inactive';

            })

            ->rawColumns(['status'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return TeacherAssignSession::find($id);


    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
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
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $teacher = TeacherAssignSession::findOrFail($id);

        if ($teacher)
            $teacher->delete();

    }
}

