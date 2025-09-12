<?php

namespace App\Http\Controllers\Exam;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Exam\MarkInput;
use App\Models\Exam\MarkEntry;
use App\Services\MarksInputService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class MarksInputController extends Controller
{
    public function __construct(MarksInputService $marksInputService)
    {
        $this->marksInputService = $marksInputService;
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
        return view('exam.marks_input.index');
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
        $companies = Company::where('status', 1)->get();
        $sessions = UserHelper::session_name();
        return view('exam.marks_input.create', compact('companies', 'sessions'));
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
        // try {
            $this->marksInputService->store($request);
            return redirect()->route('exam.marks_input.index')->with('success', 'Marks Input saved successfully');
        // } catch (\Exception $e) {
        //     return redirect()->back()->withErrors(['error' => 'Failed to save marks input: ' . $e->getMessage()]);
        // }
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
        $marksInput = MarkInput::with(['academicSession', 'company', 'branch', 'section', 'fetchClass', 'subject', 'component', 'subComponent','mark_entries'])->find($id);
        $sessions = UserHelper::session_name();
        $companies = Company::where('id', $marksInput->company_id)->get();
        $branches = Branch::where('status', 1)->get();
        //dd($marksInput);
        return view('exam.marks_input.edit', compact('marksInput', 'sessions', 'branches', 'companies'));
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
        $marksInput = MarkInput::findOrFail($id);

        // Only update mark entries
        if ($request->has('entries')) {
            
            foreach ($request->entries as $entryData) {
                if (isset($entryData['entry_id'])) {
                    MarkEntry::where('id', $entryData['entry_id'])->update([
                        'allocated_marks' => $entryData['allocated_marks']
                    ]);
                }
            }
        }

        return redirect()->route('exam.marks_input.index')->with('success', 'Allocated marks updated successfully.');
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
        $this->marksInputService->delete($id);

        return redirect()->route('exam.marks_input.index')->with('success', 'Marks Input deleted successfully');
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->marksInputService->getData();
    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $examTerm = MarkInput::find($id);
            if ($examTerm) {
                $examTerm->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }
}
