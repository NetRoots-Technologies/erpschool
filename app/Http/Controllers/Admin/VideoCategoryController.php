<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Course;
use App\Models\Admin\CourseType;
use App\Models\Admin\Session;
use App\Services\CourseServices;
use App\Services\VideoCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session as Ses;


class VideoCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(VideoCategoryService $VideoCategoryService)
    {
        $this->VideoCategoryService = $VideoCategoryService;
    }
    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        $sessions = Session::get();

        return view('admin.video_category_headings.index', compact('sessions'));
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
        $sessions = Session::get();

        return view('admin.video_category_headings.create', compact('sessions'));

    }

    public function getData()
    {

        $video = $this->VideoCategoryService->getData();
        return $video;
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
            'name' => 'required|unique:courses|max:255',
            'session_id' => 'required',

        ]);

        $this->VideoCategoryService->store($request);
        Ses::flash('flash_message_sucess', 'VideoCategory added Successfully!!!.');
        Ses::flash('alert-class', 'alert-success');
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
        $courses = Course::find($id);
        return view('admin.course.edit', compact('courses'));
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
        $this->validate($request, [

            'name' => 'required',

            'session_id' => 'required',
        ]);

        $this->VideoCategoryService->update($request, $id);
        Ses::flash('flash_message_sucess', 'Course Successfully Updated!!!.');
        Ses::flash('alert-class', 'alert-success');
        return 'done';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->VideoCategoryService->destroy($id);
        //
    }
}

