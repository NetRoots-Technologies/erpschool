<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\TeacherAssignSession;
use App\Models\HR\Teacher;
use App\Services\TeacherServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(TeacherServices $TeacherServices)
    {
        $this->TeacherServices = $TeacherServices;
    }

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        //        $session_teacher = TeacherAssignSession::get('teacher_id');
//        foreach ($session_teacher as $key=>$value){
//
//            $teacher = Teacher::whereNotIn('id' ,$value)->pluck('id','name');
//
//
//        }
//        dd($teacher);

        return view('hr.teacher.index');
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
        return view('hr.teacher.create');
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
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'mobile' => 'required',
            'salary' => 'required',
        ]);
        $this->TeacherServices->store($request);
        return 'done';

        //        return redirect()->route('hr.agent.index')
//            ->with('success','Agent created successfully');
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
        $teacher = $this->TeacherServices->edit($id);
        return view('hr.teacher.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'mobile' => 'required',
            'salary' => 'required',
        ]);
        $this->TeacherServices->update($request, $id);


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
        $teacher = $this->TeacherServices->getdata();
        return $teacher;
    }

    public function destroy($id)
    {
         if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $teacher = $this->TeacherServices->destroy($id);
        return 'done';
    }
}

