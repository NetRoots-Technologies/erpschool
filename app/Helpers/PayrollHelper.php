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
        // dd($workShifts);
        foreach ($workShifts as $workShift) {
            $startDateTime = Carbon::createFromFormat('H:i', $workShift->start_time);
            // dd($startDateTime);
            $endDateTime = Carbon::createFromFormat('H:i', $workShift->end_time);
            $workingHours = $endDateTime->diffInMinutes($startDateTime);
            // dd($workingHours);
            $workHours[$workShift->id] = $workingHours;
        }

        // dd($workHours);
        $totalHours = [];
        foreach ($workHours as $key => $workHour) {
            $totalHours[$key] = $daysInMonth * $workHour;
        }

        //        dd($startDateTime,$endDateTime,$workingHours,$totalHours,$workHours);

        // dd($totalHours);
        return $totalHours;
    }

}
