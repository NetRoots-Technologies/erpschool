<?php

namespace App\Http\Controllers\Exam;

use App\Models\Academic\AcademicClass;
use App\Models\Exam\GradingPolicies;
use App\Models\Student\AcademicSession;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Services\GradingPoliciesService;

class GradingPoliciesController extends Controller
{
    protected $grading_policies_service;
    public function __construct(GradingPoliciesService $grading_policies_service)
    {
        $this->grading_policies_service = $grading_policies_service;
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $grading_policies = $this->grading_policies_service->getdata();
        return $grading_policies;
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
        $acadmeic_sessions = AcademicSession::where('status', 1)->get();
        $classes_list = AcademicClass::select('id', 'name')->where('status', 1)->get();
        return view('exam.grading_policies.index', compact('classes_list', 'acadmeic_sessions'));
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
        $request->validate([
            'acadmeic_session_id' => 'required|exists:acadmeic_sessions,id',
            'class_id' => 'required|exists:classes,id',
            'grade' => 'required',
            'marks_range' => 'required',
            'marks_from' => 'required|numeric',
            'marks_to' => 'required|numeric',
            'description' => 'required'
        ]);
        return $this->grading_policies_service->store($request);
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
        $request->validate([
            'acadmeic_session_id' => 'required|exists:acadmeic_sessions,id',
            'class_id' => 'required|exists:classes,id',
            'grade' => 'required',
            'marks_range' => 'required',
            'marks_from' => 'required|numeric',
            'marks_to' => 'required|numeric',
            'description' => 'required'
        ]);
        return $this->grading_policies_service->update($request, $id) ?? null;
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
        return $this->grading_policies_service->destroy($id);
    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $grading_policies = $this->grading_policies_service->changeStatus($request);
    }
    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $grading_policies = GradingPolicies::find($id);
            if ($grading_policies) {
                $grading_policies->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }
}
