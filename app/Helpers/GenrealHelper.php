<?php

namespace App\Helpers;

use App\Models\HRM\CardIssuance;
use App\Models\HRM\JobApplication;
use App\Models\HRM\Holidays;
use App\Models\HRM\LeaveRequests;
use App\Models\Admin\QuottaSettings;
use App\Models\HRM\Employees;
use App\Models\HRM\LeaveEntitlements;
use App\Models\HRM\Leaves;
use App\Models\HRM\LeaveStatuses;
use App\Models\HRM\LeaveTypes;
use App\Models\HRM\WorkShifts;
use App\Models\HRM\ManageFiness;
use App\Models\HRM\WorkWeeks;
use App\Helpers\UserHelper;
use App\Models\HRM\LeaveRules;
use App\Models\Admin\TaxSettings;
use App\Models\HRM\EmployeeAttendance;
use App\Models\HRM\EmployeesOvertime;
use App\Notifications\MemoNotification;
use App\Models\HRM\Advances;



use App\User;
use Config;
use Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use DateTime;
use DatePeriod;
use DateInterval;

class GenrealHelper
{
    /**
     * Initializer
     */
    private $_user;


    function GenrealHelper(User $user)
    {
        $this->_user = $user;
    }

    public static function removeComaFromNumber($string)
    {
        return preg_replace("/[^0-9.]/", '', $string);
    }
    public static function leaveDataPostmartem()
    {
        $user_id = Auth::user()->id;
        $leave_response = array();
        for ($i = 1; $i < 5; $i++) {
            $leave_response[$i] = self::leaveData($user_id, $i);
        }

        return $leave_response;
    }

    public static function notification($users, $data)
    {
        $data = \Notification::send($users, new MemoNotification($data));
        return $data;
    }
    public static function leaveData($user_id, $leave_type)
    {

        $employment_type = Employees::where('user_id', $user_id)->first()->employment_type;

        $permitted_days = 0;
        $leaves_without_pay = 0;
        $Employee = Employees::getEmployeeDetaailByUserId($user_id);
        $LeaveType = \Config::get('hrm.leave_types.' . $leave_type);


        $current_date = Carbon::now()->format('Y-m-d');

        $balance_data['pending'] = $pending = self::processLeaveData($user_id, 3, $leave_type);
        $balance_data['scheduled'] = $scheduled = self::processLeaveData($user_id, 4, $leave_type);
        $balance_data['taken'] = $taken = self::processLeaveData($user_id, 5, $leave_type);


        $LeaveEntitlement = LeaveEntitlements::where(array(
            'employee_id' => $user_id,
            'leave_type_id' => $leave_type,
        ))
            ->where('start_date', '<=', $current_date)
            ->where('end_date', '>=', $current_date)
            ->select('id', 'no_of_days', 'days_used')
            ->first();


        if ($employment_type == '1') {
            //            dd('1');
            $leaves = LeaveRequests::getActiveByUserIdYearAndType($user_id, \Carbon\Carbon::now()->year, $leave_type);
            //            dd($leaves);
        } else {
            $leaves = [];
            if ($Employee->contracts) {
                $contract_start_date = $Employee->contracts->last()->contract_start_date;
                $contract_end_date = $Employee->contracts->last()->contract_end_date;
                $leaves = LeaveRequests::getActiveByUserIdDatesAndType($user_id, $contract_start_date, $contract_end_date, $leave_type);
            }
        }

        $taken = 0;
        $scheduled = 0;
        $pending_approval = 0;
        //
        //exit;
        foreach ($leaves as $leaveEn) {
            if ($leaveEn->leave_status_id == 5) {
                // $taken = $leaveEn->leave_count;
                if ($leaveEn->partial_days == 'short') {
                    $taken += $leaveEn->single_hours_duration / 8;
                } else {
                    $taken += $leaveEn->total_days;
                }
            } else if ($leaveEn->leave_status_id == 4) {
                // $scheduled = $leaveEn->leave_count;
                if ($leaveEn->partial_days == 'short') {
                    $scheduled += $leaveEn->single_hours_duration / 8;
                } else {
                    $scheduled += $leaveEn->total_days;
                }
            } else if ($leaveEn->leave_status_id == 3) {
                // $pending_approval = $leaveEn->leave_count;
                if ($leaveEn->partial_days == 'short') {
                    $pending_approval += $leaveEn->single_hours_duration / 8;
                } else {
                    $pending_approval += $leaveEn->total_days;
                }
            }

        }

        $quotta = QuottaSettings::getQuottaByUserIdAndLeaveType($user_id, $leave_type);

        if ($quotta) {
            $permitted_days = $quotta;
        }
        if (!$quotta) {
            $quotta = new \stdClass();
            $quotta->id = null;
            $quotta->permitted_days = 0;
        }
        if (!$LeaveEntitlement) {
            $LeaveEntitlement = new \stdClass();
            // $LeaveEntitlement->id = null;
            $LeaveEntitlement->id = 0;
            $LeaveEntitlement->no_of_days = $permitted_days;
            $LeaveEntitlement->days_used = 0;
        }

        // for maternity leaves
        if ($leave_type == 4) {
            if ($balance_data['pending'] > 0 || $balance_data['scheduled'] > 0 || $balance_data['taken'] > 0) {

                $balance_data['pending'] = $pending > 0 ? $LeaveEntitlement->no_of_days : 0;
                $balance_data['scheduled'] = $balance_data['scheduled'] > 0 ? $LeaveEntitlement->no_of_days : 0;
                $balance_data['taken'] = $taken > 0 ? $LeaveEntitlement->no_of_days : 0;

            }
        }
        $balance_data['entitle'] = $LeaveEntitlement->no_of_days;

        return $balance_data;
    }

    public static function processLeaveData($user_id, $status, $type)
    {

        $leavedata = Leaves::statusWiseLeaves($user_id, $status, $type);
        $leavedata = json_decode(json_encode($leavedata), true);


        if ($type == 2) {
            //            $leaves = Leaves::where(['employee_id' => $user_id , 'work_shift_id' => 1, 'leave_type_id' => 2 , 'leave_status_id' => $status ])->whereIn('leave_status_id', [3,4,5])->OrderBy('leave_date', 'asc')->get();
            $leaves = Leaves::where(['employee_id' => $user_id, 'work_shift_id' => 1, 'leave_type_id' => 2, 'leave_status_id' => $status])->OrderBy('leave_date', 'asc')->get();
            $leaves = json_decode(json_encode($leaves), true);
            $simple_leaves = array();
            $simple_leave_count = 0;
            $sandwitch_leave_count = 0;
            $sandwitch_leaves = array();
            //            dd($leaves);
            foreach ($leaves as $key => $value) {
                $value['leave_date'];
                $timestamp = strtotime($value['leave_date']);
                $day = date('D', $timestamp);
                // check for sandwitch
                if ($day == 'Fri' && $value['shift'] == 'full') {
                    $new_monday = date('Y-m-d', strtotime($value['leave_date'] . " + 3 day"));
                    if (isset($leaves[$key + 1]) && $leaves[$key + 1]['leave_date'] == $new_monday) {
                        $sandwitch_leave_count++;
                        array_push($sandwitch_leaves, $value['leave_date']);
                        array_push($sandwitch_leaves, $leaves[$key + 1]['leave_date']);
                    } else {
                        // it is a simple leave not a sandwitch leave

                        if (!in_array($value['leave_date'], $sandwitch_leaves)) {
                            $simple_leave_count += self::leaveValue($value);
                            array_push($simple_leaves, $value['leave_date']);
                        }

                    }

                } else {

                    if (!in_array($value['leave_date'], $sandwitch_leaves)) {
                        $simple_leave_count += self::leaveValue($value);
                        array_push($simple_leaves, $value['leave_date']);
                    }
                }

            }
            $total_leave_count = $simple_leave_count + ($sandwitch_leave_count * 4);
            return $total_leave_count;
        }


        $counter = 0;
        foreach ($leavedata as $key => $val) {
            if ($val['shift'] == 'full') {
                $counter += $val['count'];
            } elseif ($val['shift'] == 'half') {
                $counter += ($val['count']) / 2;
            } elseif ($val['shift'] == 'specific') {
                $counter += ($val['count']) / 4;
            }

        }
        return $counter;
    }

    public static function leaveValue($value)
    {
        if ($value['shift'] == 'full') {
            return 1;

        } elseif ($value['shift'] == 'half') {
            return 0.5;

        } elseif ($value['shift'] == 'specific') {
            return 0.25;
        }
    }

    public static function firstInLastOutDateWise($attendance, $ouAtt, $start_date, $end_date, $user_id, $in_after_date, $out_after_date)
    {


        $Holidays = Holidays::holidaysBetween($start_date, $end_date);

        $holiday_dates = array();
        foreach ($Holidays as $key => $val) {
            $resp = Holidays::generateDateRange($val->holiday_date, $val->holiday_date_to);
            foreach ($resp as $k => $v) {
                array_push($holiday_dates, $v);
            }

        }
        $holiday_dates = array_unique($holiday_dates);
        $in_by_date = array();
        $out_by_date = array();
        $in_out_array = array();
        $normal_in_out_array = array();

        $iter_date = '';
        $leave_dates = array();
        $temp_date_array = array();

        $employee_Data = Employees::where('user_id', $user_id)->first();
        $emp_shift = $employee_Data->shift_id;
        // dd($emp_shift);
        $shift = WorkShifts::where('id', $emp_shift)->first();

        $work_weeks = WorkWeeks::where('shift_id', $emp_shift)->get();
        $work_weeks = json_decode(json_encode($work_weeks), true);


        $min_hours_short = $shift->min_hours_short;
        $min_hours_half = $shift->min_hours_half;
        $min_hours_full = $shift->min_hours_full;
        $grace_come_late = $shift->grace_come_late;
        $grace_leave_early = $shift->grace_leave_early;

        $short_days = 0;
        $half_days = 0;
        $full_off_days = 0;
        $normal_days = 0;

        $after_short_punch = 0;
        $after_half_punch = 0;
        $after_full_punch = 0;

        $shift_start_time = $shift->shift_start_time;
        $shift_end_time = $shift->shift_end_time;
        $working_hours_per_day = $shift->working_hours_per_day;
        $break_hours_per_day = $shift->break_hours_per_day;

        $short_punch = $shift->short_punch;
        $half_punch = $shift->half_punch;
        $full_punch = $shift->full_punch;
        $abnormal_in_by_date = array();
        $abnormal_out_by_date = array();
        $abnormal_original_out_by_date = array();
        // check for abnormal shift
        $abnormal_all_in_by_date = array();
        $abnormal_all_out_by_date = array();
        $abnormal_in_out_array = array();
        if (strtotime($shift_start_time) > strtotime($shift_end_time)) {
            // Abnormal Shift Special case. Get attendance of 1st date of next month

            $special_date = date('Y-m-d', strtotime('1 day', strtotime($end_date)));

            $specialinAtten = EmployeeAttendance::InofSingleDate($special_date, $user_id);

            $specialoutAtten = EmployeeAttendance::OutofSingleDate($special_date, $user_id);


            if (count($specialinAtten) > 0)
                $attendance->push($specialinAtten->first());

            if (count($specialoutAtten) > 0)
                $ouAtt->push($specialoutAtten->first());

            foreach ($attendance as $key => $value) {

                $curr_date = explode(' ', $value->date_time)[0];

                if ($iter_date == '') {

                    $iter_date = $curr_date;
                }
                // date changed
                if ($curr_date != $iter_date) {
                    $abnormal_all_in_by_date[$iter_date] = $temp_date_array;
                    $temp_date_array = array();
                    $iter_date = $curr_date;

                }

                array_push($temp_date_array, explode(' ', $value->date_time)[1]);
            }

            $abnormal_all_in_by_date[$iter_date] = $temp_date_array;


            foreach ($abnormal_all_in_by_date as $date => $att) {

                $date_att = array();

                foreach ($att as $attVal) {
                    array_push($date_att, strtotime($attVal));
                }

                // select the nearest time after out
                sort($date_att);

                foreach ($date_att as $timeVal) {
                    // value should be greater than last out
                    if ($timeVal > strtotime($shift_end_time)) {

                        $abnormal_in_by_date[$date] = gmdate('g:i a', $timeVal);
                        break;
                    }
                }

            }


            foreach ($ouAtt as $key => $value) {

                $curr_date = explode(' ', $value->date_time)[0];

                if ($iter_date == '') {

                    $iter_date = $curr_date;
                }
                // date changed
                if ($curr_date != $iter_date) {
                    $abnormal_all_out_by_date[$iter_date] = $temp_date_array;
                    $temp_date_array = array();
                    $iter_date = $curr_date;

                }

                array_push($temp_date_array, explode(' ', $value->date_time)[1]);
            }

            $abnormal_all_out_by_date[$iter_date] = $temp_date_array;
            //dd($abnormal_all_out_by_date);
            foreach ($abnormal_all_out_by_date as $date => $att) {

                $date_att = array();

                foreach ($att as $attVal) {
                    array_push($date_att, strtotime($attVal));
                }

                // select the nearest time before in

                rsort($date_att);

                $prev_date = date('Y-m-d', strtotime('-1 day', strtotime($date)));
                //dd($date_att);
                foreach ($date_att as $timeVal) {
                    // value should be greater than last out

                    if ($timeVal < strtotime($shift_start_time)) {

                        $abnormal_original_out_by_date[$date] = gmdate('g:i a', $timeVal);
                        $abnormal_out_by_date[$prev_date] = gmdate('g:i a', $timeVal);

                        break;
                    } else {

                    }

                    // if out is not found in next date get from previous


                    //                    if ( isset($abnormal_in_by_date[$prev_date] ) ) {
                    if (isset($abnormal_in_by_date[$date])) {

                        if (!isset($abnormal_out_by_date[$date])) {

                            $in_last_date_integers = array();
                            $in_last_date = $abnormal_all_out_by_date[$date];
                            foreach ($in_last_date as $inlastVal) {
                                array_push($in_last_date_integers, strtotime($inlastVal));
                            }
                            sort($in_last_date_integers);
                            if (max($in_last_date_integers) > strtotime($shift_start_time)) {

                                $abnormal_out_by_date[$date] = gmdate('g:i a', max($in_last_date_integers));
                            } else {

                            }

                        }
                    }
                }
            }

            // Now check if In is not found in current date find first in next date from this array $abnormal_all_in_by_date

            foreach ($abnormal_all_out_by_date as $key => $val) {

                if (!isset($abnormal_in_by_date[$key])) {
                    // check if it exist all in all in by date before out time
                    if (isset($abnormal_all_in_by_date[$key])) {

                        $in_first_date_integers = array();
                        $in_first_date = $abnormal_all_in_by_date[$key];
                        foreach ($in_first_date as $inlastVal) {
                            array_push($in_first_date_integers, strtotime($inlastVal));
                        }
                        if (count($in_first_date_integers) > 0 && min($in_first_date_integers) < strtotime($shift_end_time)) {

                            $prev_date = date('Y-m-d', strtotime('-1 day', strtotime($key)));
                            $abnormal_in_by_date[$prev_date] = gmdate('g:i a', min($in_first_date_integers));
                        } else {
                            //do nothing
                        }


                    }

                }
            }

            //            dd('Done' , $abnormal_all_out_by_date, $abnormal_all_in_by_date, $abnormal_out_by_date,$abnormal_in_by_date);


            $begin = new DateTime($start_date);
            $end = new DateTime($end_date);
            $end = $end->modify('+1 day');
            $interval = DateInterval::createFromDateString('1 day');

            $period = new DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {

                $abnormal_in_out_array[$dt->format("Y-m-d")] = array(
                    'i' => isset($abnormal_in_by_date[$dt->format("Y-m-d")]) ? $abnormal_in_by_date[$dt->format("Y-m-d")] : '-',
                    'o' => isset($abnormal_out_by_date[$dt->format("Y-m-d")]) ? $abnormal_out_by_date[$dt->format("Y-m-d")] : '-',
                    'day' => substr($dt->format("l"), 0, 3),
                    'extras' => '',
                );
            }


            foreach ($abnormal_in_out_array as $key => $val) {

                if (isset($val['i']) && isset($val['o'])) {

                    if ($val['i'] != '-' && $val['o'] != '-') {

                        $in_time_float = strtotime($val['i']);
                        $out_time_float = strtotime($val['o']);

                        $working_hours = self::working_hours($val['i'], $val['o'], $break_hours_per_day);
                        //                        echo '<br>working_hours : '. $working_hours;
                    } else {

                    }
                }
            }

            //            dd('Abnormal shift',$abnormal_in_out_array,$abnormal_out_by_date ,$abnormal_all_out_by_date);
            $in_out_array = $abnormal_in_out_array;

        }

        //normal shifts starts from here
        else {

            foreach ($attendance as $key => $value) {

                $curr_date = explode(' ', $value->date_time)[0];
                if ($iter_date == '') {
                    $iter_date = $curr_date;

                }

                // date changed
                if ($curr_date != $iter_date) {
                    $in_by_date[$iter_date] = isset($temp_date_array[0]) ? $temp_date_array[0] : '-';
                    $temp_date_array = array();
                    $iter_date = $curr_date;

                }
                array_push($temp_date_array, explode(' ', $value->date_time)[1]);
            }

            $iter_date = '';
            $temp_date_array = array();
            foreach ($ouAtt as $key => $value) {

                $curr_date = explode(' ', $value->date_time)[0];
                if ($iter_date == '') {

                    $iter_date = $curr_date;

                }
                // date changed
                if ($curr_date != $iter_date) {
                    $out_by_date[$iter_date] = isset($temp_date_array[0]) ? end($temp_date_array) : '-';
                    $temp_date_array = array();
                    $iter_date = $curr_date;

                }

                array_push($temp_date_array, explode(' ', $value->date_time)[1]);
            }

            $begin = new DateTime($start_date);
            $end = new DateTime($end_date);

            $end = $end->modify('+1 day');

            $interval = DateInterval::createFromDateString('1 day');

            $period = new DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {
                $normal_in_out_array[$dt->format("Y-m-d")] = array(
                    'i' => isset($in_after_date[$dt->format("Y-m-d")]) ? $in_after_date[$dt->format("Y-m-d")] : '-',
                    'o' => isset($out_after_date[$dt->format("Y-m-d")]) ? $out_after_date[$dt->format("Y-m-d")] : '-',
                    'day' => substr($dt->format("l"), 0, 3),
                    'extras' => '',
                );

            }
            //  dd($normal_in_out_array);
            $in_out_array = $normal_in_out_array;

        }
        //normal shifts ends from here

        // get taken leaves for employee in date between
        $leaves = Leaves::where(['employee_id' => $user_id, 'leave_status_id' => 5])->whereBetween('leave_date', [$start_date, $end_date])->get();


        foreach ($leaves as $k => $v) {
            $leave_dates[$v->leave_date] = $v;
        }


        $processed = 0;
        $marked = 0;
        $unmarked = 0;
        $in_time_float_sum = 0;
        $out_time_float_sum = 0;
        $daily_sum = 0;
        $overtime = 0;
        $approved_overtime = 0;
        $test_array = $in_out_array;
        foreach ($in_out_array as $key => $val) {
            $processed++;

            if ($val['i'] != '-' && $val['o'] != '-') {
                $marked++;
                $in_out_array[$key]['extras'] = 'm';
                $in_time_float = strtotime($val['i']);
                $out_time_float = strtotime($val['o']);
                $in_time_float_sum += $in_time_float;
                $out_time_float_sum += $out_time_float;
                $working_hours = self::working_hours($val['i'], $val['o'], $break_hours_per_day);
                $daily_sum += $working_hours;
                $in_out_array[$key]['hours'] = round($working_hours, 2);
                if ($working_hours > $working_hours_per_day) {
                    $overtime += $working_hours - $working_hours_per_day;
                }
                // arrived at normal time

                // we can +add+ grace time over here if we want to
                // if( strtotime($val['i']) <= strtotime($shift_start_time) ){
                //     // dd($shift_start_time);
                //     // now check for working hours
                //     if( $working_hours < $min_hours_full && $working_hours >= $min_hours_short ){
                //         $short_days++;
                //         $in_out_array[$key]['extras'] = 'SD';
                //     }
                //     elseif( $working_hours < $min_hours_short && $working_hours >= $min_hours_half){
                //         $half_days++;
                //         $in_out_array[$key]['extras'] = 'HD';
                //     }
                //     elseif( $working_hours < $min_hours_half ){
                //         $full_off_days ++;
                //         $in_out_array[$key]['extras'] = 'FD';
                //     }
                //     $normal_days++;
                // }else
                // dd($val['i']);
                if (strtotime($val['i']) > strtotime($short_punch) and strtotime($val['i']) < strtotime($half_punch)) {
                    //   dd(1);
                    $short_days++;
                    $in_out_array[$key]['extras'] = 'SD';
                    $after_short_punch++;
                    // if( $working_hours < $min_hours_full && $working_hours >= $min_hours_short){
                    //     $short_days++;
                    //     $in_out_array[$key]['extras'] = 'SD';
                    // }
                    // elseif( $working_hours < $min_hours_short && $working_hours >= $min_hours_half){
                    //     $half_days++;
                    //     $in_out_array[$key]['extras'] = 'HD';
                    // }
                    // elseif( $working_hours < $min_hours_half ){
                    //     $full_off_days ++;
                    //     $in_out_array[$key]['extras'] = 'FD';
                    // }

                } elseif (strtotime($val['i']) >= strtotime($half_punch) and strtotime($val['i']) < strtotime($full_punch)) {
                    // dd(12);
                    $half_days++;
                    $in_out_array[$key]['extras'] = 'HD';
                    $after_half_punch++;
                    // if( $working_hours < $min_hours_short && $working_hours >= $min_hours_half){
                    //     $half_days++;
                    //     $in_out_array[$key]['extras'] = 'HD';
                    // }
                    // elseif( $working_hours < $min_hours_half ){
                    //     $full_off_days ++;
                    //     $in_out_array[$key]['extras'] = 'FD';
                    // }

                } elseif (strtotime($val['i']) >= strtotime($full_punch)) {
                    // dd(1);
                    $full_off_days++;
                    $in_out_array[$key]['extras'] = 'FD';
                    $after_full_punch++;
                } else {
                    $normal_days++;
                }

                // if( strtotime($val['i']) > strtotime($short_punch) && strtotime($val['i']) <= strtotime($half_punch )){
                //     $after_short_punch++;
                // }elseif ( strtotime($val['i']) > strtotime($half_punch) && strtotime($val['i']) <= strtotime($full_punch )){
                //     $after_half_punch ++;
                // }elseif (strtotime($val['i']) > strtotime($full_punch )){
                //     $after_full_punch ++;
                // }

            } else {
                $in_out_array[$key]['extras'] = 'um';
                $unmarked++;
            }
        }

        // calculate working days shift wise

        $off_days_of_week = array();
        foreach ($work_weeks as $week_id => $week) {
            $week_days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
            $temp_off_days = array();

            // 1- Get off days of week
            foreach ($week as $day => $day_val) {
                if (in_array($day, $week_days) && $day_val != 0) {
                    array_push($temp_off_days, $day);
                }
            }

            $off_days_of_week[$week_id] = $temp_off_days;
        }



        $total_absents = 0;
        $weekly_off_dates = array();
        $absent_dates = array();
        foreach ($in_out_array as $key => $val) {

            // calculate only from unmarked
            if ($val['extras'] == 'um') {

                $week_no = self::weekOfMonth(strtotime($key));
                //                $week_no = self::weekOfMonth(strtotime('2019-09-01'));

                // map 6th week to fifth as well
                $week_no = $week_no == 6 ? 5 : 5;
                if ($work_weeks[$week_no - 1][strtolower($val['day'])] == '0') {
                    // now check if user has any taken leave on this date
                    if (isset($leave_dates[$key]) && $leave_dates[$key]->shift == 'full') {
                        $in_out_array[$key]['extras'] = \Config::get('hrm.leave_types_abbreviations.' . $leave_dates[$key]->leave_type_id);

                    } else {
                        if (in_array($key, $holiday_dates)) {
                            $in_out_array[$key]['extras'] = 'H';
                        } else {

                            $in_out_array[$key]['extras'] = 'A';
                            array_push($absent_dates, $key);
                            $total_absents++;
                        }


                    }

                } else {
                    $in_out_array[$key]['extras'] = 'OFF';
                    $weekly_off_dates[$week_no - 1][$key] = $key;
                }
            }
        }

        $sandwich_count = 0;
        $sandwich_leaves = 0;
        foreach ($weekly_off_dates as $key => $value) {
            $week_offs = array_keys($value);
            $first_date = current($week_offs);
            $last_date = end($week_offs);
            // check if the next of prev date is not in the next or prev month
            // then dont calculate sandwich
            // algorithem works if off days are consective

            $prev_date = date('Y-m-d', strtotime($first_date . ' -1 day'));
            // $next_date = date('Y-m-d', strtotime($last_date . ' +1 day'));
            $next_date = date('Y-m-d', strtotime($last_date));
            $month = date("m", strtotime($first_date));

            if (date("m", strtotime($first_date)) == date("m", strtotime($prev_date)) && date("m", strtotime($first_date)) == date("m", strtotime($next_date))) {
                //check for sandwich here finally
                if ($in_out_array[$prev_date]['extras'] == 'A' && $in_out_array[$next_date]['extras'] == 'A') {
                    $sandwich_count++;
                    $sandwich_leaves += count($value) + 2;

                }
            }
            //                dd($prev_date,$first_date,$last_date, $next_date);
//                dd( current(array_keys(current($weekly_off_dates))), $user_id, $absent_dates , $off_days_of_week);
        }


        $return_array['working_hours_per_day'] = $working_hours_per_day;
        $return_array['average_time_in'] = $marked > 0 ? gmdate('g:i a', intval($in_time_float_sum / $marked)) : '-';
        $return_array['average_time_out'] = $marked > 0 ? gmdate('g:i a', intval($out_time_float_sum / $marked)) : '-';
        //        $return_array['average_working_hours'] = self::working_hours( $return_array['average_time_in'], $return_array['average_time_out'] ) ;
        $return_array['average_working_hours'] = $marked > 0 ? number_format((float) ($daily_sum / $marked), 2, '.', '') : '-';
        $return_array['daily_average_working_hours'] = $marked > 0 ? number_format((float) ($daily_sum / $marked), 2, '.', '') : '-';

        $return_array['after_short_punch'] = $after_short_punch;
        $return_array['after_half_punch'] = $after_half_punch;
        $return_array['after_full_punch'] = $after_full_punch;
        $return_array['overtime'] = $overtime;
        $return_array['short_days'] = $short_days;
        $return_array['half_days'] = $half_days;
        $return_array['full_off_days'] = $full_off_days;
        $return_array['unmarked'] = $unmarked;
        $return_array['marked'] = $marked;
        $return_array['processed'] = $processed;
        $return_array['total_absents'] = $total_absents + $sandwich_leaves - ($sandwich_count * 2);
        $return_array['sandwich_count'] = $sandwich_count;
        $return_array['sandwich_leaves'] = $sandwich_leaves;
        $return_array['in_out_array'] = $in_out_array;
        // implement applied approved half and short leaves in the the deductions

        // dd($return_array['att_deduction']);
        $return_array['leave_without_pay'] = self::func_leave_without_pay($employee_Data, $start_date, $end_date);
        $return_array['employee_Data'] = $employee_Data;
        $return_array['att_deduction'] = self::calculate_deductions($return_array);
        // calculate fines
        $return_array['employ_finess'] = self::calculateFiness($user_id, $start_date, $end_date);

        // $return_array['full_off_days'] =  $full_off_days + $return_array['att_deduction'];
        return $return_array;


    }
    public static function calculateFiness($user_id, $start_date, $end_date)
    {

        $fine_amount = ManageFiness::where('employee_user_id', $user_id)->where('status', 1)->whereBetween('applied_date', [$start_date, $end_date])->sum('fine_amount');
        return $fine_amount;
    }

    public static function working_hours($in, $out, $break = 0)
    {
        $time1 = strtotime($in);
        $time2 = strtotime($out);

        $difference = ($time2 - $time1) / 3600;
        if ($difference < 0) {
            //            dd($in,$out);
            $difference = 24 + $difference;
        }
        if ($difference < $break) {
            return $difference;
        }
        return $difference - $break;
    }


    public static function weekOfMonth($date)
    {
        //Get the first day of the month.
        $firstOfMonth = strtotime(date("Y-m-01", $date));
        //Apply above formula.
        return intval(date("W", $date)) - intval(date("W", $firstOfMonth)) + 1;
    }


    public static function calculate_deductions($data)
    {
        //Get the first day of the month.
        $rule = LeaveRules::first();
        $full_off = 0;
        // dd($data);
        $employee_salary = (floor($data['employee_Data']['gross_salary']));
        $per_day = (floor(($employee_salary * 12) / 365));
        $half_day_salary = (floor($per_day / 2));

        // calculate per day salary;

        $allowed_short = $rule->allowed_short_days;
        $short_days_equals_half = $rule->short_days_equals_half;
        $short_days_equals_full = $rule->short_days_equals_full;
        $short_days = $data['short_days'];
        if ($short_days < $short_days_equals_half) {
            $after_deduction['full_off'] = $data['full_off_days'];
            $after_deduction['half_days'] = $data['half_days'];
            $after_deduction['deduction'] = ($after_deduction['half_days'] * $half_day_salary) + ($after_deduction['full_off'] * $per_day);
            return $after_deduction;

        } elseif ($short_days < $short_days_equals_full) {

            $half_days = floor($short_days / $short_days_equals_half);
            $after_deduction['full_off'] = $full_off + $data['full_off_days'];
            $after_deduction['half_days'] = $half_days + $data['half_days'];
            $after_deduction['deduction'] = ($after_deduction['half_days'] * $half_day_salary) + ($after_deduction['full_off'] * $per_day);
            return $after_deduction;
            return (floor($short_days / $short_days_equals_half)) / 2;

        } elseif ($short_days >= $short_days_equals_half) {
            $full_off = floor($short_days / $short_days_equals_full);
            $remainder = $short_days % $short_days_equals_full;
            $half_days = 0;

            if ($remainder >= $short_days_equals_half) {
                $half_days = floor($remainder / $short_days_equals_half);
            }
            $after_deduction['full_off'] = $full_off + $data['full_off_days'];
            $after_deduction['half_days'] = $half_days + $data['half_days'];
            $after_deduction['deduction'] = ($after_deduction['half_days'] * $half_day_salary) + ($after_deduction['full_off'] * $per_day);
            return $after_deduction;
            // return ( $full_off + ($half_days)/2 );
        }


    }


    static public function func_leave_without_pay($user, $start_date, $till_date)
    {

        $total = QuottaSettings::pluck('permitted_days', 'id');
        $confirm_year = date('Y', strtotime($user->confirmation_date));
        $month_year = date('Y', strtotime($till_date));
        // set start date 1st jan

        if ($confirm_year < $month_year) {
            $quota_start_date = $month_year . '-01-01';
        } else {
            $quota_start_date = $user->confirmation_date;
        }

        $start_month = date('m', strtotime($quota_start_date));

        $end_month = date('m', strtotime($till_date));

        $months = $end_month - $start_month + 1;

        $quota_days = array();

        foreach ($total as $key => $val) {

            $quota_days[$key] = ceil(($val / 12) * $months);
        }
        $leaves = Leaves::where(['employee_id' => $user->user_id, 'leave_status_id' => 5])->whereBetween('leave_date', [$quota_start_date, $till_date])->get();
        $current_month = Leaves::where(['employee_id' => $user->user_id, 'leave_status_id' => 5])->whereBetween('leave_date', [$start_date, $till_date])->get();

        $leave_type_wise_detail = array();

        foreach ($leaves as $key => $val) {
            if (isset($leave_type_wise_detail[$val->leave_type_id])) {
                $leave_type_wise_detail[$val->leave_type_id] = $leave_type_wise_detail[$val->leave_type_id] + self::leaveValue($val);
            } else {
                $leave_type_wise_detail[$val->leave_type_id] = self::leaveValue($val);
            }
        }

        // current month approved leaves
        $current_month_leave_type_wise_detail = array();
        foreach ($current_month as $key => $val) {
            if (isset($current_month_leave_type_wise_detail[$val->leave_type_id])) {
                $current_month_leave_type_wise_detail[$val->leave_type_id] = $current_month_leave_type_wise_detail[$val->leave_type_id] + self::leaveValue($val);
            } else {
                $current_month_leave_type_wise_detail[$val->leave_type_id] = self::leaveValue($val);
            }
        }

        foreach ($quota_days as $key => $val) {
            if (!isset($current_month_leave_type_wise_detail[$key])) {
                $current_month_leave_type_wise_detail[$key] = 0;
            }
        }

        // now calculate leave without pay
        $leave_without_pay = array();
        foreach ($quota_days as $key => $val) {
            if (isset($leave_type_wise_detail[$key])) {
                if ($leave_type_wise_detail[$key] - $val > 0) {
                    if ($leave_type_wise_detail[$key] - $val > $current_month_leave_type_wise_detail[$key]) {
                        $leave_without_pay[$key] = $current_month_leave_type_wise_detail[$key];
                    } else {
                        $leave_without_pay[$key] = $leave_type_wise_detail[$key] - $val;
                    }
                } else {
                    $leave_without_pay[$key] = 0;
                }
            } else {
                $leave_without_pay[$key] = 0;
            }
        }

        $total_leave_without_pay = 0;
        foreach ($leave_without_pay as $key => $val) {
            $total_leave_without_pay += $val;
        }
        return $total_leave_without_pay;
    }


    static public function calculate_payroll($post, $month, $year)
    {

        $user = $post['employee_Data'];

        $basic_salary = $post['employee_Data']['basic_salary'];
        $accommodation_allowance = $post['employee_Data']['accommodation_allowance'];
        $house_rent_allowance = $post['employee_Data']['house_rent_allowance'];
        $conveyance_allowance = $post['employee_Data']['conveyance_allowance'];
        $utilities_allowance = $post['employee_Data']['utilities_allowance'];
        $fuel_allowance = $post['employee_Data']['fuel_allowance'];
        $mobile_allowance = $post['employee_Data']['mobile_allowance'];
        $gross_salary = $post['employee_Data']['gross_salary'];
        $deduction = $post['att_deduction']['deduction'];
        $overtime = $post['overtime'] * ((($basic_salary * 12) / 365) / 8);
        if ($post['total_absents'] > 1) {
            $total_absents = ($post['total_absents'] - 1) * (($basic_salary * 12) / 365);
        } else {
            $total_absents = $post['total_absents'] * (($basic_salary * 12) / 365);
        }
        $eobi = Config::get('hrm.eobi');
        $user_id = $post['employee_Data']['user_id'];
        $bank_id = $post['employee_Data']['bank_id'];
        $account_number = $post['employee_Data']['account_number'];
        $helping_hand = 0;
        $tax_amount = TaxSettings::calculateTax($basic_salary);

        $Advance = Advances::getInstallmentByEmployeeId($user_id);
        $employ_finess = $post['employ_finess'];
        $total_deducions_before_tax = $deduction + $total_absents + $eobi + $Advance + $tax_amount + $employ_finess;

        // $total_renum = $gross_salary + $conveyance_allowance + $accommodation_allowance + $fuel_allowance + $mobile_allowance + $overtime;
        $total_renum = $gross_salary + $overtime;
        $taxable_salary = $total_renum + $overtime - $total_deducions_before_tax;
        $total_after_tax = $taxable_salary - $tax_amount;
        $net_payable = $total_renum - $total_deducions_before_tax;

        $payroll_array['user_id'] = $user_id;
        $payroll_array['bank_id'] = $bank_id;
        $payroll_array['account_number'] = $bank_id;
        $payroll_array['basic_salary'] = $basic_salary;
        $payroll_array['basic_salary_arrear'] = 0;
        $payroll_array['house_rent_allowance'] = $house_rent_allowance;
        $payroll_array['house_rent_allowance_arrear'] = 0;
        $payroll_array['utilities_allowance'] = $utilities_allowance;
        $payroll_array['utilities_allowance_arrear'] = 0;
        $payroll_array['overtime'] = $overtime;
        $payroll_array['gross_salary'] = $gross_salary;
        $payroll_array['conveyance_allowance'] = $conveyance_allowance;
        $payroll_array['conveyance_allowance_arrear'] = 0;
        $payroll_array['accommodation_allowance'] = $accommodation_allowance;
        $payroll_array['accommodation_allowance_arrear'] = 0;
        $payroll_array['fuel_allowance'] = $fuel_allowance;
        $payroll_array['fuel_allowance_arrear'] = 0;
        $payroll_array['mobile_allowance'] = $mobile_allowance;
        $payroll_array['mobile_allowance_arrear'] = 0;
        $payroll_array['total_additional_allowances'] = 0;
        $payroll_array['overtime_arrear'] = 0;
        $payroll_array['late_arrivals'] = $deduction;
        // $payroll_array['leave_without_pay'] = $lwop_deduction;
        $payroll_array['absent_deduction'] = $total_absents;
        $payroll_array['advance'] = $Advance;
        $payroll_array['helping_hands'] = $helping_hand;
        $payroll_array['income_tax'] = $tax_amount;
        $payroll_array['taxable_income'] = $taxable_salary;
        $payroll_array['total_deductions'] = $total_deducions_before_tax;
        $payroll_array['net_payable'] = $net_payable;
        $payroll_array['eobi'] = $eobi;
        $payroll_array['paid_amount'] = $net_payable;
        $payroll_array['pending_amount'] = 0;
        $payroll_array['payment_method'] = 1;
        $payroll_array['employ_finess'] = $employ_finess;
        $payroll_array['payment_status'] = 'Paid';
        $payroll_array['comments'] = 'N/A';
        $payroll_array['status'] = 1;
        $payroll_array['created_by'] = 1;
        $payroll_array['updated_by'] = 1;

        return $payroll_array;


    }

}
