<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{

    public function __construct(SettingServices $SettingServices)
    {
        $this->SettingServices = $SettingServices;
    }

    public function web_form_edit()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('admin.web_registration_form_banner.edit');
    }

    public function web_form_edit_post(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $validated = $request->validate([
            'banner_image' => 'mimes:jpeg,jpg,png,gif|dimensions:width=1349,height=460'
        ]);

        $this->SettingServices->web_form_edit_post($request);
        Session::flash('success', 'Web Banner Updated Sucessfully Add!!!.');

        Session::flash('alert-class', 'alert-success');

        return redirect()->route('admin.web_form_edit');
    }
}
