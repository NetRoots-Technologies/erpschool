<?php

namespace App\Http\Controllers\Exam;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Branch;
use App\Models\Exam\ExamTerm;
use App\Models\HR\EmployeeAllowance;
use App\Models\HRM\Employees;
use App\Services\ExamTermService;
use Illuminate\Http\Request;
use App\Models\Student\AcademicSession;
use Illuminate\Support\Facades\Gate;
class ExamTermController extends Controller
{

    public function __construct(ExamTermService $examTermService)
    {
        $this->ExamTermService = $examTermService;
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
        return view('exam.exam_term.index');
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
        $sessions = UserHelper::session_name();
        $branches = Branch::where('status', 1)->get();
        $hrm_employees = Employees::where('status',1)->get();
        return view('exam.exam_term.create', compact('sessions', 'branches', 'hrm_employees'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            $this->ExamTermService->store($request);
            return redirect()->route('exam.exam_terms.index')->with('success', 'Exam Term created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the Exam Term');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $examTerm = ExamTerm::find($id);
        $sessions = UserHelper::session_name();
        $branches = Branch::where('status', 1)->get();
        $hrm_employees = Employees::where('status',1)->get();
        return view('exam.exam_term.edit', compact('sessions', 'branches', 'examTerm', 'hrm_employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->ExamTermService->update($request, $id);

        return redirect()->route('exam.exam_terms.index')->with('success', 'Exam Term updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->ExamTermService->destroy($id);

        return redirect()->route('exam.exam_terms.index')->with('success', 'Exam Term deleted successfully');
    }

    public function handleBulkAction(Request $request)
    {
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $examTerm = ExamTerm::find($id);
            if ($examTerm) {
                $examTerm->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->ExamTermService->getData();
    }


    public function generateTermId(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $sessionId = $request->session_id;
        $branchId = $request->branch_id;

        $termCount = ExamTerm::where('session_id', $sessionId)
            ->where('branch_id', $branchId)
            ->count();


        $session = AcademicSession::find($sessionId);
        $sessionYear = $session ? $session->year : now()->year;

        $nextTermNumber = $termCount + 1;
        $termId = $sessionYear . ' - Term ' . $nextTermNumber;

        return response()->json(['term_id' => $termId]);
    }

}
