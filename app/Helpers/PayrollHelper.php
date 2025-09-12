<?php

namespace App\Helpers;

use App\Models\HR\WorkShift;
use Carbon\Carbon;

class PayrollHelper
{
    public static function getTotalHoursInMonth($month)
    {
        $daysInMonth = $month;

        //        $hoursInDay = 24;
//        $totalWorkingHours = 0;
        $workShifts = WorkShift::with('workdays')->get();
        $workHours = [];

        foreach ($workShifts as $workShift) {
            $startDateTime = Carbon::createFromFormat('H:i:s', $workShift->start_time);
            $endDateTime = Carbon::createFromFormat('H:i:s', $workShift->end_time);
            $workingHours = $endDateTime->diffInMinutes($startDateTime);

            $workHours[$workShift->id] = $workingHours;
        }
        $totalHours = [];
        foreach ($workHours as $key => $workHour) {
            $totalHours[$key] = $daysInMonth * $workHour;
        }

        //        dd($startDateTime,$endDateTime,$workingHours,$totalHours,$workHours);

        //dd($totalHours);
        return $totalHours;
    }

}
