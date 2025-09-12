<?php

namespace App\Http\Controllers\Exam;

use App\Models\Exam\SkillEvaluationKey;
use App\Models\Exam\SkillGroup;
use App\Models\Exam\Skills;
use App\Models\Exam\SkillType;
use Illuminate\Http\Request;
use App\Models\Admin\Company;
use App\Http\Controllers\Controller;
use App\Models\Exam\SkillEvaluation;
use Illuminate\Support\Facades\Gate;
use App\Models\Student\AcademicSession;
use App\Services\SkillEvaluationService;

class SkillEvaluationController extends Controller
{

    public $skillEvaluationService;
    public function __construct(SkillEvaluationService $skillEvaluationService)
    {
        $this->skillEvaluationService = $skillEvaluationService;
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $skillEvaluation = $this->skillEvaluationService->getdata();
        return $skillEvaluation;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $companies = Company::all();
        $academic_sessions = AcademicSession::all();
        $skill_groups = SkillGroup::where('active', 1)->get();
        return view('exam.skill_evaluation.index', compact('companies', 'academic_sessions', 'skill_groups'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    return $this->skillEvaluationService->store($request);    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $skillEvaluation = $this->skillEvaluationService->update($request, $id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $skillEvaluation = $this->skillEvaluationService->destroy($id);

    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $skillEvaluation = $this->skillEvaluationService->changeStatus($request);
    }
    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $skillEvaluation = SkillEvaluation::find($id);
            if ($skillEvaluation) {
                $skillEvaluation->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }

    public function studentSubjectsWithEvaluation(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id'
        ]);
        // return $request->class_id;
        $skill_type = SkillType::with('subject', 'group', 'skill')->where('class_id', $request->class_id)->get();
        $skill_evaluation_key = SkillEvaluationKey::where('status', 1)->get();
        return ['skill_type' => $skill_type, 'skill_evaluation_key' => $skill_evaluation_key];
    }
}
