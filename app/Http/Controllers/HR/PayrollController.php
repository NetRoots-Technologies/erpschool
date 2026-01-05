<?php

namespace App\Http\Controllers\HR;

use Exception;
use Carbon\Carbon;
use App\Models\HR\Eobi;
use App\Helpers\TaxHelper;
use App\Models\HR\Advance;
use App\Models\HR\Holiday;
use App\Models\HR\Payroll;
use App\Models\HR\TaxSlab;
use App\Models\HR\OverTime;
use App\Models\Admin\Branch;
use Illuminate\Http\Request;
use App\Models\HR\Attendance;
use App\Models\HR\ProfitFund;
use App\Models\HRM\Employees;
use App\Helpers\PayrollHelper;
use App\Models\HR\LeaveRequest;
use App\Models\HR\EmployeeWelfare;
use App\Models\HR\PayrollApproval;
use Illuminate\Support\Facades\DB;
use App\Models\HR\MedicalAllowance;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Helpers\GeneralSettingsHelper;
use App\Models\Admin\Bank;
use App\Models\Admin\BankAccount;
use App\Models\Accounts\AccountLedger;
use App\Models\Accounts\AccountGroup;
use App\Models\Admin\Department;
use App\Services\LedgerService;

class PayrollController extends Controller
{
    protected $ledgerService;
    
    public function __construct(LedgerService $ledgerService)
    {
        $this->ledgerService = $ledgerService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       if (!Gate::allows('Payroll-list')) {
            return abort(503);
        }

        $timestamp = time();
        $branches = Branch::where('status', 1)->get();
        $bank_accounts = BankAccount::where('type', 'MOA')->pluck('id');
        $ledgers = AccountLedger::where('linked_module', BankAccount::class)->whereIn('linked_id', $bank_accounts)->get();

        return view('hr.payroll.index', compact('branches', 'ledgers'));

    }

    public function GeneratePayrollFilterData(Request $request)
    {

      
       if (!Gate::allows('Payroll-list')) {
            return abort(503);
        }

        if (isset($request['month_year']) && $request['month_year'] !== null) {
            $timestamp = strtotime($request['month_year']);
        } else {
            $timestamp = time();
        }

        $current_date = date('Y-m-d', $timestamp);
        $first_second = date('Y-m-24', $timestamp);
        $first_second_for_increment = strtotime($first_second);

        if ($current_date <= $first_second) {
            $start_date = date('Y-m-25', strtotime('-1 month', $first_second_for_increment));
            $end_date = $first_second;
        } else {
            $start_date = $first_second;
            $end_date = date('Y-m-24', strtotime('+1 month', $first_second_for_increment));
        }

        $branch_id = $request['branch_id'] ?? "";

        if (isset($request['month_year']) && $request['month_year'] !== null) {
            $monthYear = $request['month_year'];
            $generatedMonth = Carbon::createFromFormat('Y-m', $monthYear)->format('F Y');
            $generated_month_year = $request['month_year'];
        } else {
            $generatedMonth = '';
            $generated_month_year = '';
        }

        if (isset($request['department_id']) && $request['department_id'] !== null) {
            $department_id = $request['department_id'];
        } else {
            $department_id = '';
        }

        if (isset($request['hrm_employee_id']) && $request['hrm_employee_id'] !== null) {
            $hrm_employee_id = $request['hrm_employee_id'];
        } else {
            $hrm_employee_id = '';
        }

        return [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'branch_id' => $branch_id,
            'generated_month' => $generatedMonth,
            'department_id' => $department_id,
            'hrm_employee_id' => $hrm_employee_id,
            'generated_month_year' => $generated_month_year,
        ];

    }

    public function GeneratePayroll(Request $request)
    {


      

        if (!Gate::allows('Payroll-list')) {
            return abort(503);
        }

        
        if (isset($request['month_year']) && $request['month_year'] !== null) {
            $timestamp = strtotime($request['month_year']);
        } else {
            $timestamp = time();
        }

    


        $year = date('Y', $timestamp);
        $month = date('m', $timestamp);
        $date = date('F Y', $timestamp);
        $current_date = date('Y-m-d', $timestamp);


        $first_second = date('Y-m-24', $timestamp);
        $first_second_for_increment = strtotime($first_second);


       


        if ($current_date <= $first_second) {
            $start_date = date('Y-m-25', strtotime('-1 month', $first_second_for_increment));
            $end_date = $first_second;
        } else {
            $start_date = $first_second;
            $end_date = date('Y-m-24', strtotime('+1 month', $first_second_for_increment));
        }



        $dates_array = [];
        $current_month = date('F Y', $timestamp);
        for ($current_date = strtotime($start_date); $current_date <= strtotime($end_date); $current_date = strtotime('+1 day', $current_date)) {
            $day = date('Y-m-d', $current_date);
            $dates_array[] = $day;
        }

        
        $currentMonth = count($dates_array);
        
        $totalHours = PayrollHelper::getTotalHoursInMonth($currentMonth);
        
        
        $employees_data = [];

        $employees = Employees::where('status', 1)->where('salary', '>', 0)->with('workShifts.workdays');
        
        if ((isset($request['department_id']) && $request['department_id'] != null)) {
            $employees->where('department_id', $request['department_id']);
        }

        if ((isset($request['hrm_employee_id']) && $request['hrm_employee_id'] != null)) {
            $employees->where('id', $request['hrm_employee_id']);
        }

        if ((isset($request['branch_id']) && $request['branch_id'] != null)) {
            $employees->where('branch_id', $request['branch_id']);
        }

        $salaries = [];
        $total = [];
        $employees = $employees->get();

        $gracePeriod = GeneralSettingsHelper::getGeneral('grace_period');

        $committedTime = array();
        $overtime = '';
        $overtime_time = 0;
        foreach ($employees as $employee) {

            $perMinute = 0;

            if ($employee->job_seeking == 'visitingLecturer') {

                $salaries[$employee->id] = $employee->hour_salary;

                if ($employee->working_hour != null && $employee->hour_salary != null) {
                    $visitingHour = $employee->working_hour;
                    $perMinute = $visitingHour * 60;
                    $committedTime[$employee->id] = $perMinute;
                    $perMinute = round($employee->hour_salary / $perMinute, 2);
                    $total[$employee->id] = $perMinute;
                }
            } else {
                $salaries[$employee->id] = $employee->salary;
                foreach ($totalHours as $workShiftId => $totalHour) {
                    if ($employee->work_shift_id == $workShiftId) {
                        $committedTime[$employee->id] = $totalHour;
                      
                        $perMinute = $totalHour;
                        $hourlyRatePerMinute = $employee->salary / $perMinute;
                        $total[$employee->id][$workShiftId] = $hourlyRatePerMinute;
                    }
                }
            }
            
            $employeeSalary = Employees::where('status', 1)->where('id', $employee->id)->pluck('salary')->first();

            $count_data['day_off'] = 0;
            $count_data['present'] = 0;
            $count_data['absent'] = 0;
            $count_data['leave'] = 0;
            $count_data['late'] = 0;

            $employee_data = [
                'id' => $employee->id,
                'name' => $employee->name,
                'attendance' => [],
                'worked' => [],
            ];
            $employee_data['employeeSalary'] = $employee->salary;
            $employee_shift = $employee->workShifts;

            if (!$employee_shift) {
                return response()->json([
                    'error' => 'No work shift found for this employee',
                ], 401);
            }

            $startTime = $employee_shift->start_time ?? '';
            $endTime = $employee_shift->end_time;
            $work_days = $employee_shift->workdays;
            $totalLateCounts = 0;
            foreach ($dates_array as $date) {
                $leave_dates = [];
                $present = false;
                $absent = false;
                $leave = false;
                $late = false;
                $totalMinutesWorked = 0;

                $day = date('D', strtotime($date));
                $offDay = false;

                if (now() >= date('Y-m-d', strtotime($date))) {
                    if ($work_days[$day] == "1") {
                        $attendance = Attendance::where('employee_id', $employee->id)
                        ->whereDate('attendance_date', $date)
                        ->first();
                       
                        if ($attendance) {
                            if ($attendance->timeIn && $attendance->timeOut) {
                                $checkin_time = $attendance->timeIn;
                                $checkout_time = $attendance->timeOut;
                                $present = true;
                                $late_Overtime = GeneralSettingsHelper::calculateLateAndOvertime(
                                    $startTime,
                                    $endTime,
                                    $checkin_time,
                                    $checkout_time,
                                    $gracePeriod,
                                    $date,
                                );
                                $lateAndOvertime = $late_Overtime['total_hours_worked'];
                                $late_come = $late_Overtime['late'];
                                $overtime = $late_Overtime['overtime'];
                                $overtime_time = $late_Overtime['overtime_time'];

                                if ($late_come == true) {
                                    $late = true;
                                } else {
                                    $late = false;
                                }


                            }
                        } else {

                            $present = false;
                            $absent = true;


                            if ($offDay == false) {
                                $leave_status = LeaveRequest::where('hrm_employee_id', $employee->id)
                                    ->where('hr_approved', 1)
                                    ->where(function ($query) use ($date) {
                                        $query->whereDate('start_date', '<=', $date)
                                            ->whereDate('end_date', '>=', $date);
                                    })
                                    ->first();

                                if ($leave_status) {
                                    $leave = true;
                                }

                                $holidays = Holiday::whereDate('holiday_date', '<=', $date)
                                    ->whereDate('holiday_date_to', '>=', $date)->get();
                                $holidays = $holidays->whereIn('branch_id', [0, $employee->branch_id]);
                                $holidays = $holidays->whereIn('department_id', [0, $employee->department_id]);
                                $holidays = $holidays->whereIn('employee_id', [0, $employee->id]);

                                if ($holidays->isNotEmpty()) {
                                    $offDay = true;
                                }
                            }
                        }

                    } else {
                        $offDay = true;
                        $present = false;
                        $absent = false;
                        $leave = false;
                    }
                }
                
                if ($present == true) {
                    $totalHoursWorked = $lateAndOvertime;
                    list($hours, $minutes) = sscanf($totalHoursWorked, "%d hrs : %dmins");
                    $totalMinutesWorked = $hours * 60 + $minutes;
                    $count_data['present'] += $totalMinutesWorked;
                }
                if ($offDay == true && !($employee->job_seeking == 'visitingLecturer')) {
                    $shift_start_time = Carbon::createFromFormat('H:i', $startTime);
                    $shift_end_time = Carbon::createFromFormat('H:i', $endTime);
                    $totalTime = $shift_start_time->diffInMinutes($shift_end_time);
                    $totalMinutesWorked += $totalTime;
                    $count_data['day_off'] += $totalMinutesWorked;
                }

                // dd( $totalMinutesWorked ); // 0
                if ($leave == true) {
                    $shift_start_time = Carbon::createFromFormat('H:i', $startTime);
                    $shift_end_time = Carbon::createFromFormat('H:i', $endTime);
                    $leave_total_time = $shift_start_time->diffInMinutes($shift_end_time);
                    $totalMinutesWorked += $leave_total_time;
                    $count_data['leave'] += $totalMinutesWorked;
                }
                
                $employee_data['attendance'][$date] = [
                    'present' => $present,
                    'absent' => $absent,
                    'leave' => $leave,
                    'offDay' => $offDay,
                    'late' => $late,
                ];

                $employee_data['worked'][$date] = [
                    'totalMinuteWorked' => $totalMinutesWorked,
                ];
            }

            $employee_data['totalWorked'] = 0;
            foreach ($employee_data['worked'] as $emp_Work) {
                if ($emp_Work['totalMinuteWorked'] != 0) {
                    $employee_data['totalWorked'] += $emp_Work['totalMinuteWorked'];
                }
            }
            
            $employee_data['data'] = $count_data;

            $eobi = Eobi::where('employee_id', $employee->id)->first();
            $eobiValue = $eobi ? $eobi->employee_percent : 0;

            $providentFund = ProfitFund::where('employee_id', $employee->id)->where('status', 1)->first();
            $providentFundValue = $providentFund ? $providentFund->providentFund : 0;

            $sideValues = [
                'eobiValue' => $eobiValue,
                'providentFundValue' => $providentFundValue,
            ];

            $medicalAllowance = MedicalAllowance::where('employee_id', $employee->id)->first();

            $medicalAllowanceValue = $medicalAllowance ? $medicalAllowance->medicalAllowance : 0;

            $employee_data['medicalAllowance'] = $medicalAllowanceValue;

            $advances = Advance::where('employee_id', $employee->id)
                ->whereDate('effective_from', '<=', $date)
                ->where('status', 1)->where('amount_status', 'unpaid')->get();

            $advanceInstallment = 0;

            foreach ($advances as $advance) {
                if ($advance->remaining_amount != 0) {
                    $advanceInstallment += $advance->installmentAmount;
                }
            }

            $employee_data['advanceInstallment'] = $advanceInstallment;

            $employee_data['sideValues'] = $sideValues;

            $totalValue = $eobiValue + $providentFundValue;

            $employee_data['totalValues'] = $totalValue;

            $totalMoney = [];

            foreach ($total as $key => $item) {
                if (is_array($item) && count($item) > 0) {
                    $totalMoney[$key] = reset($item);
                } else {
                    $totalMoney[$key] = $item;
                }
            }

            $employee_data['perMinuteSalary'] = $totalMoney[$employee->id];
            $calculatedSalary = round($employee_data['totalWorked'] * $totalMoney[$employee->id], 0);

            $employee_data['calculatedSalary'] = $calculatedSalary;

            $netSalary_afterFund_amount = $employee_data['calculatedSalary'] - $totalValue;

            $netSalary_afterInstallment = $netSalary_afterFund_amount - $advanceInstallment;

            // $netSalary_afterInstallment = $netSalary_afterInstallment > 0 ? $netSalary_afterInstallment : 0;

            if ($calculatedSalary != 0) {
                $netSalary = $netSalary_afterInstallment + (float) $medicalAllowanceValue;
            } else {
                $netSalary = $netSalary_afterInstallment;
            }

            $currentMonth = date('m');
            $currentYear = date('Y');
            $firstDayOfMonth = date('Y-m-15', strtotime("first day of $currentYear-$currentMonth"));
            $lastDayOfMonth = date('Y-m-24', strtotime("last day of $currentYear-$currentMonth"));

            if ($date >= $firstDayOfMonth && $date <= $lastDayOfMonth) {
                if ($employee->start_date >= $firstDayOfMonth && $employee->start_date <= $lastDayOfMonth) {
                    $employeeAREA = Employees::findOrFail($employee->id);
                    if ($employeeAREA) {
                        $employeeAREA->update([
                            'areas' => $netSalary,
                        ]);
                    }
                }
            }

            $employee_data['lateJoin'] = 0;
            $employee_area = Employees::findOrFail($employee->id);
            if ($date > $lastDayOfMonth) {
                if ($employee->areas !== null && $employee->areas !== 0) {
                    $employee_data['lateJoin'] = $employee_area->areas;
                    $netSalary += $employee_area->areas;

                }
            }

            $tax_slabs = TaxSlab::all();

            $totalTax = TaxHelper::calculateTax($employeeSalary, $tax_slabs);

            $employee_data['Tax Salary'] = $totalTax;

            if ($totalTax) {
                $netSalary = $netSalary - $totalTax;
            }
            $over_time_price = 0;
            if ($overtime == true) {
                $overtime = OverTime::where('employee_id', $employee->id)
                    ->first();

                $over_time_price = $overtime_time * $overtime->total;
            }
            if ($over_time_price > 0) {
                $netSalary = $netSalary + $over_time_price;
            }

            if ($netSalary < 0) {
                $netSalary = 0;
            }

            $employee_data['totalSalary'] = $netSalary;

            $employee_data['committedTime'] = $committedTime[$employee->id];

            $employees_data[$employee->id] = $employee_data;


        }
        // here
        return view('hr.payroll.payrollData')->with([
            'employees_data' => $employees_data,
            'data' => $request->all(),
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    public function store(Request $request)
    {

        // dd($request->all());
        if (!Gate::allows('Payroll-list')) {
            return abort(503);
        }

        DB::beginTransaction();
        try {

            $data = $request->all();

            $payrollApprovals = PayrollApproval::with('payroll')
                ->whereIn('approved', [0, 1])
                ->whereHas('payroll', function ($query) use ($data) {
                    $query->where('employee_id', $data['employee_id'])
                        ->whereDate('start_date', $data['start_date'])
                        ->whereDate('end_date', $data['end_date']);
                })
                ->get();
            // dd( $payrollApprovals , $data);
            if ($payrollApprovals->isNotEmpty()) {
                $employeeNames = [];

                foreach ($payrollApprovals as $payrollApproval) {
                    $payrolls = $payrollApproval->payroll;

                    foreach ($payrolls as $payroll) {
                        if ($payroll && $payroll->employee) {
                            $employeeName = $payroll->employee->name;
                            $employeeNames[] = $employeeName;
                        }
                    }
                }

                $employeeNamesString = implode(', ', $employeeNames);
                return response()->json(['error' => "Payroll for employee(s) $employeeNamesString already exists."], 422);
            }
           
            // Get default bank account ledger if not provided
            if (empty($data['bank_account_ledger'])) {
                $bank_accounts = BankAccount::where('type', 'MOA')->get();
                
                if ($bank_accounts->isEmpty()) {
                    return response()->json([
                        'error' => 'No bank account found. Please create a bank account (Type: MOA) first in Admin → Bank Accounts.',
                        'message' => 'Bank account configuration required'
                    ], 422);
                }
                
                // Try to find existing ledger
                $bank_account_ids = $bank_accounts->pluck('id');
                $default_ledger = AccountLedger::where('linked_module', BankAccount::class)
                    ->whereIn('linked_id', $bank_account_ids)
                    ->where('is_active', true)
                    ->first();
                
                // If no ledger found, auto-create for first MOA bank account
                if (!$default_ledger) {
                    $first_bank_account = $bank_accounts->first();
                    
                    // Get or create Bank Accounts group
                    $assetsId = AccountGroup::where('name', 'Assets')->orWhere('code', 'AST-000')->value('id');
                    $bankGroup = AccountGroup::firstOrCreate(
                        ['name' => 'Bank Accounts'],
                        [
                            'code' => 'AST-BANK-' . time(),
                            'parent_id' => AccountGroup::where('name', 'Current Assets')->value('id') ?? $assetsId,
                            'description' => 'Bank accounts under current assets',
                            'is_active' => 1,
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                        ]
                    );
                    
                    // Create ledger for the bank account
                    $default_ledger = AccountLedger::create([
                        'name'                  => 'Bank - ' . $first_bank_account->account_no,
                        'code'                  => 'BANK-' . str_pad($first_bank_account->id, 4, '0', STR_PAD_LEFT) . '-' . time(),
                        'description'           => 'Ledger for bank account #' . $first_bank_account->account_no,
                        'account_group_id'      => $bankGroup->id,
                        'opening_balance'       => 0,
                        'opening_balance_type'  => 'debit',
                        'current_balance'       => 0,
                        'current_balance_type'  => 'debit',
                        'currency_id'           => 1, // PKR
                        'is_active'             => 1,
                        'is_system'             => 0,
                        'linked_module'         => BankAccount::class,
                        'linked_id'             => $first_bank_account->id,
                        'branch_id'             => $data['branch_id'] ?? null,
                        'created_by'            => auth()->id(),
                        'updated_by'            => auth()->id(),
                    ]);
                    
                    \Log::info("Auto-created bank account ledger for Bank Account ID: {$first_bank_account->id}");
                }
                
                $data['bank_account_ledger'] = $default_ledger->id;
            }
            
            // Validate that the provided ledger exists and is active
            if (!empty($data['bank_account_ledger'])) {
                $ledger = AccountLedger::where('id', $data['bank_account_ledger'])
                    ->where('is_active', true)
                    ->first();
                if (!$ledger) {
                    return response()->json([
                        'error' => 'Selected bank account ledger is invalid or inactive. Please select a valid bank account.',
                        'message' => 'Invalid bank account ledger'
                    ], 422);
                }
            }

            $payrollApproval = PayrollApproval::create([
                'hrm_employee_id' => $data['hrm_employee_id'],
                'branch_id' => $data['branch_id'],
                'generated_month_year' => $data['generated_month_year'],
                'generated_month' => $data['generated_month'],
                'department_id' => $data['department_id'],
                'bank_account_ledger' => $data['bank_account_ledger'],
            ]);

            $keys = ['eobi_provident_fund', 'provident_fund'];
            $sideValues = $data['side_values'];

            foreach ($data['employee_id'] as $key => $employeeId) {
                $payroll = new Payroll();

                $employee = Employees::findOrFail($employeeId);

                if ($employee->areas !== null && $employee->areas !== 0) {
                    $payroll->late_join = 1;
                }

                $employee->update([
                    'areas' => 0,
                ]);

                $payroll->employee_id = $employeeId;
                $payroll->committedTime = $data['committedTime'][$key];
                $payroll->start_date = $data['start_date'];
                $payroll->end_date = $data['end_date'];
                $payroll->advance = $data['advanceInstallment'][$key];
                $payroll->payroll_approval_id = $payrollApproval->id;
                $payroll->salary_per_minute = 0;
                $payroll->total_working_hours = $data['total_worked'][$key];
                $payroll->total_fund_amount = $data['total_values'][$key];
                $payroll->medicalAllowance = $data['medicalAllowance'][$key];
                $payroll->net_salary = $data['total_salary'][$key];
                $payroll->total_salary = $data['calculated_salary'][$key];
                $payroll->cash_in_hand = $data['cash_in_hand'][$key];
                $payroll->cash_in_bank = $data['cash_in_bank'][$key];
                $payroll->total_leave = 0;
                $payroll->total_absent = $data['total_absent'][$key] ?? 0;
                $payroll->total_late = $data['total_late'][$key] ?? 0;
                $payroll->total_present = $data['total_present'][$key] ?? 0;

                $startIndex = $key * 2;

                $sliceValue = array_slice($sideValues, $startIndex, 2);

                $combinedData = array_combine($keys, $sliceValue);

                $payroll->fund_values = $combinedData;
                $payroll->save();
            }

            // STEP 1: Create Payroll Accrual Journal Entry when payroll is generated
            // Debit: Salary Expense, Credit: Salary Payable (Accounts Payable)
            $branch = Branch::findOrFail($data['branch_id']);
            $department = Department::findOrFail($data['department_id']);
            
            // Create Entry for accrual
            $entryData['amount'] = array_sum($data['total_salary']);
            $entryData['narration'] = "Payroll Accrual for Month {$data['generated_month']}, Department {$department->name} of Branch {$branch->name}";
            $entryData['branch_id'] = $data['branch_id'];
            $entryData['entry_type_id'] = 1;
            
            $accrualEntry = $this->ledgerService->createEntry($entryData);
            
            // Get or create Salary Expense ledger
            $payroll_ledger = AccountLedger::where('linked_module', 'branch')
                ->where('linked_id', $data['branch_id'])
                ->whereHas('accountGroup', function($q) {
                    $q->where('name', 'LIKE', '%Salary%')->orWhere('name', 'LIKE', '%Expense%');
                })
                ->first();
            
            if (!$payroll_ledger) {
                $salaryExpenseGroup = AccountGroup::where('name', 'LIKE', '%Salary%Expense%')
                    ->orWhere('code', '040020020001')
                    ->first();
                
                if (!$salaryExpenseGroup) {
                    $expenseGroup = AccountGroup::where('type', 'expense')
                        ->where('level', 2)
                        ->first();
                    
                    if ($expenseGroup) {
                        $salaryExpenseGroup = AccountGroup::create([
                            'name' => 'Salary Expense',
                            'code' => 'EXP-SAL-' . time(),
                            'parent_id' => $expenseGroup->id,
                            'type' => 'expense',
                            'level' => 3,
                            'is_active' => 1,
                            'created_by' => auth()->id(),
                        ]);
                    }
                }
                
                if ($salaryExpenseGroup) {
                    $payroll_ledger = AccountLedger::create([
                        'name' => 'Salary Expense - ' . $branch->name,
                        'code' => 'SAL-EXP-BR-' . $data['branch_id'] . '-' . time(),
                        'description' => 'Salary expense for ' . $branch->name,
                        'account_group_id' => $salaryExpenseGroup->id,
                        'opening_balance' => 0,
                        'opening_balance_type' => 'debit',
                        'current_balance' => 0,
                        'current_balance_type' => 'debit',
                        'linked_module' => 'branch',
                        'linked_id' => $data['branch_id'],
                        'is_active' => true,
                        'created_by' => auth()->id() ?? 1
                    ]);
                    \Log::info("Salary expense ledger auto-created for branch: {$branch->name}");
                }
            }
            
            // Get or create Salary Payable ledger (Accounts Payable for salary)
            $salaryPayableGroup = AccountGroup::where('name', 'Salaries Payable Account')
                ->orWhere('code', '040020020002')
                ->first();
            
            if (!$salaryPayableGroup) {
                $accruedLiabGroup = AccountGroup::where('name', 'Accrued Liabilities')
                    ->orWhere('code', '04002002')
                    ->first();
                
                if ($accruedLiabGroup) {
                    $salaryPayableGroup = AccountGroup::where('parent_id', $accruedLiabGroup->id)
                        ->where('type', 'liability')
                        ->first();
                }
            }
            
            if (!$salaryPayableGroup) {
                $salaryPayableGroup = AccountGroup::where('type', 'liability')
                    ->where('level', 2)
                    ->first();
            }
            
            $salaryPayableLedger = AccountLedger::where('name', 'LIKE', '%Salary Payable%')
                ->whereHas('accountGroup', function($q) {
                    $q->where('type', 'liability');
                })
                ->where('linked_module', 'branch')
                ->where('linked_id', $data['branch_id'])
                ->first();
            
            if (!$salaryPayableLedger && $salaryPayableGroup) {
                $salaryPayableLedger = AccountLedger::create([
                    'name' => 'Salary Payable - ' . $branch->name,
                    'code' => 'LIA-SAL-PAY-' . $data['branch_id'] . '-' . time(),
                    'description' => 'Salary payable liability for ' . $branch->name,
                    'account_group_id' => $salaryPayableGroup->id,
                    'opening_balance' => 0,
                    'opening_balance_type' => 'credit',
                    'current_balance' => 0,
                    'current_balance_type' => 'credit',
                    'linked_module' => 'branch',
                    'linked_id' => $data['branch_id'],
                    'is_active' => true,
                    'created_by' => auth()->id() ?? 1
                ]);
                \Log::info("Salary Payable ledger auto-created for branch: {$branch->name}");
            }
            
            // Create accrual entries for each employee
            foreach ($data['employee_id'] as $key => $employeeId) {
                $employee = Employees::findOrFail($employeeId);
                $netSalary = $data['total_salary'][$key];
                
                if ($netSalary > 0 && $salaryPayableLedger && $payroll_ledger) {
                    $accrualData = [
                        'entry_id' => $accrualEntry->id,
                        'balanceType' => "d",
                        'entry_type_id' => 1,
                        'narration' => "Payroll Accrual for Employee {$employee->name}, Month {$data['generated_month']}",
                    ];
                    
                    // Debit: Salary Expense (expense increases)
                    $this->ledgerService->createEntryItems(array_merge($accrualData, [
                        'ledger_id' => $payroll_ledger->id,
                        'balanceType' => "d",
                        'amount' => $netSalary
                    ]));
                    
                    // Credit: Salary Payable / Accounts Payable (liability increases)
                    $this->ledgerService->createEntryItems(array_merge($accrualData, [
                        'ledger_id' => $salaryPayableLedger->id,
                        'balanceType' => "c",
                        'amount' => $netSalary
                    ]));
                }
            }

            DB::commit();
            return response()->json(['message' => 'Payroll data saved successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage(), 'message' => $e->getMessage()]);
        }

    }


}

