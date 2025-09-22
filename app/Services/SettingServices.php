<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\Admin\Course;
use App\Models\Admin\VideoCategory;
use App\Models\Admin\WebCrmSetting;
use Illuminate\Support\Facades\Gate;


class SettingServices
{

    public function web_form_edit_post($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $banner = WebCrmSetting::where('page_name', 'web_registration_form_banner')->first();
        if ($banner) {
            $fileNameToStore = null;
            if ($request->hasfile('banner_image')) {
                $file = $request->file('banner_image');
                $filenameWithExt = $file->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
                $filename = preg_replace("/\s+/", '-', $filename);
                $extension = $file->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $destinationPath = 'banner_image_web';
                $file->move($destinationPath, $fileNameToStore);
                $banner->value = $destinationPath . '/' . $fileNameToStore;
            }
            $banner->save();
        }

    }


}


