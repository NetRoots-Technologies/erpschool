<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Admin\Branch;
use App\Models\Admin\Departments;
use App\Models\HRM\Employees;
use App\Services\HolidayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HolidayController extends Controller
{
    public function __construct(HolidayService $holidayService)
    {
        $this->HolidayService = $holidayService;
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
        return view('hr.holidays.index');
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
        $branches = Branch::where('status', 1)->get();
        return view('hr.holidays.create', compact('branches'));

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
        $this->HolidayService->store($request);


        return redirect()->route('hr.holidays.index')
            ->with('success', 'Holiday created successfully');
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
        $branches = Branch::where('status', 1)->get();
        $holidays = $this->HolidayService->edit($id);

        return view('hr.holidays.edit', compact('holidays', 'branches'));

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
        $this->HolidayService->update($request, $id);

        return redirect()->route('hr.holidays.index')
            ->with('success', 'Holiday updated successfully');
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
        $this->HolidayService->destroy($id);

        return redirect()->route('hr.holidays.index')
            ->with('success', 'Holiday deleted successfully');
    }


    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $holiday = $this->HolidayService->getdata();
        return $holiday;
    }

    public function holidayDepartment(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $departments = Departments::where('branch_id', $request->branch_id)->get();

        $html = '<option>Select Department</option><br><option value="0">All Departments</option>';

        foreach ($departments as $department) {

            $selectedDepartment = ($request->department_id != null && $request->department_id == $department->id) ? 'selected' : '';

            $html .= '<option value="' . $department->id . '" ' . $selectedDepartment . '>' . $department->name . '</option>';
        }

        return response()->json(['html' => $html]);
    }



    public function holidayEmployee(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $employees = Employees::where('status', 1)->where('department_id', $request->department_id)->get();

        $html = '<option>Select Employee</option><br><option value="0">All Employees</option>';
        foreach ($employees as $employee) {

            $selectedEmployee = ($request->employee_id != null && $request->employee_id == $employee->id) ? 'selected' : '';

            $html .= '<option value="' . $employee->id . '" ' . $selectedEmployee . '>' . $employee->name . '</option>';
        }

        return response()->json(['html' => $html]);
    }


}
