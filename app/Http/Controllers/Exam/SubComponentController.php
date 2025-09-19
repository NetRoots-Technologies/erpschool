<?php

namespace App\Http\Controllers\Exam;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Company;
use App\Models\Exam\Component;
use App\Models\Exam\SubComponent;
use App\Models\Student\AcademicSession;
use App\Services\subComponentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SubComponentController extends Controller
{
    public function __construct(subComponentService $subComponentService)
    {
        $this->subComponentService = $subComponentService;
    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $subComponent = $this->subComponentService->getdata();
        return $subComponent;
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
        $sessions = AcademicSession::all();

        return view('exam.sub_component.index',compact('sessions'));
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
        $companies = Company::where('status', 1)->get();
        $sessions = UserHelper::session_name();
        return view('exam.sub_component.create', compact('companies', 'sessions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        try {
            $this->subComponentService->store($request);
            return redirect()->route('exam.sub_components.index')->with('success', 'Sub Component created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the sub component');
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
       if (!Gate::allows('Dashboard-list')) {
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
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        $subComponent = SubComponent::with('component.componentData.test_type')->findOrFail($id);
        $component = Component::with('componentData.test_type')->findOrFail($subComponent->component_id);

        $companies = Company::where('status', 1)->get();

        $selectedFields = [
            'branch_id' => optional($subComponent->component)->branch_id,
            'class_id' => optional($subComponent->component)->class_id,
            'section_id' => optional($subComponent->component)->section_id,
            'subject_id' => optional($subComponent->component)->subject_id,
        ];

        $sessions = UserHelper::session_name();

        return view('exam.sub_component.edit', compact('subComponent', 'companies', 'sessions', 'selectedFields', 'component'));
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
    if (!Gate::allows('Dashboard-list')) {
        return abort(503);
    }
    try {
        // Fetch the existing subcomponent to get the component_id
        $subComponent = SubComponent::findOrFail($id);

        // Pass the component_id to the service update method
        $this->subComponentService->update($request, $subComponent->component_id);

        return redirect()->route('exam.sub_components.index')->with('success', 'Sub Component updated successfully');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred while updated the sub component');
    }
}


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        try {
            $this->subComponentService->destroy($id);
            return redirect()->route('exam.sub_components.index')->with('success', 'Sub Component deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleted the sub component');
        }
    }

    public function sub_component_data(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $component = Component::with('componentData.test_type')->find($request->component_id);
        //dd($component);
        return view('exam.sub_component.data', compact('component'));
    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $subComponent = SubComponent::find($id);
            if ($subComponent) {
                $subComponent->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }


    public function fetchSubComponent(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $subComponent = SubComponent::with('test_type')->where('component_id', $request->component_id)->get();
        return $subComponent;
    }

    public function fetchMarks(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $marks = SubComponent::where('id', $request->sub_component_id)->first();
        return $marks->comp_number;
    }
    //     public function clone(Request $request)
    // {
    //     $request->validate([
    //         'course_id' => 'required|exists:courses,id',
    //         'session_id' => 'required|exists:sessions,id',
    //     ]);

    //     // Find the original course
    //     $course = SubComponent::findOrFail($request->course_id);

    //     // Clone (copy) the course attributes
    //     $newCourse = $course->replicate();

    //     // Override anything you want (like new session_id)
    //     $newCourse->session_id = $request->session_id;

    //     // Save the new course
    //     $newCourse->save();

    //     return redirect()->route('exam.sub_components.index')
    //         ->with('success', 'SubComponent Cloned successfully');
    // }
}

