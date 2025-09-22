<?php

namespace App\Http\Controllers\Exam;

use App\Models\Exam\Skills;
use Illuminate\Http\Request;
use App\Services\SkillsService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Academic\AcademicClass;
use App\Models\Admin\Course;
use App\Models\Exam\Component;

class SkillsController extends Controller
{
    protected $skillsService;
    public function __construct(SkillsService $skillsService)
    {
        $this->skillsService = $skillsService;
    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $skills = $this->skillsService->getdata();
        return $skills;
    }

       public function getsubject($classId)
        {
            $subjects = Course::where('class_id', $classId)->get();

            return response()->json($subjects);
        }

        public function getComponents($subjectId)
        {
            $components = Component::where('subject_id', $subjectId)->get();

            return response()->json($components);
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

            // fetch classes for dropdown
            $classes = AcademicClass::get();

            return view('exam.skills.index', compact('classes'));
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     if (!Gate::allows('Dashboard-list')) {
    //         return abort(503);
    //     }
    //     $request->validate([
    //         'name' => 'required|unique:skills,name'
    //     ]);
    //     return $this->skillsService->store($request);
    // }
    public function store(Request $request)
{
    if (!Gate::allows('Dashboard-list')) {
        return abort(503);
    }
    // dd($request->all());

    $request->validate([
        'name'         => 'required|string|max:255|unique:skills,name',
        'class_id'     => 'required|exists:classes,id',
        'course_id'   => 'required|exists:courses,id',
        'component_id' => 'required|exists:components,id',
    ]);

    return $this->skillsService->store($request);
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
            'name' => [
            'required',
            Rule::unique('skills', 'name')->ignore($id),
        ]
        ]);
        return $this->skillsService->update($request, $id) ?? null;
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
        return $this->skillsService->destroy($id);
    }


    public function changeStatus(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $skills = $this->skillsService->changeStatus($request);
    }
    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $skills = Skills::find($id);
            if ($skills) {
                $skills->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }
}

