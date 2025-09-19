<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\Admin\Course;
use App\Models\Admin\Notification;
use App\Models\Admin\VideoCategory;
use Illuminate\Support\Facades\Gate;


class MarketingNotificationService
{

    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        //        $data['Categorys'] = Category::all();
//        $data['Sub_Category'] = Sub_Category::all();
        return 0;
    }


    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $request->all();
        $data['banner_title'] = $request->banner_title;
        $data['banner_description'] = $request->banner_description;

        if ($request->hasfile('notification_image')) {
            $file = $request->file('notification_image');
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename = preg_replace("/\s+/", '-', $filename);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $destinationPath = 'notifications';
            $file->move($destinationPath, $fileNameToStore);
            $data['notification_image'] = $destinationPath . '/' . $fileNameToStore;
        }

        $notifications = Notification::create($data);
        return 'created';

    }

    public function show($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Notification::where('id', $id)->first();
    }

    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Notification::select('id', 'notification_title', 'notification_description')->get();


        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form   onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');" method="POST"  action="' . route("admin.marketing_notification.destroy", $row->id) . '"> ';
                $btn = $btn . '<a href=" ' . route("admin.marketing_notification.show", $row->id) . '"  class="ml-2"><i class="fas fa-eye"></i></a>';
                $btn = $btn . ' <a href="' . route("admin.marketing_notification.edit", $row->id) . '" class="ml-2">  <i class="fas fa-edit"></i></a>';
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
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Notification::where('id', $id)->first();
    }

    public function uploadImage($path, $image)
    {
        if (!Gate::allows('Dashboard-list')) {
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
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $banner = Notification::find($id);
        $banner->notification_title = $request->notification_title;
        $banner->notification_description = $request->notification_description;
        if ($request->hasfile('notification_image')) {
            $file = $request->file('notification_image');
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename = preg_replace("/\s+/", '-', $filename);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $destinationPath = 'dist/notifications';
            $file->move($destinationPath, $fileNameToStore);
            $banner->notification_image = $destinationPath . '/' . $fileNameToStore;
        }
        $banner->save();
    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $Service = Notification::findOrFail($id);
        if (asset($Service))
            $Service->delete();
    }
}


