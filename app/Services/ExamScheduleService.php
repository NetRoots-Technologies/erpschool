<?php 

namespace App\Services;
use App\Models\Exam\ExamSchedule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ExamScheduleService{
    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        // dd($request->all());
        $validator = Validator::make($request->all(), [
                'company_id'     => 'required|exists:company,id',
                'branch_id'      => 'required|exists:branches,id',
                'exam_term_id'   => 'required|exists:exam_terms,id',
                'test_type_id'   => 'required|exists:test_types,id',
                'class_id'       => 'required|exists:classes,id',
                // 'subject_id'     => 'required|exists:class_subjects,subject_id',
                'component_id'   => 'required|exists:components,id',
                'marks'          => 'nullable|numeric|min:0',
                'grade'          => 'nullable|boolean',
                'pass'           => 'nullable|boolean',
                'grade' => 'nullable|in:on',
                'pass'  => 'nullable|in:on',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            ExamSchedule::create([
                'company_id'     => $request->company_id,
                'branch_id'      => $request->branch_id,
                'exam_term_id'   => $request->exam_term_id,
                'test_type_id'   => $request->test_type_id,
                'class_id'       => $request->class_id,
                'course_id'     => $request->subject_id ?? 1,
                'component_id'   => $request->component_id,
                'marks'          => $request->marks,
                'grade'          => $request->has('grade') ? 1 : 0,
                'pass'           => $request->has('pass') ? 1 : 0,
            ]);

    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $examSchedule = ExamSchedule::find($id);
        if ($examSchedule) {
            $examSchedule->delete();
        }
    }
}