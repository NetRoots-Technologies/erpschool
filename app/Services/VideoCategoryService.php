<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\Admin\Course;
use App\Models\Admin\VideoCategory;
use Illuminate\Support\Facades\Gate;


class VideoCategoryService
{

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return VideoCategory::all();
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
        $video_category = VideoCategory::create(['name' => $request->name, 'session_id' => $request->session_id]);

    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = VideoCategory::find($id);
        $input = $request->all();
        $data->update($input);
    }





    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = VideoCategory::with('course', 'session')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('course', function ($row) {
                if (isset($row->course))
                    return $row->course->name;
                else
                    return 'N/A';


            })->addColumn('session', function ($row) {
                if (isset($row->session))
                    return $row->session->title;
                else
                    return 'N/A';


            })
            ->addColumn('action', function ($row) {
                $btn = ' <form class="delete_form" data-route="' . route("admin.video_category.destroy", $row->id) . '"   id="videoCategory-' . $row->id . '"  method="POST"> ';

                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary  btn-sm videoCategory_edit"  data-videocategory_edit=\'' . $row . '\'>Edit</a>';


                $btn = $btn . ' <button data-id="videoCategory-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';

                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;

            })
            ->rawColumns(['course', 'action', 'session'])
            ->make(true);
    }
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $video_category = VideoCategory::findOrFail($id);
        if ($video_category)
            $video_category->delete();

    }
}

