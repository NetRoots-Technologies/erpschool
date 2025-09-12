<?php

namespace App\Services;

use App\Models\Admin\OnezCampForm;
use App\Models\Admin\SeminarForm;
use App\Models\Admin\Session;
use App\Models\Admin\StudentDataBank;
use App\Models\Admin\BvForm;
use App\Models\Admin\StudentDataBankCourse;
use Illuminate\Support\Facades\DB;
use App\Models\Student\Students;
use Config;

use DataTables;
use Illuminate\Support\Facades\Auth;
use function Psr\Log\alert;
use Illuminate\Support\Facades\Gate;

class DataBank
{

    public function index1()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return StudentDataBank::all();
    }

    public function databank_create_student($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $StudentDataBank = StudentDataBank::find($id);
        $student = Students::where('email', $StudentDataBank->email)->first();
        if ($student) {
            return null;
        } else {
            return $StudentDataBank;
        }


        //        dd($StudentDataBank->email);
//        $student= Students::get('email');
//        dd($StudentDataBank->email,$student[0]->email);


        //        return StudentDataBank::find($id);
    }

    public function student_databank_remarks($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $remarks = StudentDataBank::find($request->id);
        $remarks->remarks = $request->remarks;
        $remarks->save();
        return response()->json(['success' => 'Successfully']);

    }

    public function student_databank_status($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $status = StudentDataBank::find($request->id);
        $status->status = $request->status;
        $status->save();
        return response()->json(['success' => 'Successfully']);

    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $walkin_student = StudentDataBank::find($id);
        if ($walkin_student) {
            $walkin_student->delete();
        }
    }

    public function walk_in_student($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $user_id = Auth::user()->id;
        $walk_in_student = new StudentDataBank();
        $walk_in_student->name = $request->name;
        $walk_in_student->email = $request->email;
        $walk_in_student->city = $request->city;
        $walk_in_student->mobile_no = $request->mobile_no;
        $walk_in_student->message = $request->remarks;
        $walk_in_student->created_by = $user_id;
        $walk_in_student->form_type = 'CRM';
        $walk_in_student->save();
    }

    public function getdata($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $user = auth()->user();
        if ($user->role_id == 2) {
            $id = $user->id;
            //            $data = StudentDataBank:: where('status', '!=', 1)->where('created_by', $id)->groupBy('email')->get();
            $data = DB::table('student_data_bank')->where('status', '!=', 1)->where('created_by', $id)->groupBy('email')->get();
        } else {
            $data = StudentDataBank::groupby('email')->where('status', '!=', 1)->with('courses', 'old_courses.course_name')->get();

        }
        if ($request->course) {
            $data = $data->where('course_id', $request->course);
        }
        if ($request->form_type) {
            if ($request->form_type == "CRM") {
                $data = $data->where('form_type', '=', 'CRM');
            } else if ($request->form_type == "Website") {
                $data = $data->where('form_type', '=', 'Website');
            }
        }
        if (isset($request->date) && isset($request->date_end)) {
            if ($request->date && $request->date_end) {
                $data = $data
                    ->where('created_at', '>=', date('Y-m-d', strtotime($request->date)))
                    ->where('created_at', '<=', date('Y-m-d', strtotime($request->date_end)));

            }

        }
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('message', function ($row) {
                return '<button style="margin-left: 18px" data-id="' . $row->id . '" data-message="' . $row->message . '" class="btn btn-outline-primary message_view"><i class="fa fa-envelope" ></i></button>';
            })
            ->addColumn('view_courses', function ($row) {
                return '<button style="margin-left: 18px"  data-route="' . route("admin.view_data_bank_courses", $row->id) . '"   data-id="' . $row->id . '" data-message="' . $row->message . '" class="btn btn-primary  btn-sm view_course">View Course</button>';
            })
            ->addColumn('remarks', function ($row) {
                return $row->remarks;
                //                return '<button style="margin-left: 18px" data-id="' . $row->id . '" data-name="' . $row->name . '" data-remarks="' . $row->remarks . '" class="btn btn-outline-primary remarks_write"><i class="	fas fa-pencil-alt"></i></button>';
    
            })
            ->addColumn('created_at', function ($row) {
                $btn = date("M d,Y ", strtotime($row->created_at));
                $btn = $btn . '<br/>' . date(" h:i A ", strtotime($row->created_at));

                return $btn;

            })
            ->addColumn('courses', function ($row) {
                //dd($row->old_courses->course_name->name);
    
                if (isset($row->courses)) {
                    if (isset($row->courses)) {
                        return $row->courses->name;
                    }


                } elseif (isset($row->old_courses)) { {
                        if (isset($row->old_courses->course_name->name)) {

                            $crow = StudentDataBank::find($row->id);
                            $crow->course_id = $row->old_courses->course_name->id;
                            $crow->save();
                            return $row->old_courses->course_name->name;
                        }
                    }
                } else {
                    return 'N/A';
                }


            })->addColumn('action', function ($row) {
                $btn = '';
                if (Gate::allows('students-create'))
                    $btn = '<a href="' . route("students.databank.create", $row->id) . '" class="btn btn-primary create_student   btn-sm mt-2">Create Student</a><br>';

                if (Gate::allows('student_databank-delete'))
                    $btn = $btn . '<a  class="btn btn-danger mt-2 btn-sm delete" data-route="' . route("admin.student_databank.destroy", $row->id) . '">Delete Student</a>';
                return $btn;

            })->addColumn('status', function ($row) {
                $btn = '<select style="width: 86px" class="form-control status"  data-id="' . $row->id . '"  name="status" id="status_' . $row->id . '" >';
                $btn = $btn . '  <option ';
                if ($row->status == 1)
                    $btn = $btn . 'selected';

                $btn = $btn . 'value="1">Active</option>

                         <option ';


                if ($row->status == 2)
                    $btn = $btn . 'selected';

                $btn = $btn . '  value="2">De-Active</option>
                         <option ';

                if ($row->status == 0 || $row->status == null)
                    $btn = $btn . 'selected';

                $btn = $btn . '   value="0">Pending</option>

                       </select>';

                return $btn;
            })
            ->rawColumns(['message', 'created_at', 'action', 'destroy', 'remarks', 'status', 'view_courses', 'old_courses'])
            ->make(true);
    }


    public function bv_form_get_data($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = BvForm::get();
        if (isset($request->date) && isset($request->date_end)) {
            if ($request->date && $request->date_end) {
                $data = $data
                    ->where('created_at', '>=', date('Y-m-d', strtotime($request->date)))
                    ->where('created_at', '<=', date('Y-m-d', strtotime($request->date_end)));

            }

        }
        return Datatables::of($data)->addIndexColumn()
            ->make(true);

    }

    public function seminar_form_get_data($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = SeminarForm::get();
        if (isset($request->date) && isset($request->date_end)) {
            if ($request->date && $request->date_end) {
                $data = $data
                    ->where('created_at', '>=', date('Y-m-d', strtotime($request->date)))
                    ->where('created_at', '<=', date('Y-m-d', strtotime($request->date_end)));

            }

        }
        return Datatables::of($data)->addIndexColumn()
            ->make(true);

    }

    public function onezcamp_form_get_data($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = OnezCampForm::get();
        if (isset($request->date) && isset($request->date_end)) {
            if ($request->date && $request->date_end) {
                $data = $data
                    ->where('created_at', '>=', date('Y-m-d', strtotime($request->date)))
                    ->where('created_at', '<=', date('Y-m-d', strtotime($request->date_end)));

            }

        }
        return Datatables::of($data)->addIndexColumn()
            ->make(true);

    }
}
