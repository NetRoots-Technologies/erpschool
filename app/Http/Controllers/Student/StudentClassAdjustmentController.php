<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Models\Admin\Company;
use App\Models\Student\Students;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class StudentClassAdjustmentController extends Controller
{

    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $companies=Company::all();
        return view('acadmeic.student_class_adjustment.create',compact('companies'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $request->validate([
            'student_id' => 'required|array|min:1',
            'student_id.*' => 'exists:students,id',
            'to_company_id' => 'required|exists:company,id',
            'to_branch_id' => 'required|exists:branches,id',
            'to_class_id' => 'required|exists:classes,id',
            'to_section_id' => 'required|exists:sections,id',
        ]);

        $studentIds = $request->input('student_id');
        $toCompanyId = $request->input('to_company_id');
        $toBranchId = $request->input('to_branch_id');
        $toClassId = $request->input('to_class_id');
        $toSectionId = $request->input('to_section_id');

        $updated=Students::whereIn('id', $studentIds)->update([
            'company_id' => $toCompanyId,
            'branch_id' => $toBranchId,
            'class_id' => $toClassId,
            'section_id' => $toSectionId,
        ]);
        if($updated){
             return redirect()->route('academic.student-class-adjustment.create')
            ->with('success', 'Students successfully adjusted to new class.');
        }else{
             return redirect()->route('academic.student-class-adjustment.create')
            ->with('success', 'Something Went Wrong');
        }
    }

}
