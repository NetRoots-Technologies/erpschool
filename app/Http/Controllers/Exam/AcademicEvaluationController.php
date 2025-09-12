<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam\AcademicEvaluationKey;
use App\Services\academicEvaluationKeyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AcademicEvaluationController extends Controller
{
    protected $academicEvaluationKeyService;
    public function __construct(academicEvaluationKeyService $academicEvaluationKeyService)
    {
        $this->academicEvaluationKeyService = $academicEvaluationKeyService;
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $academicEvaluationKey = $this->academicEvaluationKeyService->getdata();
        return $academicEvaluationKey;
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
        return view('exam.academic_evaluation_key.index');

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
        return $component = $this->academicEvaluationKeyService->store($request);
    }

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
        return $academicEvaluationKey = $this->academicEvaluationKeyService->update($request, $id);

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
        return $academicEvaluationKey = $this->academicEvaluationKeyService->destroy($id);

    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $academicEvaluationKey = $this->academicEvaluationKeyService->changeStatus($request);
    }
    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $skillEvaluation = AcademicEvaluationKey::find($id);
            if ($skillEvaluation) {
                $skillEvaluation->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }
}
