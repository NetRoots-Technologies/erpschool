<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\User;
use App\Models\HR\Agent;
use App\Models\Admin\Course;
use App\Models\HRM\Employees;
use App\Models\Admin\VideoUpload;
use App\Models\HRM\EmployeeTypes;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;


class VideoUploadServices
{

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return VideoUpload::all();
    }


    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('admin.video.index');

    }

    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        foreach ($request->name as $key => $value) {
            $video = new VideoUpload();
            $video->course_id = $request->course_id;
            $video->session_id = $request->session_id;
            $video->name = $request->name[$key];
            $video->video_id = $request->video_id[$key];
            $video->video_description = $request->video_description[$key];
            $video->video_categories_id = $request->video_categories_id[$key];

            $video->save();
        }

    }


    public function getdata($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        //        $data = VideoUpload::with('get_course')->get();
        $data = VideoUpload::with('video_heading', 'session')->where('session_id', $request->id)->get();


        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("admin.video.destroy", $row->id) . '"> ';

                //                $btn = $btn . '<a href="' . route("admin.video.edit", $row->id) . '" class="btn btn-primary  ml-2 mr-2 btn-sm">Edit</a>';
                $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })->addColumn('get_course', function ($row) {

                if (isset($row->get_course)) {
                    return $row->get_course->name;
                } else {
                    return 'N/A';
                }
            })->addColumn('session', function ($row) {

                if (isset($row->session)) {
                    return $row->session->title;
                } else {
                    return 'N/A';
                }
            })->addColumn('video_heading', function ($row) {

                if (isset($row->video_heading)) {
                    return $row->video_heading->name;
                } else {
                    return 'N/A';
                }

            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return VideoUpload::find($id);


    }

    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        $video = VideoUpload::find($id);
        $video->name = $request->name;
        $video->video_id = $request->video_id;
        $video->course_id = $request->course_id;
        $video->session_id = $request->session_id;

        $video->video_description = $request->video_description;
        $video->save();

    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $video = VideoUpload::findOrFail($id);


        if ($video)
            $video->delete();

    }
}


