<?php

namespace App\Services;

use App\Models\Admin\Session;
use Config;
use DataTables;
use Illuminate\Support\Facades\Gate;


class SessionService
{

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }


    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        // return Permission::with('child')->where('main', 1)->get();

    }

    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        $session = Session::create(['title' => $request->title, 'start_date' => $request->start_date, 'end_date' => $request->end_date, 'start_time' => $request->start_time, 'end_time' => $request->end_time, 'course_id' => $request->course_id]);

    }


    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Session::join('courses', 'courses.id', '=', 'sessions.course_id')
            ->select('sessions.*', 'courses.name as course_name');
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('status', function ($row) {

                return ($row->status == 1) ? 'Active' : 'Inactive';

            })
            ->addColumn('action', function ($row) {

                $btn = ' <form class="delete_form" data-route="' . route("admin.session.destroy", $row->id) . '"   id="Session-' . $row->id . '"  method="POST"> ';
                if (Gate::allows('session-edit'))
                    $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary  btn-sm session_edit"  data-session-edit=\'' . $row . '\'>Edit</a>';
                if (Gate::allows('course-video'))
                    $btn = $btn . '<a href="' . route("admin.session_videos", $row->id) . '" class="btn btn-primary  btn-sm" style="
    margin-right: 3px;
    margin-left: 5px;
" ">Videos</a>';

                if (Gate::allows('session-delete'))
                    $btn = $btn . ' <button data-id="Session-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Session::find($id);


    }


    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Session::find($id);
        $input = $request->all();
        $data->update($input);
    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $session = Session::findOrFail($id);
        if ($session)
            $session->delete();
    }
}

