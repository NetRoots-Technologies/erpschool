<?php

namespace App\Http\Controllers\Student;

use Carbon\Carbon;
use App\Models\Admin\Branch;
use Illuminate\Http\Request;
use App\Models\Admin\Company;
use App\Models\StudentChallan;
use App\Models\Student\Students;
use Illuminate\Support\Facades\DB;
use App\Exports\PreAdmissionExport;
use App\Imports\PreAdmissionImport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Accounts\JournalEntry;
use App\Models\Admin\StudentDataBank;
use App\Models\Accounts\AccountLedger;
use App\Models\Student\AcademicSession;
use App\Models\Academic\AcademicClass;
use App\Services\StudentDataBankService;
use App\Models\Accounts\JournalEntryLine;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Models\Accounts\AccountGroup;

class StudentDataBankController extends Controller
{
    protected $StudentDataBankService;
    public function __construct(StudentDataBankService $studentDataBankService)
    {
        $this->StudentDataBankService = $studentDataBankService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('PreAdmissionForm-list')) {
            return abort(503);
        }
        return view('acadmeic.student_databank.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('PreAdmissionForm-create')) {
            return abort(503);
        }
        $year = Carbon::now()->format('y');
        $branchCode = 'LHR';
        $regNo = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $referenceNo = "CSS-$year-$branchCode-$regNo";

        // Active academic sessions
        $sessions = AcademicSession::where('status', 1)->get();

        // 👉 Get all available classes
        $classes = AcademicClass::where('status', 1)->orderBy('name')->get();

        return view('acadmeic.student_databank.create', compact('referenceNo', 'sessions', 'classes'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('PreAdmissionForm-create')) {
            return abort(503);
        }
        // dd($request->all());
        $this->StudentDataBankService->store($request);
        return redirect()->route('academic.studentDataBank.index')->with('success', 'Databank created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('PreAdmissionForm-list')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('PreAdmissionForm-edit')) {
            return abort(503);
        }
        $studentDatabank = StudentDataBank::find($id);
        return view('acadmeic.student_databank.edit', compact('studentDatabank'));

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
        if (!Gate::allows('PreAdmissionForm-edit')) {
            return abort(503);
        }
        $this->StudentDataBankService->update($request, $id);

        return redirect()->route('academic.studentDataBank.index')->with('success', 'Databank Updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('PreAdmissionForm-delete')) {
            return abort(503);
        }
        $this->StudentDataBankService->destroy($id);

        return redirect()->route('academic.studentDataBank.index')->with('success', 'Databank Deleted successfully');

    }

    public function getData()
    {
        if (!Gate::allows('PreAdmissionForm-list')) {
            return abort(503);
        }
        $data = $this->StudentDataBankService->getData();
        return $data;
    }

    public function addStudent($id = 0)
    {
        if (!Gate::allows('PreAdmissionForm-list')) {
            return abort(503);
        }
        $studentDatabank = StudentDataBank::find($id);
        $companies = Company::where('status', 1)->get();
        $branches = Branch::where('status', 1)->get();
        $students = Students::all();

        if ($studentDatabank != null) {
            return view('acadmeic.student.databank', compact('students', 'branches', 'studentDatabank', 'companies'));
        }
    }


    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('PreAdmissionForm-list')) {
            return abort(503);
        }
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            StudentDataBank::where('id', $id)->delete();
        }

        return response()->json(['message' => 'Bulk action completed successfully']);
    }

    public function exportBulkFile()
    {
        if (!Gate::allows('PreAdmissionForm-list')) {
            return abort(503);
        }
        return Excel::download(new PreAdmissionExport, 'preadmission_sample.xlsx');
    }

    public function importBulkFile(Request $request)
    {
        if (!Gate::allows('PreAdmissionForm-list')) {
            return abort(503);
        }
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new PreAdmissionImport, $request->file('import_file'));
            return back()->with('success', 'Admission Form Excel File imported successfully!');
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $firstError = $failures[0]->errors()[0] ?? 'Import failed due to validation error.';
            return back()->with('error', 'Import Failed: ' . $firstError);
        } catch (\Throwable $e) {
            Log::error('PreAdmissionForm Import Exception: ' . $e->getMessage());
            return back()->with('error', 'Import Failed: ' . $e->getMessage());
        }
    }

    // for student challan
    public function studentChallan($id)
    {
        $studentDatabank = StudentDataBank::with(['challans', 'classes'])->find($id);

        if (!$studentDatabank) {
            return abort(404, 'Student not found');
        }

        $companies = Company::where('status', 1)->get();
        $branches = Branch::where('status', 1)->get();
        $students = Students::all();

        return view('acadmeic.student.studentchallan', compact('students', 'branches', 'studentDatabank', 'companies'));
    }


    // public function studentChallanPayment(Request $request, $id)
    // {
    //     $challan = StudentChallan::findOrFail($id);

    //     if ($challan->status === 'Paid') {
    //         return back()->with('warning', 'Challan already paid');
    //     }

    //     $paymentMode = $request->payment_mode;
    //     $classId = $request->class_id; // 👈 receive class ID
    //     // dd($classId);


    //     if (!in_array($paymentMode, ['cash', 'bank'])) {
    //         return back()->with('error', 'Invalid payment mode');
    //     }

    //     DB::transaction(function () use ($challan, $paymentMode) {

    //         $challan->update([
    //             'paid_date' => now()->toDateString(),
    //             'status'    => 'Paid',
    //         ]);

    //         $this->createChallanJournalEntry($challan, $paymentMode);
    //     });

    //     return back()->with('success', 'Challan paid & journal entry created successfully');
    // }

    // private function createChallanJournalEntry(StudentChallan $challan, $paymentMode)
    // {
    //     // ===============================
    //     // Revenue Ledger
    //     // ===============================
    //     $revenueLedger = AccountLedger::firstOrCreate(
    //         [
    //             'account_group_id' => 108,
    //             'linked_module'    => 'preadmission',
    //         ],
    //         [
    //             'name'                   => 'Pre Admission Form Charges',
    //             'code'                   => 'REV-PRE-' . time(),
    //             'opening_balance'        => 0,
    //             'opening_balance_type'   => 'credit',
    //             'current_balance'        => 0,
    //             'current_balance_type'   => 'credit',
    //             'is_active'              => true,
    //             'created_by'             => auth()->id() ?? 1,
    //         ]
    //     );

    //     // ===============================
    //     // Select Debit Ledger
    //     // ===============================
    //     if ($paymentMode === 'cash') {
    //         $debitLedger = AccountLedger::where('name', 'Cash in Hand')->first();
    //     } else {
    //         // $debitLedger = AccountGroup::where('name', 'Cash at Bank')->first();
    //            $debitLedger = AccountLedger::firstOrCreate(
    //     [
    //         'name' => 'Cash at Bank',
    //     ],
    //     [
    //         'account_group_id'        => 102, // BANK GROUP ID
    //         'code'                   => 'BANK-' . time(),
    //         'opening_balance'        => 0,
    //         'opening_balance_type'   => 'debit',
    //         'current_balance'        => 0,
    //         'current_balance_type'   => 'debit',
    //         'is_active'              => true,
    //         'created_by'             => auth()->id() ?? 1,
    //     ]
    // );
    //     }

    //     if (!$debitLedger) {
    //         throw new \Exception('Payment ledger not found');
    //     }

    //     // ===============================
    //     // Journal Entry
    //     // ===============================
    //     $entry = JournalEntry::create([
    //         'entry_number'  => JournalEntry::generateNumber(),
    //         'entry_date'    => now()->toDateString(),
    //         'reference'     => 'CHALLAN-' . $challan->id,
    //         'description'   => 'Pre Admission Test Charges',
    //         'status'        => 'posted',
    //         'entry_type'    => 'receipt_voucher',
    //         'source_module' => 'student_challan',
    //         'source_id'     => $challan->id,
    //         'branch_id'     => $challan->branch_id,
    //         'posted_at'     => now(),
    //         'posted_by'     => auth()->id(),
    //         'created_by'    => auth()->id(),
    //     ]);

    //     // Debit
    //     JournalEntryLine::create([
    //         'journal_entry_id' => $entry->id,
    //         'account_ledger_id'=> $debitLedger->id,
    //         'debit'            => $challan->amount,
    //         'credit'           => 0,
    //     ]);

    //     // Credit
    //     JournalEntryLine::create([
    //         'journal_entry_id' => $entry->id,
    //         'account_ledger_id'=> $revenueLedger->id,
    //         'debit'            => 0,
    //         'credit'           => $challan->amount,
    //     ]);

    //     $challan->update(['journal_entry_id' => $entry->id]);

    //     // Update balances
    //     $debitLedger->updateBalance($challan->amount, 0);
    //     $revenueLedger->updateBalance(0, $challan->amount);
    // }


    public function studentChallanPayment(Request $request, $id)
    {
        $challan = StudentChallan::findOrFail($id);

        if ($challan->status === 'Paid') {
            return back()->with('warning', 'Challan already paid');
        }

        $paymentMode = $request->payment_mode;
        $classId = $request->class_id; // 👈 receive class ID

        if (!in_array($paymentMode, ['cash', 'bank'])) {
            return back()->with('error', 'Invalid payment mode');
        }

        DB::transaction(function () use ($challan, $paymentMode, $classId) {

            // Mark challan as paid
            $challan->update([
                'paid_date' => now()->toDateString(),
                'status'    => 'Paid',
            ]);

            // Pass class ID to journal entry creation
            $this->createChallanJournalEntry($challan, $paymentMode, $classId);
        });

        return back()->with('success', 'Challan paid & journal entry created successfully');
    }


    private function createChallanJournalEntry(StudentChallan $challan, $paymentMode, $classId)
    {
        // ===============================
        // Revenue Ledger
        // ===============================
        if (in_array($classId, [10, 13])) {
            // SEN Assessment Fee Ledger for class 10 or 13
            $revenueLedger = AccountLedger::firstOrCreate(
                [
                    'account_group_id' => 108, // Revenue group
                    'linked_module'    => 'sen_assessment_fee',
                ],
                [
                    'name'                   => 'SEN Assessment Fee',
                    'code'                   => 'REV-SEN-' . time(),
                    'opening_balance'        => 0,
                    'opening_balance_type'   => 'credit',
                    'current_balance'        => 0,
                    'current_balance_type'   => 'credit',
                    'is_active'              => true,
                    'created_by'             => auth()->id() ?? 1,
                ]
            );
        } else {
            // Default Pre Admission Ledger
            $revenueLedger = AccountLedger::firstOrCreate(
                [
                    'account_group_id' => 108,
                    'linked_module'    => 'preadmission',
                ],
                [
                    'name'                   => 'Pre Admission Form Charges',
                    'code'                   => 'REV-PRE-' . time(),
                    'opening_balance'        => 0,
                    'opening_balance_type'   => 'credit',
                    'current_balance'        => 0,
                    'current_balance_type'   => 'credit',
                    'is_active'              => true,
                    'created_by'             => auth()->id() ?? 1,
                ]
            );
        }

        // ===============================
        // Select Debit Ledger
        // ===============================
        if ($paymentMode === 'cash') {
            $debitLedger = AccountLedger::firstOrCreate(
                ['name' => 'Cash in Hand'],
                [
                    'account_group_id'        => 101, // Cash group ID
                    'code'                   => 'CASH-' . time(),
                    'opening_balance'        => 0,
                    'opening_balance_type'   => 'debit',
                    'current_balance'        => 0,
                    'current_balance_type'   => 'debit',
                    'is_active'              => true,
                    'created_by'             => auth()->id() ?? 1,
                ]
            );
        } else {
            $debitLedger = AccountLedger::firstOrCreate(
                ['name' => 'Cash at Bank'],
                [
                    'account_group_id'        => 102, // Bank group ID
                    'code'                   => 'BANK-' . time(),
                    'opening_balance'        => 0,
                    'opening_balance_type'   => 'debit',
                    'current_balance'        => 0,
                    'current_balance_type'   => 'debit',
                    'is_active'              => true,
                    'created_by'             => auth()->id() ?? 1,
                ]
            );
        }

        if (!$debitLedger) {
            throw new \Exception('Payment ledger not found');
        }

        // ===============================
        // Journal Entry
        // ===============================
        $entry = JournalEntry::create([
            'entry_number'  => JournalEntry::generateNumber(),
            'entry_date'    => now()->toDateString(),
            'reference'     => 'CHALLAN-' . $challan->id,
            'description'   => $classId && in_array($classId, [10,13])
                                ? 'SEN Assessment Fee Payment'
                                : 'Pre Admission Test Charges',
            'status'        => 'posted',
            'entry_type'    => 'receipt_voucher',
            'source_module' => 'student_challan',
            'source_id'     => $challan->id,
            'branch_id'     => $challan->branch_id,
            'posted_at'     => now(),
            'posted_by'     => auth()->id(),
            'created_by'    => auth()->id(),
        ]);

        // Debit
        JournalEntryLine::create([
            'journal_entry_id' => $entry->id,
            'account_ledger_id'=> $debitLedger->id,
            'debit'            => $challan->amount,
            'credit'           => 0,
        ]);

        // Credit
        JournalEntryLine::create([
            'journal_entry_id' => $entry->id,
            'account_ledger_id'=> $revenueLedger->id,
            'debit'            => 0,
            'credit'           => $challan->amount,
        ]);

        $challan->update(['journal_entry_id' => $entry->id]);

        // Update balances
        $debitLedger->updateBalance($challan->amount, 0);
        $revenueLedger->updateBalance(0, $challan->amount);
    }


}

