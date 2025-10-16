<?php

namespace App\Http\Controllers\HR;

use Exception;
use App\Models\HR\OverTime;
use App\Models\Admin\Branch;
use Illuminate\Http\Request;
use App\Models\HRM\Employees;
use App\Services\overTimeService;
use App\Http\Controllers\Controller;
use App\Helpers\GeneralSettingsHelper;
use Illuminate\Support\Facades\Gate;

class OvertimeController extends Controller
{
    protected $overTimeService;

    public function __construct(overTimeService $overTimeService)
    {
        $this->overTimeService = $overTimeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Overtime-list')) {
            return abort(503);
        }
        return view('hr.overtime_management.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('Overtime-create')) {
            return abort(503);
        }
        $employees = Employees::where('status', 1)->get();
        $branches = Branch::where('status', 1)->get();
        return view('hr.overtime_management.create', compact('employees', 'branches'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('Overtime-create')) {
            return abort(503);
        }
        //        try {
        $this->overTimeService->store($request);
        return redirect()->route('hr.overtime.index')->with('success', 'Overtime Assigned');
        //        } catch (\Exception $e) {
        //            return redirect()->route('hr.overtime.index')->with('error', 'An error occurred while Assigning Overtime');
        //        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('Overtime-list')) {
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
        if (!Gate::allows('Overtime-edit')) {
            return abort(503);
        }
        $overtime = OverTime::find($id);
        $employees = Employees::where('status', 1)->get();
        $branches = Branch::where('status', 1)->get();
        return view('hr.overtime_management.edit', compact('employees', 'branches', 'overtime'));
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
        if (!Gate::allows('Overtime-edit')) {
            return abort(503);
        }
        try {
            $this->overTimeService->update($request, $id);
            return redirect()->route('hr.overtime.index')->with('success', 'Overtime Assigned');
        } catch (\Exception $e) {
            return redirect()->route('hr.overtime.index')->with('error', 'An error occurred while Assigning Overtime');
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
        if (!Gate::allows('Overtime-delete')) {
            return abort(503);
        }
        $this->overTimeService->destroy($id);
        return redirect()->route('hr.overtime.index')->with('success', 'Overtime deleted');
    }


    public function overtimeData(Request $request)
    {
        if (!Gate::allows('Overtime-list')) {
            return abort(503);
        }
        $data = $request->all();
        $branch_id = $data['branch_id'];
        $department_id = $data['department_id'];
        $employee_id = $data['employee_id'];


        $overtimeHourPrice = GeneralSettingsHelper::getSetting('overtime_price_per_hour');

        $calculate_overtime = GeneralSettingsHelper::getSetting('hours_to_calculate_overtime_after_for_per_hour');


        if ($employee_id) {
            $employee = Employees::find($employee_id);
            return view('hr.overtime_management.data', compact('employee', 'overtimeHourPrice', 'calculate_overtime'));
        } else {
            $employees = Employees::where('branch_id', $branch_id)->orWhere('department_id', $department_id)->get();
            return view('hr.overtime_management.data', compact('employees', 'overtimeHourPrice', 'calculate_overtime'));
        }
    }

    public function getdata()
    {
        if (!Gate::allows('Overtime-list')) {
            return abort(503);
        }
        return $this->overTimeService->getdata();
    }

    public function report(Request $request)
    {
        if (!Gate::allows('Overtime-list')) {
            return abort(503);
        }
        $overtime = OverTime::with('employee', 'branch')->orderBy('created_at', 'desc');

        if ($request->month) {
            $month = $request->month;

            $yearMonth = date('Y-m', strtotime($month));

            $overtime->whereDate('end_date', 'like', "{$yearMonth}%");
        }

        return $overtime->get();
    }

    public function reportView()
    {
        if (!Gate::allows('Overtime-list')) {
            return abort(503);
        }
        $currentMonth = now()->format('Y-m');
        return view('hr.overtime_management.report', compact('currentMonth'));
    }
}

