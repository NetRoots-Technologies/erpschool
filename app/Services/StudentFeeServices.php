<?php

namespace App\Services;

use App\Models\Admin\AssignTool;
use App\Models\Admin\City;
use App\Models\Admin\Ledgers;
use App\Models\Admin\Session;
use App\Models\Admin\StudentDataBank;
use App\Models\Fee\PaidStudentFee;
use App\Models\Fee\StudentFee;
use App\Models\Student\StudentDetail;
use Carbon\Traits\Date;
use Config;
use App\Helper\Helpers;
use App\Models\Student\Students;
use App\Models\Student\StudentCourse;
use App\Models\Admin\Course;
use App\Models\User;
use http\Env\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DataTables;
use Spatie\Permission\Models\Role;

use Event;
use App\Events\SendMail;

class StudentFeeServices
{

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    public function apiindex()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Students::all();

    }

    public function get_fee($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $amount = Course::where('id', $id)->value('fee');
        return $amount;

    }

    public function student_paid_fee_detail($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $student_fee = StudentFee::find($id);


        $data['session'] = Session::where('course_id', $student_fee->course_id)->get();
        $data['student_fee'] = StudentFee::where('id', $id)->with('student', 'session', 'course')->first();
        return $data;

    }

    //    public function fee_paid_detail_edit($id)
//    {
//
//        $data['student_fee'] = StudentFee::where('id', $id)->with('student', 'session', 'course')->first();
//        return $data;
//    }

    public function fee_paid_detail_edit_post($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $student_fee = StudentFee::find($id);

        $student_session = Students::find($student_fee->student_id);

        $student_session->session_id = $request->session_id;
        $student_fee->session_id = $request->session_id;


        $student_fee->save();
        $student_session->save();
    }

    public function discount_on_instalment_post($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $student_fee = StudentFee::find($id);
        if ($student_fee) {

            $group_id = Config::get('account_constants.Income_Group_Fee');
            $parent_type = $student_fee->student_id;

            $income_ledger = Ledgers::where('parent_type', $parent_type)->where('group_id', $group_id)->first();

            $group_id_recivable = Config::get('account_constants.Current_Asset_Fee');
            $reciveable_ledger = Ledgers::where('parent_type', $parent_type)->where('group_id', $group_id_recivable)->first();

            $entry['entry_type_id'] = Config::get('voucher_types.Fee_Receive_Voucher');
            $entry['narration'] = 'discount to student';

            $Entry_Create = Helpers::create_entry($entry);


            $entry_item['entry_id'] = $Entry_Create;
            $entry_item['amount'] = $request->discount;
            $entry_item['narration'] = "   Student Fee discount ";
            $entry_item['dc'] = 'c';
            $entry_item['ledger_id'] = $reciveable_ledger->id;
            $Entry_Item_create = Helpers::create_entry_item($entry_item);
            $entry_item['dc'] = 'd';
            $entry_item['ledger_id'] = $income_ledger->id;
            $Entry_Item_create = Helpers::create_entry_item($entry_item);

            $previous_discount = $student_fee->discount_amount;
            $remaining_amount = $student_fee->remaining_amount;
            $student_fee->discount_amount = $previous_discount + $request->discount;
            $student_fee->remaining_amount = $remaining_amount - $request->discount;
            $student_fee->save();
            $todayDate = Carbon::now()->format('Y-m-d');

            $PaidStudentFee = new PaidStudentFee();
            $PaidStudentFee->student_fee_id = $student_fee->id;
            $PaidStudentFee->student_id = $student_fee->student_id;
            $PaidStudentFee->source = 'discount';
            $PaidStudentFee->start_date = $todayDate;
            $PaidStudentFee->paid_date = $todayDate;

            $PaidStudentFee->paid_status = 'done';
            $PaidStudentFee->type = 'discount';
            $PaidStudentFee->installement_amount = $request->discount;
            $PaidStudentFee->save();


        }
    }

    public function make_defaulter_post($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $student_fee = StudentFee::find($id);
        if ($student_fee) {
            if ($student_fee->defaulter == 0) {
                $group_id = Config::get('account_constants.Income_Group_Fee');
                $parent_type = $student_fee->student_id;

                $income_ledger = Ledgers::where('parent_type', $parent_type)->where('group_id', $group_id)->first();

                $group_id_recivable = Config::get('account_constants.Current_Asset_Fee');
                $reciveable_ledger = Ledgers::where('parent_type', $parent_type)->where('group_id', $group_id_recivable)->first();

                $entry['entry_type_id'] = Config::get('voucher_types.Fee_Receive_Voucher');
                $entry['narration'] = 'defaulter to student';

                $Entry_Create = Helpers::create_entry($entry);


                $entry_item['entry_id'] = $Entry_Create;
                $entry_item['amount'] = $student_fee->remaining_amount;
                $entry_item['narration'] = "   Student Fee defaulter ";
                $entry_item['dc'] = 'c';
                $entry_item['ledger_id'] = $reciveable_ledger->id;
                $Entry_Item_create = Helpers::create_entry_item($entry_item);
                $entry_item['dc'] = 'd';
                $entry_item['ledger_id'] = $income_ledger->id;
                $Entry_Item_create = Helpers::create_entry_item($entry_item);

                $student_fee->defaulter = 1;
                $student_fee->save();
            }

        }

    }

    public function make_defaulter_reactive($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $student_fee = StudentFee::find($id);
        if ($student_fee) {
            if ($student_fee->defaulter == 1) {
                $group_id = Config::get('account_constants.Income_Group_Fee');
                $parent_type = $student_fee->student_id;

                $income_ledger = Ledgers::where('parent_type', $parent_type)->where('group_id', $group_id)->first();

                $group_id_recivable = Config::get('account_constants.Current_Asset_Fee');
                $reciveable_ledger = Ledgers::where('parent_type', $parent_type)->where('group_id', $group_id_recivable)->first();

                $entry['entry_type_id'] = Config::get('voucher_types.Fee_Receive_Voucher');
                $entry['narration'] = '  student reactivated';

                $Entry_Create = Helpers::create_entry($entry);


                $entry_item['entry_id'] = $Entry_Create;
                $entry_item['amount'] = $student_fee->remaining_amount;
                $entry_item['narration'] = "Student Fee defaulter reactive ";
                $entry_item['dc'] = 'c';
                $entry_item['ledger_id'] = $reciveable_ledger->id;
                $Entry_Item_create = Helpers::create_entry_item($entry_item);
                $entry_item['dc'] = 'd';
                $entry_item['ledger_id'] = $income_ledger->id;
                $Entry_Item_create = Helpers::create_entry_item($entry_item);
                $student_fee->defaulter = 0;
                $student_fee->save();
            }

        }

    }


    public function student_fee_paid($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Student_course_id = 0;

        $student_fee = PaidStudentFee::find($id);
        $student_fee_id = $student_fee->student_fee_id;

        //        $student_fee_id = PaidStudentFee::where('id', $id)->value('student_fee_id');
        $StudentFee = StudentFee::where('id', $student_fee_id)->first();

        //        dd($StudentFee);
        $student = Students::find($StudentFee->student_id);

        $student->status = 'active';
        $student->save();

        $course_name = 'N/A';
        $session_name = 'N/A';
        $session = Session::find($student->session_id);
        $Student_course_id = $session->course_id;
        $course = Course::find($Student_course_id);
        if ($course)
            $course_name = $course->name;
        if ($session)
            $session_name = $session->title;

        $subject = "";
        if ($student_fee->type == "advance") {
            $subject = "Student Advance";
        } else {
            $subject = "Student Installment";
        }

        $student_fee = PaidStudentFee::find($id);
        $data = [
            'name' => $student->name,
            'email' => $student->email,
            'password' => '12345678',
            'course_name' => $course_name,
            'advance' => $student_fee->installement_amount,
            'type' => $student_fee->type,
            'source' => $student_fee->source,
            'session_name' => $session_name,
            'subject' => $subject
        ];


        $StudentFee->remaining_amount = $StudentFee->remaining_amount - $request['paid_amount']['installement_amount'];
        $StudentFee->total_paid_fee = $StudentFee->student_fee - $StudentFee->remaining_amount;
        $StudentFee->tools_provided = $request->tools_provided;
        $StudentFee->save();

        //Entry Create
        $entry['entry_type_id'] = Config::get('voucher_types.Fee_Receive_Voucher');
        $entry['narration'] = $student->name . ' - ' . $student->email . ' - ' . $student_fee->type;
        //                dd($entry);
        $Entry_Create = Helpers::create_entry($entry);

        $group_id = Config::get('account_constants.Current_Asset_Fee');
        $parent_type = $student->id;
        $Current_assets_fee_ledger = Helpers::get_ledger($group_id, $parent_type);


        //                //Entry Item for Advance Student Fee
        $Current_Assets_Cash = Config::get('accounts_constants.Current_Assets_Cash');
        $Cash_in_Hand = Helpers::get_ledger($Current_Assets_Cash);
        if (!$Cash_in_Hand) {
            $Cash_in_Hand = Config::get('ledger_constants.Current_Assets_Cash_in_Hand');
        }

        $entry_item['entry_id'] = $Entry_Create;
        $entry_item['amount'] = $request['paid_amount']['installement_amount'];
        $entry_item['narration'] = "Advance Student Fee " . $student->name . ' - ' . $student->email;
        $entry_item['dc'] = 'd';
        $entry_item['ledger_id'] = $Cash_in_Hand;
        $Entry_Item_create = Helpers::create_entry_item($entry_item);

        if ($Current_assets_fee_ledger) {
            $entry_item['dc'] = 'c';
            $entry_item['ledger_id'] = $Current_assets_fee_ledger->id;
            $Entry_Item_create = Helpers::create_entry_item($entry_item);
        }

        $PaidStudentFee = PaidStudentFee::where('id', $id)->update([
            'paid_status' => 'paid',
            'paid_date' => date('Y-m-d')
        ]);
        try {
            //            event(new SendMail($student->id, $data));
        } catch (\Exception $e) {


        }

    }

    public function student_paid_fee($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $StudentFee = StudentFee::where('id', $id)->with('student', 'session', 'course')->first();
        $todayDate = Carbon::now()->format('Y-m-d');

        if ($StudentFee->remaining_amount > 0) {
            $PaidStudentFee = new PaidStudentFee();
            $PaidStudentFee->student_fee_id = $StudentFee->id;
            $PaidStudentFee->student_id = $StudentFee->student_id;
            $PaidStudentFee->source = $request->Payment_source;
            $PaidStudentFee->start_date = $todayDate;
            $PaidStudentFee->due_date = $request->due_date;
            $PaidStudentFee->paid_status = 'pending';
            $PaidStudentFee->type = 'installment';
            $PaidStudentFee->installement_amount = $request->paid_amount;
            $PaidStudentFee->save();
            //$StudentFee->remaining_amount = $StudentFee->remaining_amount - $request->paid_amount;
            //$StudentFee->save();
        }


    }

    public function get_data_student_paid_fee_detail($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = PaidStudentFee::where('student_fee_id', $id)->get();


        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (Gate::allows('student_fee-delete')) {
                    $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("admin.fee_paid_detail_delete", $row->id) . '"> ';
                    $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
                    $btn = $btn . method_field('DELETE') . '' . csrf_field();
                    $btn = $btn . ' </form>';

                }


                if ($row->paid_status == 'pending') {
                    $btn = $btn . '<a   data-route="' . route("admin.student_fee_paid", $row->id) . '"  data-id="' . $row->id . '"  class="btn btn-primary  btn-sm fee_paid mr-2"  data-fee_paid=\'' . $row . '\'>Pay</a>';
                    $btn = $btn . '<a href="' . route("admin.student_fee_voucher", $row->id) . '"  data-route="' . route("admin.student_fee_voucher", $row->id) . '"  data-id="' . $row->id . '"  class="btn btn-success  btn-sm fee_paid_voucher"  data-fee_paid_voucher=\'' . $row . '\'>Create Voucher</a>';

                    return $btn;
                } else {

                    $btn = $btn . '<a href="' . route("admin.student_fee_voucher", $row->id) . '"  data-route="' . route("admin.student_fee_voucher", $row->id) . '"  data-id="' . $row->id . '"  class="btn btn-success  btn-sm fee_paid_voucher"  data-fee_paid_voucher=\'' . $row . '\'>Create Voucher</a>';

                    return $btn;
                }


            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function get_data_student_fee_more_than_30k()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = StudentFee::with('course', 'student', 'session')->where('total_paid_fee', '>=', 30000)->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('paid_date', function ($row) {
                if (isset($row->paid_date)) {
                    $btn = $row->paid_date;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }
                return $btn;
            })->addColumn('session', function ($row) {
                if (isset($row->session)) {
                    $btn = $row->session->title;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }
                return $btn;
            })->addColumn('student_name', function ($row) {
                if (isset($row->student)) {
                    $btn = $row->student->name;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }

                return $btn;
            })->addColumn('student_status', function ($row) {
                if (isset($row->student)) {
                    $btn = $row->student->status;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }

                return $btn;
            })->addColumn('course_name', function ($row) {
                if (isset($row->course)) {
                    $btn = $row->course->name;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }

                return $btn;
            })
            ->addColumn('source', function ($row) {
                if (isset($row->source)) {
                    $btn = $row->source;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }

                return $btn;
            })->addColumn('date', function ($row) {
                if (isset($row->created_at)) {
                    $btn = date('Y-m-d', strtotime($row->created_at));

                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }

                return $btn;
            })
            ->rawColumns(['source', 'student_status', 'date', 'status', 'action', 'course_name', 'paid_date', 'session', 'student_name', 'total_paid_fee', 'tools_provided'])
            ->make(true);
    }


    public function get_sessions($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $html = "<option value=''>Select option</option>";
        $Session = Session::where('course_id', $id)->get();
        foreach ($Session as $item) {
            $html = $html . "<option value='" . $item->id . "'>" . $item->title . "</option>";
        }
        return $html;

    }


    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data['course'] = Course::all();
        $StudentFee = StudentFee::pluck('student_id');
        $data['databank'] = Students::whereNotIn('id', $StudentFee)->get();
        return $data;


    }

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Student_course_id = 0;

        $sturdent = Students::find($request->data_bank_id);

        foreach ($request->course_id as $key => $value) {
            $StudentCourse = new StudentCourse();
            $StudentCourse->course_id = $value;
            $Student_course_id = $value;
            $StudentCourse->student_id = $request->data_bank_id;
            $StudentCourse->save();
        }
        if ($request->Session) {
            $sturdent->session_id = $request->Session;
            $sturdent->save();
        }
        if ($request->advance > 0) {
            //            $sturdent->status = 'active';


            $student_user = User::where('email', $sturdent->email)->first();
            if (!$student_user) {
                $student_user = new User();
            }
            $student_user->email = $sturdent->email;
            $student_user->name = $sturdent->name;
            $student_user->password = Hash::make('12345678');
            $student_user->student_id = $sturdent->id;
            $student_user->role_id = Role::where('name', 'Student')->first()->id;

            $student_user->save();
            $sturdent->save();

            if ($student_user->save()) {
                $APIKey = 'e8785e57ef6d6ebe153f093b0c527b86';
                $receiver = $sturdent->mobile_no;
                $sender = '8583';
                $textmessage = 'You are a onez student now';

                $url = "https://api.veevotech.com/sendsms?hash=" . $APIKey . "&receivenum=" . $receiver . "&sendernum=" . urlencode($sender) . "&textmessage=" . urlencode($textmessage);

                #----CURL Request Start
                $ch = curl_init();
                $timeout = 30;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $response = curl_exec($ch);
                curl_close($ch);
                #----CURL Request End, Output Response
                echo $response;
            }


            //            event(new SendMail(3));
            //Fee Ledger Create in Receiveable Group
            $fee_ledger['name'] = $sturdent->name . ' - ' . $sturdent->email . ' - Recieveables';
            $fee_ledger['group_id'] = Config::get('account_constants.Current_Asset_Fee');
            $fee_ledger['parent_type'] = $sturdent->id;

            $Current_assets_fee_ledger = Helpers::create_ledger($fee_ledger);


            if ($Current_assets_fee_ledger) {
                //Fee Ledger Create in Income Group
                $fee_ledger['name'] = $sturdent->name . ' - ' . $sturdent->email . ' - Income';
                $fee_ledger['group_id'] = Config::get('account_constants.Income_Group_Fee');
                $fee_ledger['parent_type'] = $sturdent->id;
                $Income_fee_ledger = Helpers::create_ledger($fee_ledger);

                //Entry Create
                $entry['entry_type_id'] = Config::get('voucher_types.Fee_Receive_Voucher');
                $entry['narration'] = $sturdent->name . ' - ' . $sturdent->email;
                //                dd($entry);
                $Entry_Create = Helpers::create_entry($entry);

                //Entry Item Create for Student Fee
                $entry_item['entry_id'] = $Entry_Create;
                $entry_item['amount'] = $request->student_fee;
                $entry_item['narration'] = "Create Student Fee " . $sturdent->name . ' - ' . $sturdent->email . ' Recieveables';
                $entry_item['dc'] = 'd';
                $entry_item['ledger_id'] = $Current_assets_fee_ledger->id;
                $Entry_Item_create = Helpers::create_entry_item($entry_item);

                if ($Income_fee_ledger) {
                    $entry_item['dc'] = 'c';
                    $entry_item['narration'] = "Create Student Fee " . $sturdent->name . ' - ' . $sturdent->email . ' Income';
                    $entry_item['ledger_id'] = $Income_fee_ledger->id;
                    $Entry_Item_create = Helpers::create_entry_item($entry_item);
                }
                //                //Entry Item for Advance Student Fee
//                $Current_Assets_Cash = Config::get('accounts_constants.Current_Assets_Cash');
//                $Cash_in_Hand = Helpers::get_ledger($Current_Assets_Cash);
//                if (!$Cash_in_Hand) {
//                    $Cash_in_Hand = Config::get('ledger_constants.Current_Assets_Cash_in_Hand');
//
//                }
//
//
//                $entry_item['amount'] = $request->advance;
//                $entry_item['narration'] = "Advance Student Fee " . $sturdent->name .' - '. $sturdent->email;
//                $entry_item['dc'] = 'd';
//                $entry_item['ledger_id'] = $Cash_in_Hand;
//                $Entry_Item_create = Helpers::create_entry_item($entry_item);
//
//                if ($Income_fee_ledger) {
//                    $entry_item['dc'] = 'c';
//                    $entry_item['ledger_id'] = $Current_assets_fee_ledger->id;
//                    $Entry_Item_create = Helpers::create_entry_item($entry_item);
//                }


            }


        }

        foreach ($request->course_id as $item) {
            $studentFee = new StudentFee();
            //          $studentFee->data_bank_id = $request->data_bank_id;
            $studentFee->student_id = $request->data_bank_id;
            $studentFee->student_fee = $request->student_fee;
            $studentFee->course_id = $item;
            if ($request->Session) {
                $studentFee->session_id = $request->Session;
            }
            $studentFee->course_fee = $request->course_fee;
            $studentFee->discount_amount = $request->discounted_amount;
            $studentFee->remaining_amount = $request->student_fee;
            //            $studentFee->installement_type = $request->installement_type;
            $studentFee->save();
        }

        $PaidStudentFee = new PaidStudentFee();
        $PaidStudentFee->student_fee_id = $studentFee->id;
        $PaidStudentFee->student_id = $request->data_bank_id;
        $PaidStudentFee->source = $request->Payment_source;
        $PaidStudentFee->paid_date = date('Y-m-d');
        $PaidStudentFee->start_date = date('Y-m-d');
        $PaidStudentFee->due_date = date('Y-m-d');
        //        $PaidStudentFee->paid_status = 'paid';
        $PaidStudentFee->type = 'advance';
        $PaidStudentFee->installement_amount = $request->advance;
        $PaidStudentFee->paid_status = 'pending';

        $PaidStudentFee->save();


    }

    public function get_data_student_fee($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        //       $fee_paid = StudentFee::withSum('paidfee','installement_amount')->where('paidfee_sum_installement_amount','<=',30000)->get();
        $data = StudentFee::with('course', 'student', 'session', 'tool_date');
        if ($request->session) {
            $data = $data->where('session_id', $request->session);
        }
        if ($request->course) {
            $data = $data->where('course_id', $request->course);
        }
        if ($request->discount) {
            if ($request->discount == "yes") {
                $data = $data->where('discount_amount', '>', 0);
            } else if ($request->discount == "no") {
                $data = $data->where('discount_amount', '<=', 0);
            }
        }
        if ($request->tools) {
            if ($request->tools == "yes") {
                $data = $data->where('tools_provided', '=', 'yes');
            } else if ($request->tools == "no") {
                $data = $data->where('tools_provided', '=', 'no');
            }
        }
        if ($request->defaulter) {
            if ($request->defaulter == "yes") {
                $data = $data->where('defaulter', 1);
            } else if ($request->tools == "no") {
                $data = $data->where('defaulter', 0);
            }
        }
        if (isset($request->date) && isset($request->date_end)) {
            if ($request->date && $request->date_end) {
                $data = $data->whereBetween('created_at', [
                    date('Y-m-d', strtotime($request->date)),
                    date('Y-m-d', strtotime($request->date_end))
                ]);
            }

        }
        if (isset($request->tools_date) && isset($request->tools_date_to)) {
            if ($request->tools_date && $request->tools_date_to) {

                $user = $data->pluck('id');
                $tools = AssignTool::whereBetween('created_at', [
                    date('Y-m-d', strtotime($request->tools_date)),
                    date('Y-m-d', strtotime($request->tools_date_to))
                ])->whereIn('student_fee_id', $user)->pluck('student_fee_id');

                $data = $data->whereIn('id', $tools);
            }

        }
        if (isset($request->remaining)) {
            if ($request->remaining == 30) {
                $data = $data->where('total_paid_fee', '>=', 30000);
            } elseif ($request->remaining == 1) {
                $data = $data->where('remaining_amount', '>', 0);
            } elseif ($request->remaining == 0) {
                $data = $data->where('remaining_amount', '<=', 0);
            }
        }
        if (isset($request->certificates)) {

            if ($request->certificates == "yes") {
                $data = $data->where('certificate', '=', 'Yes');

            } else if ($request->certificates == "no") {
                $data = $data->where('certificate', '=', 'No');
            }
        }


        $data = $data->get();


        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="' . route('admin.fee_paid_detail', $row->id) . '"  class="btn btn-sm btn-light">Details </a>';
                $perc = 0;

                $st_fee = $row->student_fee;
                $fee_paid = $row->total_paid_fee;
                $discount = $row->course_fee == $row->discount_amount;


                if ($fee_paid > 0)
                    $perc = $fee_paid / $st_fee * 100;


                if ($perc >= 70 || $row->remaining_amount == 0 || $discount)
                    $btn = $btn . '<a href="' . route('admin.assign_tools_get', $row->id) . '"  class="btn btn-sm btn-light mt-1">Assign Tools </a>';


                $sess = Session::pluck('id');
                $student = Students::with('session')->where('id', $row->student_id)->where('session_id', '!=', null)->first();


                if (isset($student->session)) {
                    $sess_created_at = $student->session->created_at;


                    $curr_date = Carbon::today();
                    $diff_days = $curr_date->diffInDays($sess_created_at);


                    if ($diff_days > 63 && $row->remaining_amount == 0 && $row->defaulter != 1)
                        $btn = $btn . '<a href="' . route('admin.assign_certificate', $row->id) . '"  class="btn btn-sm btn-light mt-1">Assign Certificate ' . $diff_days . '-Days' . '</a>';

                }


                return $btn;
            })
            ->addColumn('paid_date', function ($row) {
                if (isset($row->paid_date)) {
                    $btn = $row->paid_date;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }
                return $btn;
            })->addColumn('defaulter', function ($row) {
                if ($row->defaulter == 0) {
                    $btn = "No";
                    return $btn;
                } else {
                    $btn = "Yes";
                    return $btn;
                }
                return $btn;
            })->addColumn('session', function ($row) {
                if (isset($row->session)) {
                    $btn = $row->session->title;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }
                return $btn;
            })->addColumn('student_name', function ($row) {
                if (isset($row->student)) {
                    $btn = $row->student->name;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }

                return $btn;
            })->addColumn('student_status', function ($row) {
                if (isset($row->student)) {
                    $btn = $row->student->status;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }

                return $btn;
            })->addColumn('course_name', function ($row) {
                if (isset($row->course)) {
                    $btn = $row->course->name;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }

                return $btn;
            })
            ->addColumn('source', function ($row) {
                if (isset($row->source)) {
                    $btn = $row->source;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }

                return $btn;
            })->addColumn('date', function ($row) {
                if (isset($row->created_at)) {
                    $btn = date('Y-m-d', strtotime($row->created_at));

                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }

                return $btn;
            })->addColumn('remaining_amount', function ($row) {

                if ($row->defaulter == 0) {
                    $btn = $row->remaining_amount;

                } else {
                    $btn = 0;

                }
                return $btn;
            })->addColumn('tools_date', function ($row) {
                if (isset($row->tool_date)) {
                    if (count($row->tool_date) > 0) {
                        $btn = date('Y-m-d', strtotime($row->tool_date[0]->created_at));
                        return $btn;
                    }


                } else {
                    $btn = 'N/A';
                    return $btn;
                }

            })->addColumn('total_paid_fee', function ($row) {


                if (isset($row->total_paid_fee)) {
                    $btn = 0;
                    if ($row->total_paid_fee >= 30000) {
                        //                        $btn = '<p style="color: white;font-weight: bold">' . " $row->total_paid_fee " . '</p>';
                        return $row->total_paid_fee;
                    }
                    if ($row->total_paid_fee < 30000) {
                        //                        $btn = $row->total_paid_fee;
                        return $row->total_paid_fee;
                    } else
                        return $btn;
                } else {
                    $btn = 0;
                    return $btn;
                }

                return $btn;

            })->addColumn('tools_provided', function ($row) {
                if (isset($row->tools_provided)) {
                    if ($row->tools_provided == 'Yes') {
                        $btn = '<p style="color: white;font-weight: bold">' . " $row->tools_provided " . '</p>';
                        return $btn;
                    }
                    if ($row->tools_provided == "No") {
                        $btn = '<p style="color: white;">' . " $row->tools_provided " . '</p>';
                        return $btn;
                    }
                }

            })->addColumn('id', function ($row) {

                $btn = '<p style="color: black;font-weight: bold; font-size: large">' . " $row->id " . '</p>';
                return $btn;

            })->setRowClass(function ($row) {
                if ($row->defaulter == 1) {
                    return 'bg-dark text-white';
                }

                $perc = 0;
                $st_fee = $row->student_fee;
                $fee_paid = $row->total_paid_fee;
                if ($fee_paid > 0)
                    $perc = $fee_paid / $st_fee * 100;

                if ($perc >= 70 && $row->remaining_amount != 0) {
                    return 'bg-warning';
                } elseif ($row->remaining_amount == 0) {
                    return 'bg-success';
                } elseif ($perc < 70) {
                    return 'bg-danger';
                }


                //                return ($row->total_paid_fee >= 30000 ? 'bg-success' : 'bg-danger');
            })
            ->rawColumns(['source', 'id', 'tools_provided', 'student_status', 'date', 'tools_date', 'status', 'action', 'course_name', 'paid_date', 'session', 'student_name', 'total_paid_fee'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data['course'] = Course::select('id', 'name', 'fee')->get();
        $data['databank'] = StudentDataBank::all();
        return $data;

    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $studentFee = StudentFee::find($id);
        $studentFee->data_bank_id = $request->data_bank_id;
        $studentFee->student_fee = $request->student_fee;
        foreach ($request->course_id as $item) {
            $studentFee->course_id = $item;
        }

        $studentFee->course_fee = $request->course_fee;
        $studentFee->installement_type = $request->installement_type;
        $studentFee->save();

    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $student = StudentFee::findOrFail($id);
        if ($student)
            $student->delete();

    }

    public function fee_paid_detail_delete($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $student = PaidStudentFee::findOrFail($id);


        if ($student) {

            if ($student->paid_status == "paid") {
                $StudentFee = StudentFee::where('id', $student->student_fee_id)->first();
                if ($StudentFee) {


                    $StudentFee->remaining_amount = $StudentFee->remaining_amount + $student->installement_amount;
                    $StudentFee->total_paid_fee = $StudentFee->total_paid_fee - $student->installement_amount;

                    $student_data = Students::find($StudentFee->student_id);

                    //Entry Create
                    $entry['entry_type_id'] = Config::get('voucher_types.Fee_Receive_Voucher');
                    $entry['narration'] = $student_data->name . ' - ' . $student_data->email . ' - ' . $student_data->type;
                    //                dd($entry);
                    $Entry_Create = Helpers::create_entry($entry);

                    $group_id = Config::get('account_constants.Current_Asset_Fee');
                    $parent_type = $student->id;
                    $Current_assets_fee_ledger = Helpers::get_ledger($group_id, $parent_type);


                    //                //Entry Item for Advance Student Fee
                    $Current_Assets_Cash = Config::get('accounts_constants.Current_Assets_Cash');
                    $Cash_in_Hand = Helpers::get_ledger($Current_Assets_Cash);
                    if (!$Cash_in_Hand) {
                        $Cash_in_Hand = Config::get('ledger_constants.Current_Assets_Cash_in_Hand');

                    }

                    $entry_item['entry_id'] = $Entry_Create;
                    $entry_item['amount'] = $student->installement_amount;
                    $entry_item['narration'] = "Advance Student Fee " . $student->name . ' - ' . $student->email;
                    $entry_item['dc'] = 'c';
                    $entry_item['ledger_id'] = $Cash_in_Hand;
                    $Entry_Item_create = Helpers::create_entry_item($entry_item);

                    if ($Current_assets_fee_ledger) {
                        $entry_item['dc'] = 'd';
                        $entry_item['ledger_id'] = $Current_assets_fee_ledger->id;
                        $Entry_Item_create = Helpers::create_entry_item($entry_item);
                    }

                    $StudentFee->save();
                }


            }


            $student->delete();

        }


    }

    public function get_state($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $html = "<option value=''> Select State </option>";
        $state = State::where('country_id', $id)->get();
        foreach ($state as $item) {
            $html = $html . "<option value='" . $item->id . "'>" . $item->name . "</option>";
        }

        return $html;
    }

    public function get_city($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $html = "<option value=''> Select State </option>";
        $city = City::where('state_id', $id)->get();
        foreach ($city as $item) {
            $html = $html . "<option value='" . $item->id . "'>" . $item->name . "</option>";
        }
        return $html;


    }

    public function cronjob()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $date = date('Y-m-d');

        $courses = Course::where('status', 1)->get();

        foreach ($courses as $course) {


            //            $sessions = Session::where('course_id', $course->id)->where('status', 1)->where('start_date', '<=', $date)->where('end_date', '>=', $date)->pluck('id');
            $sessions = Session::where('course_id', $course->id)->get();

            foreach ($sessions as $session) {

                $start_date = $session->start_date;
                $end_date = $session->end_date;

                $diff = abs(strtotime($start_date) - strtotime($end_date));

                $years = floor($diff / (365 * 60 * 60 * 24));
                $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

                dd($days);
                die;

                $students = StudentFee::where('session_id', $sessions)->where('remaining_amount', '>', 0)->where('defaulter', 0)->get();
                dd($students);
            }


        }
    }

    public function assign_certificate($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $student_cert = StudentFee::find($id);
        $student_cert->certificate = 'Yes';
        $student_cert->save();
    }
}
