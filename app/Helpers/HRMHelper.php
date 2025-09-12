<?php

namespace App\Helpers;

use App\Models\Admin\Employees;
use App\User;
use Config;
use Gate;
use Illuminate\Support\Facades\Auth;

use App\Models\HRM\WorkShifts;
use App\Models\HRM\AttendanceSettings;



/**
 * Class to store the entire group tree
 */
class HRMHelper
{
    /**
     * Initializer
     */

    public static function getTimeSlots($shift_id = 1)
    {

        $shift_details = WorkShifts::getShiftById($shift_id);

        $shift_start_time = $shift_details->shift_start_time . ':00';
        $shift_end_time = $shift_details->shift_end_time . ':00';

        $settings = AttendanceSettings::where('shift_id', $shift_id)->get();
        $time_slots_array = array();
        foreach ($settings as $key => $val) {
            $temp_array['in_start'] = date('H:i:s', strtotime($val->in_start . " minutes", strtotime($shift_start_time)));
            $temp_array['in_end'] = date('H:i:s', strtotime($val->in_end . " minutes", strtotime($shift_start_time)));
            $temp_array['out_start'] = date('H:i:s', strtotime($val->out_start . " minutes", strtotime($shift_end_time)));
            $temp_array['out_end'] = date('H:i:s', strtotime($val->out_end . " minutes", strtotime($shift_end_time)));
            $temp_array['attendance_type'] = $val->attendance_type;
            array_push($time_slots_array, $temp_array);
        }

        return $time_slots_array;

    }
    // helper function to get first_in and last_out for each day
    public static function firstInLastOut($Attendance)
    {
        $faulty_attendace = array();
        $first_in_last_out = array();
        $day_wise_firstin_lastout = array();

        $day_wise_attendance = array();
        $single_day_attendance = array();
        $last_date = $curr_date = '';
        foreach ($Attendance as $key => $val) {
            // echo '<br> Date : '.substr($val->date_time,0,10);
            if (!isset($curr_date)) {
                $curr_date = substr($val->date_time, 0, 10);
            }
            if ($curr_date == substr($val->date_time, 0, 10)) {
                $single_day_attendance[substr($val->date_time, 11)] = $val->type;
            } else {
                $day_wise_attendance[$curr_date] = $single_day_attendance;
                $single_day_attendance = array();
                $curr_date = substr($val->date_time, 0, 10);
                $single_day_attendance[substr($val->date_time, 11)] = $val->type;
            }

        }

        $day_wise_attendance[$curr_date] = $single_day_attendance;
        $day_wise_attendance = array_filter($day_wise_attendance);
        //
//        $result = array();
        foreach ($day_wise_attendance as $key => $value) {
            //dd($key);
            $array_length = count($value);
            $first_key = array_keys($value)[0];
            $last_key = array_keys($value)[$array_length - 1];
            if ($value[$first_key] == 'I' || $value[$last_key] == 'O') {
                $day_wise_firstin_lastout[$key] = array($first_key, $last_key);
                array_push($first_in_last_out, array($first_key, $last_key));

            } else {
                array_push($faulty_attendace, $key);
            }

        }
        $result['faulty_attendace'] = $faulty_attendace;
        $result['first_in_last_out'] = $first_in_last_out;
        $result['day_wise_firstin_lastout'] = $day_wise_firstin_lastout;


        return $result;

    }


}