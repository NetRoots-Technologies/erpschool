<?php
namespace App\Services;

use App\Models\Admin\StudentDataBank;
use App\Models\Student\Students;
use Illuminate\Support\Facades\Gate;
use Monolog\Handler\IFTTTHandler;
use Yajra\DataTables\DataTables;

class StudentDataBankService
{


    public function store($request)
    {

        $studentDatabank = StudentDataBank::create([
            'reference_no' => $request->get('reference_no'),
            'student_name' => trim($request->get('first_name') . ' ' . $request->get('last_name')),
            'student_email' => $request->get('student_email'),
            'student_age' => $request->get('student_age'),
            'gender' => $request->get('gender'),
            'student_phone' => $request->get('student_phone'),
            'study_perviously' => $request->get('study_perviously'),
            'reason_for_leaving' => $request->get('reason_for_leaving'),
            'father_name' => $request->get('father_name'),
            'father_cnic' => $request->get('father_cnic'),
            'present_address' => $request->get('present_address'),
            'mother_name' => $request->get('mother_name'),
            'mother_cnic' => $request->get('mother_cnic'),
            'landline_number' => $request->get('landline_number'),
            'previous_school' => $request->get('previous_school'),
            'admission_for' => $request->get('admission_for'),
            'reason_of_switch' => $request->get('reason_of_switch'),
            'academic_session_id' => $request->get('academic_session_id'),
        ]);

        return $studentDatabank;
    }



    public function getData()
    {

        $data = StudentDataBank::orderBy('created_at', 'desc')->get();


        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;justify-content:center">';
                if(auth()->user()->can('PreAdmissionForm-create')){
                $btn .= '<a href="' . route("academic.add-student", $row->id) . '" class="btn btn-success btn-sm"  style="margin-right: 4px;">Add </a>';

                }
                if(auth()->user()->can('PreAdmissionForm-edit')){
                $btn .= '<a href="' . route("academic.studentDataBank.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';

                }
                // $btn .= '<form method="POST" action="' . route("academic.studentDataBank.destroy", $row->id) . '">';
                // $btn .= '<button type="submit" class="btn btn-danger btn-sm deleteBtn"
                // data-id="' . $row->id . '"
                // data-url="' . route("academic.studentDataBank.destroy", $row->id) . '"
                // style="margin-right: 4px;">Delete</button>';
                // $btn .= '</form>';
                $btn .= '</div>';

                return $btn;

            })->addColumn('status', function ($row) {

                if ($row->status == '1') {
                    return 'Active';
                } else {
                    return 'InActive';

                }
            })->addColumn('name', function ($row) {

                if ($row->student_name) {
                    return $row->student_name;
                } else {
                    return 'N/A';

                }
            })
            ->rawColumns(['action', 'status'])
            ->make(true);


    }

    public function update($request, $id)
    {

        $studentDatabank = StudentDataBank::find($id);

        $studentDatabank->update([
            'reference_no' => $request->get('reference_no'),
            'student_name' => trim($request->get('first_name') . ' ' . $request->get('last_name')),
            'student_email' => $request->get('student_email'),
            'student_age' => $request->get('student_age'),
            'gender' => $request->get('gender'),
            'student_phone' => $request->get('student_phone'),
            'study_perviously' => $request->get('study_perviously'),
            'reason_for_leaving' => $request->get('reason_for_leaving'),
            'father_name' => $request->get('father_name'),
            'father_cnic' => $request->get('father_cnic'),
            'present_address' => $request->get('present_address'),
            'mother_name' => $request->get('mother_name'),
            'mother_cnic' => $request->get('mother_cnic'),
            'landline_number' => $request->get('landline_number'),
            'previous_school' => $request->get('previous_school'),
            'admission_for' => $request->get('admission_for'),
            'reason_of_switch' => $request->get('reason_of_switch'),
            'academic_session_id' => $request->get('academic_session_id'),
        ]);
        return $studentDatabank;
    }

    public function destroy($id)
    {

        $studentDatabank = StudentDataBank::find($id);
        if ($studentDatabank) {
            $studentDatabank->delete();
        }
    }

}

