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

            // Creating Entry
            $data['amount'] = $Payroll_total;
            $data['narration'] = "Approving Paylip of Month $salary->generated_month, For Department $department->name of Branch $branch->name";
            $data['branch_id'] = $salary->branch_id;
            $data['entry_type_id'] = 1;

            $entry = $this->ledgerService->createEntry($data);
            //  ----------------------------------------------------------------

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
                \Log::info("Cash ledger auto-created for payroll");
            }

            if ($payrollApprovals->has('payroll')) {

                $payroll_ledger = AccountLedger::where('linked_module', 'branch')
                    ->where('linked_id', $payrollApprovals->branch_id)
                    ->first();
                    
                // Auto-create salary expense ledger if not found
                if (!$payroll_ledger) {
                    $payroll_ledger = AccountLedger::create([
                        'name' => 'Salary Expense - ' . $branch->name,
                        'code' => 'SAL-BR-' . $payrollApprovals->branch_id . '-' . time(),
                        'description' => 'Salary expense for ' . $branch->name,
                        'account_group_id' => 17, // Salary Expense
                        'opening_balance' => 0,
                        'opening_balance_type' => 'debit',
                        'current_balance' => 0,
                        'current_balance_type' => 'debit',
                        'linked_module' => 'branch',
                        'linked_id' => $payrollApprovals->branch_id,
                        'is_active' => true,
                        'created_by' => auth()->id() ?? 1
                    ]);
                    \Log::info("Salary expense ledger auto-created for branch: {$branch->name}");
                }
                foreach ($payrollApprovals->payroll as $payroll) {
                    if ($payroll->cash_in_hand == 0 && $payroll->cash_in_bank == 0) {
                        throw new Exception("Cash in bank and Bank can not be 0 for " . $payroll->employee->name);
                    }

                    $data = [
                        'entry_id' => $entry->id,
                        'balanceType' => "c",
                        'entry_type_id' => 1,
                        'narration' => "Approving Paylip of Month {$salary->generated_month}, for Employee {$payroll->employee->name}",
                    ];

                    if ($payroll->cash_in_hand > 0) {
                        $this->ledgerService->createEntryItems(array_merge($data, ['ledger_id' => $cash_ledger->id, 'amount' => $payroll->cash_in_hand]));
                    }
                    if ($payroll->cash_in_bank > 0) {
                        $this->ledgerService->createEntryItems(array_merge($data, ['ledger_id' => $bank_ledger->id, 'amount' => $payroll->cash_in_bank]));
                    }
                    $this->ledgerService->createEntryItems(array_merge($data, ['ledger_id' => $payroll_ledger->id, 'balanceType' => "d", 'amount' => $payroll->net_salary]));


                    $groups['eobi_provident_fund'] = config('constants.FixedGroups.EOBI');
                    $groups['provident_fund'] = config('constants.FixedGroups.PF');

                    $compnay_eobi = GeneralSettingsHelper::getSetting('eobi');
                    $socialSecurity = GeneralSettingsHelper::getSetting('socialSecurity');

                    
                   
                    foreach ($groups as $key => $group) {
                        // dd($group);
                        $ledgers = $this->ledgerService->getLedgers($group, Branches::class, $payrollApprovals->branch_id);
                        // dd($ledgers , $payroll->fund_values[$key] , 'e');
                        foreach ($ledgers as $ledger) {
                            $data['amount'] = $payroll->fund_values[$key];
                            $data['entry_id'] = $entry->id;
                            $data['balanceType'] = $ledger->account_type_id == 2 ? "c" : "d";
                            $data['narration'] = "Entry of " . $key . " For " . $payroll->employee->name;
                            $data['ledger_id'] = $ledger->id;

                            $this->ledgerService->createEntryItems($data);
                        }
                    }


                    // creating Emplyeee Benifits

                    // eobi

                    $this->employeeBenefitService->createEmployeeBenefit($payroll->employee_id, $compnay_eobi['company'], $payroll->fund_values['eobi_provident_fund']);
                    // PF
                    $this->employeeBenefitService->createEmployeeBenefit($payroll->employee_id, $payroll->fund_values['provident_fund'], $payroll->fund_values['provident_fund'], "PF");
                    //SS
                    $percentage = $socialSecurity['percentage'];
                    $min_salary = $socialSecurity['min-salary'];
                    if ($min_salary <= (int) $payroll->employee->grossSalary) {
                        $ss = ((int) $payroll->employee->grossSalary * (int) $percentage) / 100;
                        $this->employeeBenefitService->createEmployeeBenefit($payroll->employee_id, $ss, 0, "SS");
                        $groups['Social_Security'] = config('constants.FixedGroups.SS');

                        $ledgers = $this->ledgerService->getLedgers($groups['Social_Security'], Branches::class, $payrollApprovals->branch_id);
                        dd(  $ledgers , '3');
                        foreach ($ledgers as $ledger) {
                            $data['amount'] = $ss;
                            $data['entry_id'] = $entry->id;
                            $data['balanceType'] = $ledger->account_type_id == 2 ? "c" : "d";
                            $data['narration'] = "Entry of Social_Security  For " . $payroll->employee->name;
                            $data['ledger_id'] = $ledger->id;

                            $this->ledgerService->createEntryItems($data);
                        }
                    }
                    // ----------------------

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

            // ✅ Accounting entries already created above (lines 153-185)
            // No need for additional integration - ledger entries are complete
            
            \Log::info("Payroll approved and accounting entries created for: {$salary->generated_month}, Branch: {$branch->name}, Total: {$Payroll_total}");

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
}

