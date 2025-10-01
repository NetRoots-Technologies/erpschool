<?php

namespace App\Http\Controllers\Exam;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Exam\EffortLevels;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Services\EffortLevelsService;
use App\Models\Admin\Company;
use App\Models\Exam\EffortLevel;
use Auth;
use App\Models\Students;



class EffortLevelsController extends Controller
{
    protected $effort_levels_service;
    public function __construct(EffortLevelsService $effort_levels_service)
    {
        $this->effort_levels_service = $effort_levels_service;
    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $effort_levels = $this->effort_levels_service->getdata();
        return $effort_levels;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
    $effortLevels = EffortLevel::with(['student.AcademicClass','student.section','student.branch.company', 'course', 'user'])->get();
    // dd($effortLevels);

    return view('exam.effort_levels.index', compact('effortLevels'));
    

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        return view('exam.effort_levels.create', compact('companies'));
    }



public function store(Request $request)
{
    $effortMap = [
        'Very Good' => 4,
        'Good' => 3,
        'Satisfactory' => 2,
        'Needs Improvement' => 1
    ];

    EffortLevel::create([
        'student_id' => $request->student_id,
        'user_id' => Auth::user()->id,
        'subject_id' => $request->subject_id,
        'effort' => $request->effort_level,
        'level' => (int) $request->achievement_level,
    ]);

    return redirect()->route('exam.effort_levels.index')->with('success', 'Effort level saved successfully');

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
        $request->validate([
            'abbrev' => [
                'required',
                Rule::unique('effort_levels', 'abbrev')->ignore($id),
            ],
            'key' => [
                'required'
            ]
        ]);
        return $this->effort_levels_service->update($request, $id) ?? null;
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
        return $this->effort_levels_service->destroy($id);
    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $effort_levels = $this->effort_levels_service->changeStatus($request);
    }
    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $effort_levels = EffortLevels::find($id);
            if ($effort_levels) {
                $effort_levels->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }
}

