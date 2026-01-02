<?php

namespace App\Http\Controllers\HR;

use Exception;
use Carbon\Carbon;
use App\Jobs\SendEmailJob;
use App\Models\HR\Advance;
use App\Models\HR\Payroll;
use App\Mail\SalarySlipMail;
use Illuminate\Http\Request;
use App\Models\Accounts\AccountLedger;
use App\Models\Accounts\AccountGroup;
use App\Models\HR\SalarySlip;
use App\Models\Admin\Branches;
use App\Services\LedgerService;
use Yajra\DataTables\DataTables;
use App\Models\Admin\BankAccount;
use App\Models\HR\PayrollApproval;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use ZanySoft\LaravelPDF\Facades\PDF;
use App\Helpers\GeneralSettingsHelper;
use App\Models\Admin\Department;
use App\Services\EmployeeBenefitService;

class PayrollApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $ledgerService;
    protected $employeeBenefitService;
    public function __construct(LedgerService $ledgerService, EmployeeBenefitService $employeeBenefitService)
    {
        $this->ledgerService = $ledgerService;
        $this->employeeBenefitService = $employeeBenefitService;
    }

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('hr.payroll.approve');
    }


    public function getData(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $request->all();
        $startDate = $data['salary_year'] ?? '';


        $data = PayrollApproval::with('payroll')->where('generated_month_year', $startDate)->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('month', function ($row) {
                return Carbon::parse($row->generated_month)->format('F');
            })
            ->addColumn('year', function ($row) {
                return Carbon::parse($row->generated_month)->format('Y');
            })
            ->addColumn('status', function ($row) {

                if ($row->approved == 0) {
                    return 'Pending';
                } elseif ($row->approved == 1) {
                    return '<span style="color: green;">Approved</span>';
                } elseif ($row->approved == 2) {
                    return '<span style="color: red;">Rejected</span>';
                }

            })
            ->addColumn('created_at', function ($row) {
                if ($row->created_at) {
                    return $row->created_at;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('action', function ($row) {
                $action_column = '<a target="blank" href="payroll_status/' . $row->id . '" class="btn btn-xs btn-warning" style="margin: 1px;"><i class="fa fa-eye"></i> View</a>';
                return $action_column;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function payrollStatus($id)
    {
        
        $payrollApproval = PayrollApproval::with('payroll')->findorfail($id);
        
        $payrolls = $payrollApproval->payroll;
        
        return view('hr.payroll.payroll_status_approve', compact('payrollApproval', 'payrolls'));
    }

    public function payroll_status_approve($id)
    {
        
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        ini_set('max_execution_time', 120000);
        DB::beginTransaction();

        try {

            $salary = PayrollApproval::with('payroll')->findorfail($id);
            $salary->approved = 1;
            $salary->save();

            $Payroll_total = 0;
            $payrollEmployees = $salary->payroll;
          
            foreach ($payrollEmployees as $payroll) {
                $Payroll_total += $payroll->total_salary;

                if ($payroll->advance != 0 && $payroll->advance !== null) {
                    $advance = Advance::where('employee_id', $payroll->employee_id)->first();

                    if ($advance && $advance->remaining_amount != 0) {
                        $advanceAmount = $advance->remaining_amount - $payroll->advance;
                        $advance->remaining_amount = $advanceAmount;
                        $advance->save();
                    }

                    if ($advance->remaining_amount == 0) {
                        $advance->amount_status = 'paid';
                        $advance->save();
                    }

                }
            }
            $branch = Branches::where('id', $salary->branch_id)->first();
            $department = Department::where('id', $salary->department_id)->first();

            // Note: Disbursement entries are now created separately via salary_disburse method
            // This method only handles approval status update and salary slip creation

            $payrollApprovals = PayrollApproval::with(['payroll.employee'])->where('approved', 1)->find($id);
            $generatedMonth_year = $payrollApprovals->generated_month_year;
            $generatedMonth = $payrollApprovals->generated_month;

            if ($payrollApprovals->has('payroll')) {
                $groups['eobi_provident_fund'] = config('constants.FixedGroups.EOBI');
                $groups['provident_fund'] = config('constants.FixedGroups.PF');

                $compnay_eobi = GeneralSettingsHelper::getSetting('eobi');
                $socialSecurity = GeneralSettingsHelper::getSetting('socialSecurity');

                foreach ($payrollApprovals->payroll as $payroll) {
                    // creating Employee Benefits
                    // eobi
                    if ($compnay_eobi && isset($compnay_eobi['company']) && isset($payroll->fund_values['eobi_provident_fund'])) {
                        $this->employeeBenefitService->createEmployeeBenefit($payroll->employee_id, $compnay_eobi['company'], $payroll->fund_values['eobi_provident_fund']);
                    }
                    
                    // PF
                    if (isset($payroll->fund_values['provident_fund'])) {
                        $this->employeeBenefitService->createEmployeeBenefit($payroll->employee_id, $payroll->fund_values['provident_fund'], $payroll->fund_values['provident_fund'], "PF");
                    }
                    
                    //SS
                    if ($socialSecurity && isset($socialSecurity['percentage']) && isset($socialSecurity['min-salary'])) {
                        $percentage = $socialSecurity['percentage'];
                        $min_salary = $socialSecurity['min-salary'];
                        if ($min_salary <= (int) $payroll->employee->grossSalary) {
                            $ss = ((int) $payroll->employee->grossSalary * (int) $percentage) / 100;
                            $this->employeeBenefitService->createEmployeeBenefit($payroll->employee_id, $ss, 0, "SS");
                        }
                    }

                    // Create Salary Slip
                    $SalarySlip = SalarySlip::create([
                        'employee_id' => $payroll->employee_id,
                        'salary_per_minute' => $payroll->salary_per_minute,
                        'total_working_hours' => $payroll->total_working_hours,
                        'advance' => $payroll->advance,
                        'total_fund_amount' => $payroll->total_fund_amount,
                        'fund_values' => $payroll->fund_values,
                        'total_salary' => $payroll->total_salary,
                        'net_salary' => $payroll->net_salary,
                        'cash_in_hand' => $payroll->cash_in_hand,
                        'cash_in_bank' => $payroll->cash_in_bank,
                        'committedTime' => $payroll->committedTime,
                        'generated_month_year' => $generatedMonth_year,
                        'generated_month' => $generatedMonth,
                        'total_present' => $payroll->total_present,
                        'total_absent' => $payroll->total_absent,
                        'total_leave' => $payroll->total_leave,
                        'total_late' => $payroll->total_late,
                        'medicalAllowance' => $payroll->medicalAllowance,
                    ]);
                }
            }
            
            \Log::info("Payroll approved for: {$salary->generated_month}, Branch: {$branch->name}, Total: {$Payroll_total}");

            DB::commit();
            return redirect()->route('hr.payroll.approve')->with('success', 'Payroll Approved');
        } catch (Exception $e) {
            DB::Rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function payroll_status_reject($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $salary = PayrollApproval::find($id);
        $salary->approved = 2;
        $salary->save();

        return redirect()->route('hr.payroll.approve');

    }

    /**
     * Display Salary Disburse listing page
     */
    public function salaryDisburseIndex()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('hr.payroll.disburse');
    }

    /**
     * Get data for Salary Disburse datatable (only approved payrolls)
     */
    public function getDisburseData(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $request->all();
        $startDate = $data['salary_year'] ?? '';

        // Only get approved payrolls (approved = 1)
        $data = PayrollApproval::with('payroll')
            ->where('approved', 1)
            ->where('generated_month_year', $startDate)
            ->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('month', function ($row) {
                return Carbon::parse($row->generated_month)->format('F');
            })
            ->addColumn('year', function ($row) {
                return Carbon::parse($row->generated_month)->format('Y');
            })
            ->addColumn('status', function ($row) {
                if (!empty($row->disbursed) && $row->disbursed == 1) {
                    return '<span style="color: blue;">Disbursed</span>';
                } else {
                    return '<span style="color: green;">Approved - Pending Disbursement</span>';
                }
            })
            ->addColumn('created_at', function ($row) {
                if ($row->created_at) {
                    return $row->created_at;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('action', function ($row) {
                $action_column = '<a target="blank" href="salary_disburse_status/' . $row->id . '" class="btn btn-xs btn-warning" style="margin: 1px;"><i class="fa fa-eye"></i> View</a>';
                return $action_column;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    /**
     * Show Salary Disburse detail page
     */
    public function salaryDisburseStatus($id)
    {
        $payrollApproval = PayrollApproval::with('payroll')->findorfail($id);
        
        // Only show if approved
        if ($payrollApproval->approved != 1) {
            return redirect()->route('hr.salary.disburse')->with('error', 'Only approved payrolls can be disbursed.');
        }
        
        $payrolls = $payrollApproval->payroll;
        
        return view('hr.payroll.salary_disburse_status', compact('payrollApproval', 'payrolls'));
    }

    /**
     * Process Salary Disbursement - Create ledger entries
     */
    
    public function salary_disburse($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        ini_set('max_execution_time', 120000);
        DB::beginTransaction();

        try {
            $salary = PayrollApproval::with('payroll')->findorfail($id);
            
            // Only allow disbursement if approved
            if ($salary->approved != 1) {
                throw new Exception("Only approved payrolls can be disbursed.");
            }

            // Check if already disbursed
            if (!empty($salary->disbursed) && $salary->disbursed == 1) {
                throw new Exception("This payroll has already been disbursed.");
            }

            $Payroll_total = 0;
            $payrollEmployees = $salary->payroll;
          
            foreach ($payrollEmployees as $payroll) {
                $Payroll_total += $payroll->total_salary;
            }

            $branch = Branches::where('id', $salary->branch_id)->first();
            $department = Department::where('id', $salary->department_id)->first();

            // Creating Entry for disbursement
            $data['amount'] = $Payroll_total;
            $data['narration'] = "Salary Disbursement for Month $salary->generated_month, For Department $department->name of Branch $branch->name";
            $data['branch_id'] = $salary->branch_id;
            $data['entry_type_id'] = 1;

            $entry = $this->ledgerService->createEntry($data);

            $payrollApprovals = PayrollApproval::with(['payroll.employee'])->where('approved', 1)->find($id);
            $generatedMonth_year = $payrollApprovals->generated_month_year;
            $generatedMonth = $payrollApprovals->generated_month;
            $bank_ledger = AccountLedger::where('id', $payrollApprovals->bank_account_ledger)->first();
            $cash_ledger = AccountLedger::where('code', 'cash')->first();
            
            // Auto-create cash ledger if not found
            if (!$cash_ledger) {
                $cash_ledger = AccountLedger::create([
                    'name' => 'Cash Account',
                    'code' => 'cash',
                    'description' => 'Cash in hand',
                    'account_group_id' => 2, // Current Assets
                    'opening_balance' => 0,
                    'opening_balance_type' => 'debit',
                    'current_balance' => 0,
                    'current_balance_type' => 'debit',
                    'is_active' => true,
                    'created_by' => auth()->id() ?? 1
                ]);
                \Log::info("Cash ledger auto-created for payroll disbursement");
            }

            if ($payrollApprovals->has('payroll')) {
                
                // Get or create Salary Payable ledger (Accounts Payable for salary)
                $salaryPayableGroup = AccountGroup::where('name', 'Salaries Payable Account')
                    ->orWhere('code', '040020020002')
                    ->first();
                
                if (!$salaryPayableGroup) {
                    // Try to find any liability group under Accrued Liabilities
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
                    // Try to find any liability group
                    $salaryPayableGroup = AccountGroup::where('type', 'liability')
                        ->where('level', 2)
                        ->first();
                }

                if (!$salaryPayableGroup) {
                    throw new Exception("Salary Payable Account Group not found. Please configure it in Chart of Accounts.");
                }

                $salaryPayableLedger = AccountLedger::where('name', 'LIKE', '%Salary Payable%')
                    ->whereHas('accountGroup', function($q) {
                        $q->where('type', 'liability');
                    })
                    ->where('linked_module', 'branch')
                    ->where('linked_id', $payrollApprovals->branch_id)
                    ->first();

                if (!$salaryPayableLedger && $salaryPayableGroup) {
                    $salaryPayableLedger = AccountLedger::create([
                        'name' => 'Salary Payable - ' . $branch->name,
                        'code' => 'LIA-SAL-PAY-' . $payrollApprovals->branch_id . '-' . time(),
                        'description' => 'Salary payable liability for ' . $branch->name,
                        'account_group_id' => $salaryPayableGroup->id,
                        'opening_balance' => 0,
                        'opening_balance_type' => 'credit',
                        'current_balance' => 0,
                        'current_balance_type' => 'credit',
                        'linked_module' => 'branch',
                        'linked_id' => $payrollApprovals->branch_id,
                        'is_active' => true,
                        'created_by' => auth()->id() ?? 1
                    ]);
                    \Log::info("Salary Payable ledger auto-created for branch: {$branch->name}");
                }

                if (!$salaryPayableLedger) {
                    throw new Exception("Salary Payable ledger not found. Cannot process salary disbursement.");
                }

                foreach ($payrollApprovals->payroll as $payroll) {
                    if ($payroll->cash_in_hand == 0 && $payroll->cash_in_bank == 0) {
                        throw new Exception("Cash in bank and Bank can not be 0 for " . $payroll->employee->name);
                    }

                    // STEP 2: Salary Disbursement - Debit Salary Payable, Credit Cash/Bank
                    $disbursementData = [
                        'entry_id' => $entry->id,
                        'balanceType' => "c",
                        'entry_type_id' => 1,
                        'narration' => "Salary Disbursement for Employee {$payroll->employee->name}, Month {$salary->generated_month}",
                    ];

                    // Debit: Salary Payable (liability decreases)
                    if ($payroll->cash_in_hand > 0) {
                        $this->ledgerService->createEntryItems(array_merge($disbursementData, [
                            'ledger_id' => $salaryPayableLedger->id,
                            'balanceType' => "d",
                            'amount' => $payroll->cash_in_hand
                        ]));
                        // Credit: Cash (asset decreases)
                        $this->ledgerService->createEntryItems(array_merge($disbursementData, [
                            'ledger_id' => $cash_ledger->id,
                            'balanceType' => "c",
                            'amount' => $payroll->cash_in_hand
                        ]));
                    }
                    
                    if ($payroll->cash_in_bank > 0 && $bank_ledger) {
                        // Debit: Salary Payable (liability decreases)
                        $this->ledgerService->createEntryItems(array_merge($disbursementData, [
                            'ledger_id' => $salaryPayableLedger->id,
                            'balanceType' => "d",
                            'amount' => $payroll->cash_in_bank
                        ]));
                        // Credit: Bank (asset decreases)
                        $this->ledgerService->createEntryItems(array_merge($disbursementData, [
                            'ledger_id' => $bank_ledger->id,
                            'balanceType' => "c",
                            'amount' => $payroll->cash_in_bank
                        ]));
                    }
                }

                // Mark as disbursed
                $salary->disbursed = 1;
                $salary->save();
            }

            \Log::info("Salary disbursed for: {$salary->generated_month}, Branch: {$branch->name}, Total: {$Payroll_total}");

            DB::commit();
            return redirect()->route('hr.salary.disburse')->with('success', 'Salary Disbursed Successfully');
        } catch (Exception $e) {
            DB::Rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

