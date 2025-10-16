<?php

namespace App\Http\Controllers\Exam;

use App\Helpers\UserHelper;
use Illuminate\Http\Request;
use App\Models\Admin\Company;
use App\Models\Exam\Component;
use App\Services\ComponentService;
use App\Http\Controllers\Controller;
use App\Models\Academic\SchoolType;
use App\Models\Admin\Course;
use App\Models\Exam\ClassSubject;
use App\Models\Student\AcademicSession;
use Illuminate\Support\Facades\Gate;
use view;

class ComponentController extends Controller
{
    protected $componentService;

    public function __construct(componentService $componentService)
    {
        $this->componentService = $componentService;
    }

    public function getData()
    {
        if (!Gate::allows('Components-list')) {
            return abort(503);
        }
        $component = $this->componentService->getdata();
        return $component;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Components-list')) {
            return abort(503);
        }
        $sessions = AcademicSession::all();

        return view('exam.components.index',compact('sessions'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('Components-create')) {
            return abort(503);
        }
        $companies = Company::where('status', 1)->get();
        $sessions = UserHelper::session_name();

        return view('exam.components.create', compact('companies', 'sessions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('Components-create')) {
            return abort(503);
        }
        try {
            $this->componentService->store($request);

            return redirect()->route('exam.components.index')->with('success', 'Component created successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the component');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('Components-list')) {
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
        if (!Gate::allows('Components-edit')) {
            return abort(503);
        }
        $component = Component::with('componentData.test_type')->find($id);
        $companies = Company::where('status', 1)->get();
        $sessions = UserHelper::session_name();

        return view('exam.components.edit', compact('component', 'companies', 'sessions'));

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
        if (!Gate::allows('Components-edit')) {
            return abort(503);
        }
        try {

            $this->componentService->update($request, $id);
            return redirect()->route('exam.components.index')->with('success', 'Component updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the component');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('Components-delete')) {
            return abort(503);
        }
        try {
            $this->componentService->destroy($id);
            return redirect()->route('exam.components.index')->with('success', 'Component deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the component');
        }
    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('Components-list')) {
            return abort(503);
        }
        return $component = $this->componentService->changeStatus($request);
    }
    public function handleBulkAction(Request $request)
    {

        if (!Gate::allows('Components-list')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $component = Component::find($id);
            if ($component) {
                $component->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }

    public function fetchComponentSubject(Request $request)
    {
       
        $component = Component::where('subject_id', $request->course_id)->get();

        return response()->json($component);
    }

    public function clone(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'session_id' => 'required|exists:sessions,id',
        ]);

        // Find the original course
        $course = Component::findOrFail($request->course_id);

        // Clone (copy) the course attributes
        $newCourse = $course->replicate();

        // Override anything you want (like new session_id)
        $newCourse->session_id = $request->session_id;

        // Save the new course
        $newCourse->save();

        return redirect()->route('exam.components.index')
            ->with('success', 'Component Cloned successfully');
    }

    // public function changeStatus($request)
    // {
    //     if (!Gate::allows('Components-list')) {
    //         return abort(503);
    //     }
    //     $testType = TestType::find($request->id);
    //     if ($testType) {
    //         $testType->status = ($request->status == 'active') ? 1 : 0;
    //         $testType->save();
    //         return $testType;
    //     }
    // }


}

