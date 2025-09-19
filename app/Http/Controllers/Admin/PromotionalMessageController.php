<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Course;
use App\Models\Admin\StudentDataBank;
use App\Models\Admin\StudentDataBankCourse;
use App\Models\Student\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PromotionalMessageController extends Controller
{
    public function promotional_messages_index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $regular_students = Students::select('id', 'name', 'mobile_no', 'student_data_bank_id')->where('student_data_bank_id', '!=', null)->pluck('student_data_bank_id');
        $students = StudentDataBank::select('id', 'name', 'mobile_no')->whereNotIn('id', $regular_students)->get();

        $courses = Course::get();
        return view('admin.promotional_messages.index', compact('students', 'courses'));
    }

    public function promotional_messages_send(Request $request)
    {
if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        dd($request);
        $students = StudentDataBank::select('id', 'name', 'mobile_no')->whereIn('id', $request->students)->get();


        foreach ($students as $student) {

            $APIKey = 'e8785e57ef6d6ebe153f093b0c527b86';
            $receiver = $student->mobile_no;
            $sender = '8583';
            $textmessage = $request->message;
            $url = "https://api.veevotech.com/sendsms?hash=" . $APIKey . "&receivenum=" . $receiver . "&sendernum=" . urlencode($sender) . "&textmessage=" . urlencode($textmessage);
            $ch = curl_init();
            $timeout = 30;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $response = curl_exec($ch);
            curl_close($ch);


            //            echo $response;

        }
        return redirect()->route('admin.promotional_messages');

    }

    public function get_student_with_course(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $regular_students = Students::select('id', 'name', 'mobile_no', 'student_data_bank_id')->where('student_data_bank_id', '!=', null)->pluck('student_data_bank_id');
        $student_courses = StudentDataBankCourse::where('course_id', $request->course_id)->pluck('student_data_bank_id');


        $students = StudentDataBank::
            select('id', 'name', 'mobile_no')
            ->whereIn('id', $student_courses)
            ->whereNotIn('id', $regular_students)
            ->get();
        foreach ($students as $student) {

            if (isset($student->mobile_no)) {


                $APIKey = 'e8785e57ef6d6ebe153f093b0c527b86';
                $receiver = $student->mobile_no;
                $sender = '8583';
                $textmessage = $request->message;
                $url = "https://api.veevotech.com/sendsms?hash=" . $APIKey . "&receivenum=" . $receiver . "&sendernum=" . urlencode($sender) . "&textmessage=" . urlencode($textmessage);
                $ch = curl_init();
                $timeout = 30;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $response = curl_exec($ch);
                curl_close($ch);
            }

            //            echo $response;

        }
        //        dd($students);

        return array($students);

    }

    public function get_student(Request $request)
    {
if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $regular_students = Students::select('id', 'name', 'mobile_no', 'student_data_bank_id')->where('student_data_bank_id', '!=', null)->pluck('student_data_bank_id');
        $students = StudentDataBank::
            select('id', 'name', 'mobile_no')
            ->whereNotIn('id', $regular_students)
            ->get();
        foreach ($students as $student) {

            if (isset($student->mobile_no)) {
                $APIKey = 'e8785e57ef6d6ebe153f093b0c527b86';
                $receiver = $student->mobile_no;
                $sender = '8583';
                $textmessage = $request->message;
                $url = "https://api.veevotech.com/sendsms?hash=" . $APIKey . "&receivenum=" . $receiver . "&sendernum=" . urlencode($sender) . "&textmessage=" . urlencode($textmessage);
                $ch = curl_init();
                $timeout = 30;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $response = curl_exec($ch);
                curl_close($ch);
            }

            //            echo $response;

        }
        return array($students);

    }
}

