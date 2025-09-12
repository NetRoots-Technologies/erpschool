<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Academic\AcademicClass;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Admin\Course;
use App\Models\Admin\CourseType;
use App\Models\Exam\ClassSubject;
use App\Models\Fee\StudentFee;
use App\Models\Student\AcademicSession;
use App\Services\CourseServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CourseSampleExport;
use App\Imports\CourseExcelImport;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(CourseServices $CourseServices)
    {
        $this->CourseServices = $CourseServices;
    }
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $course_types = CourseType::where('status', 1)->get();
        $sessions = AcademicSession::all();
        return view('acadmeic.courses.index', compact('course_types', 'sessions'));
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
        $course_types = CourseType::where('status', 1)->get();
        $classes = AcademicClass::where('status', 1)->get();

        $formattedSessions = UserHelper::session_name();

        return view('acadmeic.courses.create', compact('course_types', 'classes', 'companies', 'formattedSessions'));

    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Users = $this->CourseServices->getdata();
        return $Users;
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

        $this->CourseServices->store($request);

        return redirect()->route('academic.subjects.index')
            ->with('success', 'Course added successfully');
        //return redirect()->route('admin.course.index');
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
        $course = Course::find($id);
        $course_types = CourseType::where('status', 1)->get();
        $classes = AcademicClass::where('status', 1)->get();
        $companies = Company::where('status', 1)->get();
        $branches = Branch::where('status', 1)->get();
        $formattedSessions = UserHelper::session_name();


        return view('acadmeic.courses.edit', compact('course', 'course_types', 'classes', 'formattedSessions', 'companies', 'branches'));
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


        $this->CourseServices->update($request, $id);

        return redirect()->route('academic.subjects.index')
            ->with('success', 'Course Updated successfully');
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

        $this->CourseServices->destroy($id);

        return redirect()->route('academic.subjects.index')
            ->with('success', 'Course Deleted successfully');
    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $coursetype = $this->CourseServices->changeStatus($request);
    }


    public function fetchAcademicClass(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $class = AcademicClass::where('branch_id', $request->branch_id)->get();
        return response()->json($class);
    }

    public function fetchAcademicClasses(Request $request)
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        $class = AcademicClass::where('branch_id', $request->branch_id)->get();
        return response()->json($class);
    }

    public function exportbulkfile()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Excel::download(new CourseSampleExport, 'Course_bulk_sample.xlsx');
    }

    public function importBulkFile(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            // Minimal check to avoid runtime errors
            if (!$request->hasFile('import_file')) {
                return back()->with('error', 'No file uploaded.');
            }

            Excel::import(new \App\Imports\CourseExcelImport, $request->file('import_file'));
            return back()->with('success', 'Courses imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $firstError = !empty($failures) && !empty($failures[0]->errors())
                ? $failures[0]->errors()[0]
                : 'Validation failed.';
            return back()->with('error', 'Import Failed: ' . $firstError);
        } catch (\Exception $e) {
            Log::error('Course Import Exception: ' . $e->getMessage());
            return back()->with('error', 'Import Failed: ' . $e->getMessage());
        }
    }

    public function fetchSubject(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Course::where('class_id', $request->id)->get();
    }

    public function clone(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'session_id' => 'required|exists:sessions,id',
        ]);

        // Find the original course
        $course = Course::findOrFail($request->course_id);

        // Clone (copy) the course attributes
        $newCourse = $course->replicate();

        // Override anything you want (like new session_id)
        $newCourse->session_id = $request->session_id;

        // Save the new course
        $newCourse->save();

        return redirect()->route('academic.subjects.index')
            ->with('success', 'Course Cloned successfully');
    }
}
