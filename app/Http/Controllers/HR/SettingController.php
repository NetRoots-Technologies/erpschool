<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Designation;
use App\Models\HR\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class SettingController extends Controller
{
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $settings = GeneralSetting::all();
        $settingValue = [];
        foreach ($settings as $setting) {
            $settingValue[$setting->key] = [
                'values' => json_decode($setting->values, true),
                'name' => $setting->name
            ];
        }
        $Designations = Designation::all();
        $selectedDesignations = $Designations->pluck('id')->toArray();

        return view('hr.settings.index', compact('settingValue', 'selectedDesignations', 'Designations'));
    }



    public function updateSetting(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        foreach ($request->except('_token') as $key => $value) {
            $setting = GeneralSetting::where('key', $key)->first();
            if ($setting) {
                $setting->values = json_encode($value);
                $setting->save();
            } else {
                GeneralSetting::create([
                    'key' => $key,
                    'values' => json_encode($value)
                ]);
            }
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

}
