<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Course;
use App\Services\SessionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Gate;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(SessionService $UserServise)
    {
        $this->SessionService = $UserServise;
    }

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        if (!Gate::allows('session-list')) {
            return abort(503);
        }
        $courses = Course::get();

        return view('admin.session.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('admin.session.create');
    }

    public function getData()
    {
if (!Gate::allows('students')) {
            return abort(503);
        }
        $Users = $this->SessionService->getdata();
        return $Users;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       if (!Gate::allows('students')) {
            return abort(503);
        }

        $validated = $request->validate([
            'title' => 'required|unique:sessions|max:255',
            'start_date' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'course_id' => 'required',

        ]);
        $this->SessionService->store($request);
        Session::flash('flash_message_sucess', 'Session Successfully Add!!!.');
        Session::flash('alert-class', 'alert-success');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = \App\Models\Admin\Session::find($id);
        return view('admin.session.edit', compact('data'));
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
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->validate($request, [

            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'course_id' => 'required',

        ]);

        $this->SessionService->update($request, $id);
        Session::flash('flash_message_sucess', 'Session Successfully Updated!!!.');
        Session::flash('alert-class', 'alert-success');
        return 'done';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->SessionService->destroy($id);
        return 'done';
    }
}
