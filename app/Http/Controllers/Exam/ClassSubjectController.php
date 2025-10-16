<?php

namespace App\Http\Controllers\Exam;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Academic\AcademicClass;
use App\Models\Admin\Company;
use App\Models\Admin\Course;
use App\Models\Exam\ClassSubject;
use App\Services\ClassSubjectService;
use Gate;
use Illuminate\Http\Request;

class ClassSubjectController extends Controller
{
    protected $ClassSubjectService;

    public function __construct(ClassSubjectService $classSubjectService)
    {
        $this->ClassSubjectService = $classSubjectService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('class-subjects-list')) {
            return abort(503);
        }
        return view('exam.class_subject.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('class-subjects-create')) {
            return abort(503);
        }
        $classes = AcademicClass::where('status', 1)->get();
        $subjects = Course::where('status', 1)->get();
        $sessions = UserHelper::session_name();

        $companies = Company::where('status', 1)->get();
        return view('exam.class_subject.create', compact('classes', 'subjects', 'companies', 'sessions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('class-subjects-create')) {
            return abort(503);
        }
        $this->ClassSubjectService->store($request);
        return redirect()->route('exam.class_subjects.index')->with('success', 'Class assigned to Subject');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('class-subjects-list')) {
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
       if (!Gate::allows('class-subjects-edit')) {
            return abort(503);
        }
        $classSubject = ClassSubject::find($id);
        $classes = AcademicClass::where('status', 1)->get();
        $Subjects = Course::where('status', 1)->get();
        $sessions = UserHelper::session_name();
        $companies = Company::where('status', 1)->get();

        return view('exam.class_subject.edit', compact('classes', 'Subjects', 'companies', 'sessions', 'classSubject'));
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
        if (!Gate::allows('class-subjects-edit')) {
            return abort(503);
        }
        $this->ClassSubjectService->update($request, $id);
        return redirect()->route('exam.class_subjects.index')->with('success', 'Class assigned to Subject');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('class-subjects-delete')) {
            return abort(503);
        }
        $this->ClassSubjectService->destroy($id);
        return redirect()->route('exam.class_subjects.index')->with('success', 'Deleted Sucessfully');
    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('class-subjects-list')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $classSubject = ClassSubject::find($id);
            if ($classSubject) {
                $classSubject->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }
    public function getData()
    {
        if (!Gate::allows('class-subjects-list')) {
            return abort(503);
        }
        return $this->ClassSubjectService->getdata();
    }

    

}


