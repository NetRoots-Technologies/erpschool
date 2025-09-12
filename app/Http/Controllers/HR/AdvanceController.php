<?php

namespace App\Http\Controllers\HR;

use Carbon\Carbon;
use App\Models\HR\Advance;
use App\Models\Admin\Groups;
use Illuminate\Http\Request;
use App\Models\Admin\Ledgers;
use App\Models\HRM\Employees;
use App\Services\LedgerService;
use App\Services\AdvanceService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\GeneralSettingsHelper;
use Illuminate\Support\Facades\Gate;

class AdvanceController extends Controller
{
    protected $advanceService;
    protected $ledgerService;
    public function __construct(AdvanceService $advanceService, LedgerService $ledgerService)
    {
        $this->advanceService = $advanceService;
        $this->ledgerService = $ledgerService;
    }

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('hr.advances.index');
    }

    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $yearAgo = GeneralSettingsHelper::getSetting('employeeTime');
        $inputYear = intval($yearAgo['year']);
        $oneYearAgo = Carbon::now()->subYear($inputYear)->toDateString();

        if (Auth()->user()->hasRole('Admin')) {
            $employees = Employees::whereDate('start_date', '<=', $oneYearAgo)->get();
        } else {
            $employees = Employees::whereDate('start_date', '<=', $oneYearAgo)
                ->where('id', auth()->id())
                ->get();
        }
        $durations = config('advances.duration');
        return view('hr.advances.create', compact('employees', 'durations'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            if (!Auth::user()->hasRole('Admin')) {
                $advance = Advance::findOrFail($request->get('employee_id'));
                if ($advance->remaining_amount != 0 && $advance->amount_status == 'unpaid') {
                    return redirect()->back()->with('error', 'Employee cannot apply for a new advance');
                }
            }
            $employees = Employees::find($request->get('employee_id'));
            if ($employees) {
                $advance_salary = config('constants.FixedGroups.advance_salary');

                $empLedger = Ledgers::where("parent_type", Employees::class)->where('parent_type_id', $employees->id)->first();

                if (!$empLedger) {

                    $group = Groups::find($advance_salary);

                    // $data["name"] = $employees->name . " Advance ";
                    // $data["parent_type"] = Employees::class;
                    // $data["parent_id"] = $employees->id;
                    // $data["parent_type_id"] = $employees->id;
                    // $data["group_id"] = $group->id;
                    // $data["group_number"] = $group->number;
                    // $data["account_type_id"] = $group->account_type_id;
                    // $data["balanceType"] = "d";
                    // $data["branch_id"] = $employees->branch_id;

                    // $this->ledgerService->createLedger($data);

                    $this->ledgerService->createAutoLedgers([$group->id] ,$employees->name . " Advance ", $employees->branch_id, Advance::class, $employees->id );

                }
            }


            $this->advanceService->store($request);
            return redirect()->route('hr.advances.index')->with('success', 'Advance created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the Advance');
        }
    }

    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $advance = Advance::find($id);

        $yearAgo = GeneralSettingsHelper::getSetting('employeeTime');
        $inputYear = intval($yearAgo['year']);
        $oneYearAgo = Carbon::now()->subYear($inputYear)->toDateString();

        if (Auth::user()->hasRole('Admin')) {
            $employees = Employees::whereDate('start_date', '<=', $oneYearAgo)->get();
        } else {
            $employees = Employees::whereDate('start_date', '<=', $oneYearAgo)
                ->where('id', Auth::id())
                ->get();
        }

        $durations = config('advances.duration');
        return view('hr.advances.edit', compact('employees', 'durations', 'advance'));
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            $this->advanceService->update($request, $id);
            return redirect()->route('hr.advances.index')->with('success', 'Advance Updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while Updating the Advance');
        }
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->advanceService->getData();
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->advanceService->destroy($id);

        return redirect()->route('hr.advances.index')
            ->with('success', 'Advance deleted successfully');
    }

    public function getEmployeeSalary(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $employee = Employees::find($request->emp_id);
        $employeeSalary = $employee->salary;

        $startYear = Carbon::parse($employee->start_date)->year;
        $currentYear = Carbon::now()->year;
        $yearsOfService = $currentYear - $startYear;
        $salaryTime = GeneralSettingsHelper::getSetting('advanceSalaryTime');

        if ($yearsOfService >= 3) {
            $employeeSalary *= $salaryTime['salary'];
        } elseif ($yearsOfService >= 2) {
            $employeeSalary *= 2;
        } else {
            $employeeSalary;
        }

        return response()->json($employeeSalary);
    }

}
