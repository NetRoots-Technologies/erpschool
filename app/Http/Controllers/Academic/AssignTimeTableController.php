<?php

namespace App\Http\Controllers\Academic;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Academic\AssignTimeTable;
use App\Models\Academic\ClassTimeTable;
use App\Models\Academic\TimeTable;
use App\Models\Admin\Company;
use App\Models\HR\Designation;
use App\Models\HRM\Employees;
use App\Services\AssignTimeTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AssignTimeTableController extends Controller
{
    protected $AssignTimeTableService;
    public function __construct(AssignTimeTableService $assignTimeTableService)
    {
        $this->AssignTimeTableService = $assignTimeTableService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('AssignTimetable-list')) {
            abort(403);
        }
        return view('acadmeic.assign_timetable.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('AssignTimetable-create')) {
            abort(403);
        }
        $companies = Company::where('status', 1)->get();
        $designations = Designation::where('name', 'Teacher')->pluck('id');
        $sessions = UserHelper::session_name();
        if ($designations->isNotEmpty()) {

            foreach ($designations as $item) {
                $teachers = Employees::where('designation_id', $item)->pluck('name', 'id');
            }
            return view('acadmeic.assign_timetable.create', compact('companies', 'teachers', 'sessions'));
        } else {
            return redirect()->route('academic.assign_timetable.index')->with('error', 'Create a designation for teachers before proceeding');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       if (!Gate::allows('AssignTimetable-create')) {
            abort(403);
        }
        $this->AssignTimeTableService->store($request);
        return redirect()->route('academic.assign_timetable.index')->with('success', 'Timetable assigned successfully');

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
        if (!Gate::allows('AssignTimetable-edit')) {
            abort(403);
        }
        $assignTable = AssignTimeTable::with('classTimeTable')->find($id);
        $companies = Company::where('status', 1)->get();
        $designations = Designation::where('name', 'Teacher')->pluck('id');
        $sessions = UserHelper::session_name();
        foreach ($designations as $item) {
            $teachers = Employees::where('designation_id', $item)->pluck('name', 'id');
        }
        return view('acadmeic.assign_timetable.edit', compact('teachers', 'assignTable', 'companies','sessions'));

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
         if (!Gate::allows('AssignTimetable-edit')) {
            abort(403);
        }
        $this->AssignTimeTableService->update($request, $id);
        return redirect()->route('academic.assign_timetable.index')->with('success', 'Timetable updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         if (!Gate::allows('AssignTimetable-delete')) {
            abort(403);
        }
        $this->AssignTimeTableService->destroy($id);
        return redirect()->route('academic.assign_timetable.index')->with('success', 'Timetable Deleted successfully');
    }

    public function getData()
    {
         if (!Gate::allows('AssignTimetable-list')) {
            abort(403);
        }
        return $this->AssignTimeTableService->getdata();
    }

    public function fetch_courseTimeTable(Request $request)
    {
         if (!Gate::allows('AssignTimetable-list')) {
            abort(403);
        }
        $courses = ClassTimeTable::with('Timetable')
            ->where('course_id', $request->course_id)
            ->get();

        $time = [];

        foreach ($courses as $course) {
            if ($course->Timetable) {
                $time[] = $course->Timetable;
            }
        }

        return response()->json($time);
    }

}

