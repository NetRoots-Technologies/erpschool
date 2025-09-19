<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Admin\Session;
use App\Models\HR\Teacher;
use App\Models\User;
use App\Services\TeacherAssignSessionServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TeacherAssignSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(TeacherAssignSessionServices $TeacherAssignSessionServices)
    {
        $this->TeacherAssignSessionServices = $TeacherAssignSessionServices;
    }
    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $teachers = User::where('role_id', 3)->get();
        $sessions = Session::get();

        return view('hr.teacherAssignSession.index', compact('teachers', 'sessions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('hr.teacherAssignSession.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $validated = $request->validate([
            'session_id' => 'required',
            'teacher_id' => 'required',

        ]);
        $this->TeacherAssignSessionServices->store($request);
        return 'done';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $teacher = $this->TeacherAssignSessionServices->edit($id);
        return view('hr.teacherAssignSession.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $validated = $request->validate([
            'session_id' => 'required',
            'teacher_id' => 'required',
        ]);
        $this->TeacherAssignSessionServices->update($request, $id);


        return 'done';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $teacher = $this->TeacherAssignSessionServices->getdata();
        return $teacher;
    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $teacher = $this->TeacherAssignSessionServices->destroy($id);
        return 'done';
    }
}

