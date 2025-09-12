<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\Admin\Course;
use App\Models\Admin\Notification;
//use App\Models\MarktingVideos;
use App\Models\Admin\markting_video;
use Illuminate\Support\Facades\Gate;


class MarketingVideoServices
{
    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        //        $data['Categorys'] = Category::all();
//        $data['Sub_Category'] = Sub_Category::all();
        return 0;
    }


    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $request->all();
        $data['name'] = $request->name;
        $data['description'] = $request->description;
        $data['video_id'] = $request->video_id;
        $data['status'] = $request->status;
        $data['video_link'] = $request->video_link;
        $notifications = markting_video::create($data);
        return 'created';

    }

    public function show($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return markting_video::where('id', $id)->first();
    }

    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = markting_video::select('id', 'name', 'description')->get();


        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form   onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');" method="POST"  action="' . route("admin.marketing_video.destroy", $row->id) . '"> ';
                $btn = $btn . '<a href=" ' . route("admin.marketing_video.show", $row->id) . '"  class="ml-2"><i class="fas fa-eye"></i></a>';
                $btn = $btn . ' <a href="' . route("admin.marketing_video.edit", $row->id) . '" class="ml-2">  <i class="fas fa-edit"></i></a>';
                $btn = $btn . '<button  type="submit" class="ml-2"    ><i class="fas fa-trash"></i></button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['action', 'user'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return markting_video::where('id', $id)->first();
    }

    public function uploadImage($path, $image)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $folderPath = $path;
        $image_parts = explode(";base64,", $image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $image_name = uniqid() . '.' . $image_type;
        $file = $folderPath . $image_name;
        file_put_contents($file, $image_base64);
        return $image_name;
    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $banner = markting_video::find($id);

        $banner->name = $request->name;
        $banner->description = $request->description;
        $banner->video_id = $request->video_id;
        $banner->status = $request->status;
        $banner->video_link = $request->video_link;
        $banner->save();
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Service = markting_video::findOrFail($id);
        if (asset($Service))
            $Service->delete();
    }
}
