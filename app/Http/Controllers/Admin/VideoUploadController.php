<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Course;
use App\Models\Admin\Session;
use App\Models\Admin\VideoCategory;
use App\Models\Admin\VideoUpload;
use App\Services\VideoUploadServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VideoUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(VideoUploadServices $VideoUploadServices)
    {
        $this->VideoUploadServices = $VideoUploadServices;
    }

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('admin.video.index');
    }

    public function getData(Request $request)
    {

        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $this->VideoUploadServices->getdata($request);
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $id = $request->id;
        $sessions = Session::where('id', $request->id)->first();

        $video_categories = VideoCategory::where('session_id', $request->id)->get();
        return view('admin.video.create', compact('sessions', 'video_categories', 'id'));
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
            'video_id' => 'required',

        ]);
        $this->VideoUploadServices->store($request);


        return redirect()->route('admin.session_videos', $request->session_id)
            ->with('success', 'Video link uploaded successfully');


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

    public function session_videos($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('admin.video.index', compact('id'));
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
        $data = $this->VideoUploadServices->edit($id);
        return view('admin.video.edit', compact('data'));
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
            'name' => 'required',

        ]);
        $this->VideoUploadServices->update($request);


        return redirect()->route('admin.video.index')
            ->with('success', 'Video link updated successfully');

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
        $this->VideoUploadServices->destroy($id);
        return redirect()->back()
            ->with('danger', 'Video link deleted successfully');

    }
}

