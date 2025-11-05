<?php

namespace App\Helpers;

use App\Models\HR\OverTime;
use App\Models\HRM\Employees;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GeneralSettingsHelper
{
    public static function getSetting($key)
    {
        $setting = DB::table('general_settings')->where('key', $key)->first();
        return $setting ? json_decode($setting->values, true) : null;
    }

        public static function getGeneral($key)
        {
            $setting = DB::table('general_settings')->where('key', $key)->first();
            return $setting ? $setting->values : null;
        }

    public static function calculateLateAndOvertime($startTime, $endTime, $checkinTime, $checkoutTime, $gracePeriod, $date)
    {
        $startTime = Carbon::createFromTimeString($startTime);
        $endTime = Carbon::createFromTimeString($endTime);
        $checkinTime = Carbon::createFromTimeString($checkinTime);
        $checkoutTime = Carbon::createFromTimeString($checkoutTime);

        $startTimegrace = $startTime->copy();
        $startTimeWithGracePeriod = $startTimegrace->addMinutes($gracePeriod);
        $late_time = $checkinTime->greaterThan($startTimeWithGracePeriod);

        $lateTime = $late_time ?  $checkinTime->diffInMinutes($startTimeWithGracePeriod): 0;

        $hours = floor($lateTime / 60);
        $minutes = $lateTime % 60;
        $lateTimeString = $hours . " Hr : " . $minutes . " Min";


        if ($checkinTime->lte($startTime)) {
            $checkinTime = $startTime;
        }
        if ($checkoutTime->gt($endTime)) {
            $totalMinutes = $checkinTime->diffInMinutes($endTime);
        } else {
            $totalMinutes = $checkinTime->diffInMinutes($checkoutTime);
        }

        $totalHours = floor($totalMinutes / 60);
        $totalMinutes %= 60;
        $totalHoursWorked = $totalHours . " hr" . ($totalHours !== 1 ? 's' : '');

        if ($totalMinutes > 0) {
            $totalHoursWorked .= " : " . $totalMinutes . "min" . ($totalMinutes !== 1 ? 's' : '');
        }


        $overtime = OverTime::where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where('action', 'yes')
            ->get();
        $emp_id = [];
        foreach ($overtime as $item) {
            $emp_id[] = $item['employee_id'];
        }
        $employees = Employees::whereIn('id', $emp_id)->get();

        $totalovertimeHours = '';
        $overtime = '';
        $overtime_Time = 0;
        if ($employees->isNotEmpty()) {
            $totalMinutes = $checkinTime->diffInMinutes($checkoutTime);
            $totalHours = floor($totalMinutes / 60);

            $overtime = $checkoutTime->greaterThan($endTime);

            if ($overtime == true) {
                $time = $startTime->diffInHours($endTime);
                $overtime_Time = max(0, $totalHours - $time - 1);
            }
        }

        return [
            'late' => $late_time,
            'total_hours_worked' => $totalHoursWorked,
            'overtime' => $overtime,
            'overtime_time' => $overtime_Time,
            'lateTime' => $lateTimeString,
            'overtime_hours' => $totalovertimeHours,
        ];
    }

    public static function calculateHourlyOvertime($startTime, $endTime, $checkinTime, $checkoutTime, $gracePeriod)
    {
        $startTime = Carbon::createFromTimeString($startTime);
        $endTime = Carbon::createFromTimeString($endTime);
        $checkinTime = Carbon::createFromTimeString($checkinTime);
        $checkoutTime = Carbon::createFromTimeString($checkoutTime);

        //dd($startTime,$endTime,$checkinTime,$checkoutTime);

        $startTimeWithGracePeriod = $startTime->addMinutes($gracePeriod);

        //        for late
        $late = $checkinTime->greaterThan($startTimeWithGracePeriod);
        $lateTime = $checkinTime->diffInMinutes($startTimeWithGracePeriod);
        $hours = floor($lateTime / 60);
        $minutes = $lateTime % 60;
        $lateTimeString = $hours . " Hr : " . $minutes . " Min";
        //       end late

        //for total hour worked

        $totalMinutes = $checkoutTime->diffInMinutes($checkinTime);
        $totalHours = floor($totalMinutes / 60);
        $totalMinutes %= 60;
        $totalHoursWorked = $totalHours . " hr" . ($totalHours !== 1 ? 's' : '');

        if ($totalMinutes > 0) {
            $totalHoursWorked .= " : " . $totalMinutes . "min" . ($totalMinutes !== 1 ? 's' : '');
        }


        $overtime = $checkoutTime->greaterThan($endTime);
        $overtimeHours = $overtime ? $checkoutTime->diffInHours($endTime) : 0;

        return [
            'late' => $late,
            'total_hours_worked' => $totalHoursWorked,
            'overtime' => $overtime,
            'lateTime' => $lateTimeString,
            'overtime_hours' => $overtimeHours,
        ];
    }

}


//
//$totalMinutes = $checkoutTime->diffInMinutes($checkinTime);
//dd($totalMinutes);
//$totalHours = floor($totalMinutes / 60);
//$totalMinutes %= 60;
//$totalHoursWorked = $totalHours . " hr" . ($totalHours !== 1 ? 's' : '');
