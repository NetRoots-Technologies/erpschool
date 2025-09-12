<?php

namespace App\Http\Controllers\Academic;

use App\Models\Admin\Course;
use Illuminate\Http\Request;
use App\Models\Admin\Company;
use App\Models\Academic\Section;
use App\Models\Admin\CourseType;
use App\Models\Academic\TimeTable;
use App\Services\ClassTimeService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Academic\AcademicClass;
use App\Models\Academic\ClassTimeTable;
use App\Models\Student\AcademicSession;

class ClassTimeTableController extends Controller
{
    protected $ClassTimeService;
    protected $gate;
    public function __construct(ClassTimeService $classTimeService,Gate $gate)
    {
        $this->ClassTimeService = $classTimeService;
        $this->gate = $gate;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if (!Gate::allows('ClassTimetable-list')) {
        abort(403);
    }
        return view('acadmeic.class_timetable.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       if (!Gate::allows('ClassTimetable-create')) {
        abort(403);
    }
        $courses = Course::where('status', 1)->get();
        $course_types = CourseType::where('status', 1)->get();
        $classes = AcademicClass::where('status', 1)->get();
        $formattedSessions = AcademicSession::where('status', 1)->get();

        $sessions = [];

        foreach ($formattedSessions as $session) {
            $sessions[$session->id] = $session->name . ' ' . date('y', strtotime($session->start_date)) . '-' . date('y', strtotime($session->end_date));
        }

        //  dd($course_type);
        $timetables = TimeTable::all();
        $companies = Company::where('status', 1)->get();

        return view('acadmeic.class_timetable.create', compact('courses', 'course_types', 'classes', 'companies', 'sessions', 'timetables'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

           if (!Gate::allows('ClassTimetable-create')) {
        abort(403);
    }
        $this->ClassTimeService->store($request);

        return redirect()->route('academic.class_timetable.index')->with('success', 'Class Time Table created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
          if (!Gate::allows('ClassTimetable-edit')) {
        abort(403);
    }
        $courses = Course::where('status', 1)->get();
        $course_types = CourseType::where('status', 1)->get();
        $classes = AcademicClass::where('status', 1)->get();

        $classTime = ClassTimeTable::with('Timetable')->find($id);
        $formattedSessions = AcademicSession::where('status', 1)->get();

        $sessions = [];

        foreach ($formattedSessions as $session) {
            $sessions[$session->id] = $session->name . ' ' . date('y', strtotime($session->start_date)) . '-' . date('y', strtotime($session->end_date));
        }
        $timetables = TimeTable::all();
        $companies = Company::where('status', 1)->get();

        return view('acadmeic.class_timetable.edit', compact('sessions', 'classTime', 'courses', 'course_types', 'classes', 'companies', 'timetables'));

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
         if (!Gate::allows('ClassTimetable-edit')) {
        abort(403);
    }

        $this->ClassTimeService->update($request, $id);

        return redirect()->route('academic.class_timetable.index')->with('success', 'Class Time Table Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         if (!Gate::allows('ClassTimetable-delete')) {
        abort(403);
    }
        $this->ClassTimeService->destroy($id);

        return redirect()->route('academic.class_timetable.index')->with('success', 'Class Time Table Deleted successfully');
    }

    public function fetchAcademicBranch(Request $request)
    {

        $academicBranches = TimeTable::with('branch')->where('id', $request->timetable_id)->first();
        return $academicBranches->branch;
    }

    public function fetchAcademicSection(Request $request)
    {
        
        $sections = Section::where('class_id', $request->class_id)->get();
        return $sections;
    }

    public function fetchAcademicCourse(Request $request)
    {
        
        $courses = Course::where('class_id', $request->class_id)->get();
        return $courses;
    }
    public function fetchAcademicTime(Request $request)
    {
        
        $timetable = TimeTable::where('branch_id', $request->branch_id)->get();
        return $timetable;
    }

    public function getData()
    {
        
        return $this->ClassTimeService->getdata();
    }





}
