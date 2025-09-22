<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcademicClass;
use App\Models\Admin\Branch;
use App\Models\Admin\Course;
use App\Models\Exam\ExamDetail;
use App\Models\Exam\ExamTerm;
use App\Models\Exam\SubjectMarks;
use App\Models\Exam\TestType;
use App\Services\ExamDetailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ExamDetailController extends Controller
{
    protected $ExamDetailService;

    public function __construct(ExamDetailService $examDetailService)
    {
        $this->ExamDetailService = $examDetailService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('exam.exam_details.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $testTypes = TestType::where('status', 1)->get();
        $examTypes = ExamTerm::get();
        $branches = Branch::all();
        return view('exam.exam_details.create', compact('testTypes', 'examTypes', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->ExamDetailService->store($request);
        return redirect()->route('exam.exam_details.index')->with('success', 'Exam Detail created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('Dashboard-list')) {
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
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        // Load exam detail with its related subject marks
        $examDetail = ExamDetail::with(['subjectMarks.course','class.branch'])->findOrFail($id);
        $class=$examDetail->class->name;
        $branch=$examDetail->class->branch->name;

        $testTypes = TestType::where('status', 1)->get();
        $examTypes = ExamTerm::get();
        // echo $branch;
        // dd($examDetail);
        $subject=Course::where('branch_id',$examDetail->class->branch->id)->get();
        $marks=SubjectMarks::where('exam_detail_id',$examDetail->id)->get();
        // $marks=SubjectMarks::where('branch_id')
        // dd($marks);

        return view('exam.exam_details.edit', compact(
            'examDetail',   
            'testTypes',
            'examTypes',
            'branch',
            'class',
            'subject',
            'marks'
        ));
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
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->ExamDetailService->update($request, $id);
        return redirect()->route('exam.exam_details.index')->with('success', 'Exam Detail Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->ExamDetailService->destroy($id);
        return redirect()->route('exam.exam_details.index')->with('success', 'Exam Detail Updated successfully');
    }


    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $this->ExamDetailService->getData();
    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $testType = $this->ExamDetailService->changeStatus($request);
    }


    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $testType = ExamDetail::find($id);
            if ($testType) {
                $testType->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }

    public function getClass($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return AcademicClass::where('branch_id', $id)->get();
    }
}

