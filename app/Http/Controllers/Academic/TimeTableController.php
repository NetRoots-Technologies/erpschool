<?php

namespace App\Http\Controllers\Academic;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Academic\AcademicClass;
use App\Models\Academic\SchoolType;
use App\Models\Academic\TimeTable;
use App\Models\Admin\Company;
use App\Models\Student\AcademicSession;
use App\Services\TimeTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TimeTableController extends Controller
{

    protected $TimeTableService;
    public function __construct(TimeTableService $timeTableService)
    {
        $this->TimeTableService = $timeTableService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Timetable-list')) {
            return abort(503);
        }
        return view('acadmeic.timetable.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

          if (!Gate::allows('Timetable-create')) {
            return abort(503);
        }
      
        $sessions = UserHelper::session_name();
        $schools = SchoolType::where('status', 1)->get();
        $companies = Company::where('status', 1)->get();
        return view('acadmeic.timetable.create', compact('sessions', 'schools', 'companies'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        
          if (!Gate::allows('Timetable-create')) {
            return abort(503);
        }
      
        try {
            $this->TimeTableService->store($request);
            return redirect()->route('academic.timetables.index')->with('success', 'TimeTable created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the TimeTable: ' . $e->getMessage());
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
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
          if (!Gate::allows('Timetable-edit')) {
            return abort(503);
        }
        $timetable = TimeTable::with('company', 'branch', 'school', 'session')->find($id);

        $sessions = UserHelper::session_name();

        $schools = SchoolType::where('status', 1)->get();
        $companies = Company::where('status', 1)->get();
        return view('acadmeic.timetable.edit', compact('timetable', 'sessions', 'schools', 'companies'));
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
      if (!Gate::allows('Timetable-edit')) {
            return abort(503);
        }
        $this->TimeTableService->update($request, $id);
        return redirect()->route('academic.timetables.index')->with('success', 'TimeTable updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      if (!Gate::allows('Timetable-delete')) {
            return abort(503);
        }
        $this->TimeTableService->destroy($id);
        return redirect()->route('academic.timetables.index')->with('success', 'TimeTable Deleted successfully');

    }

    public function getData()
    {
       if (!Gate::allows('Timetable-list')) {
            return abort(503);
        }
        $timetable = $this->TimeTableService->getdata();
        return $timetable;
    }

}

