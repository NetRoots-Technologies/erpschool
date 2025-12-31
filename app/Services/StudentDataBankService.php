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
        
        // 2. Auto Challan create
        $this->createChallanForStudent($studentDatabank);

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
                // studentchallan creation link
                // if(auth()->user()->can('PreAdmissionForm-list')){
                $btn .= '<a href="' . route("academic.studentChallans", ['student_databank_id' => $row->id]) . '" class="btn btn-info btn-sm"  style="margin-right: 4px;">Challans</a>';
                // }   
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

    protected function createChallanForStudent($student)
    {
        $lastChallan = \App\Models\StudentChallan::latest()->first();
        $challanNo = 'CH-' . str_pad(($lastChallan ? $lastChallan->id + 1 : 1), 6, '0', STR_PAD_LEFT);

        \App\Models\StudentChallan::create([
            'student_databank_id' => $student->id,
            'challan_no'          => $challanNo,
            'reference_no'        => uniqid('REF-'),
            'amount'              => 5000,  // Default amount
            'issue_date'          => now(),
            'due_date'            => now()->addDays(7), // Optional
        ]);
    }


}

