<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\JournalEntry;
use App\Models\Accounts\JournalEntryLine;
use App\Models\Accounts\AccountLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IntegrationController extends Controller
{
    /**
     * Record HR Salary Payment
     * Called from HR module when salary is paid
     */
    public function recordHRSalary(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'salary_amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'reference' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Get ledgers
            $salaryExpenseLedger = AccountLedger::where('name', 'LIKE', '%Salary%')
                ->where('linked_module', 'expense')
                ->first();
            
            $cashLedger = AccountLedger::where('name', 'LIKE', '%Cash%')->first();

            if (!$salaryExpenseLedger || !$cashLedger) {
                throw new \Exception('Required ledgers not found. Please setup chart of accounts.');
            }

            // Create journal entry
            $entry = JournalEntry::create([
                'entry_number' => JournalEntry::generateNumber(),
                'entry_date' => $request->payment_date,
                'reference' => $request->reference,
                'description' => 'Salary payment for employee ID: ' . $request->employee_id,
                'status' => 'posted',
                'entry_type' => 'payment',
                'source_module' => 'hr_salary',
                'source_id' => $request->employee_id,
                'branch_id' => auth()->user()->branch_id ?? null,
                'posted_at' => now(),
                'posted_by' => auth()->id(),
                'created_by' => auth()->id(),
            ]);

            // Debit: Salary Expense
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_ledger_id' => $salaryExpenseLedger->id,
                'description' => 'Salary expense',
                'debit' => $request->salary_amount,
                'credit' => 0,
            ]);

            // Credit: Cash
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_ledger_id' => $cashLedger->id,
                'description' => 'Cash payment',
                'debit' => 0,
                'credit' => $request->salary_amount,
            ]);

            // Update ledger balances
            $salaryExpenseLedger->updateBalance($request->salary_amount, 0);
            $cashLedger->updateBalance(0, $request->salary_amount);

            DB::commit();
            return response()->json(['success' => true, 'entry_id' => $entry->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Record Inventory Purchase
     * Called from Inventory module when purchase is made
     */
    public function recordInventoryPurchase(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required',
            'purchase_amount' => 'required|numeric',
            'purchase_date' => 'required|date',
            'reference' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Get ledgers
            $inventoryLedger = AccountLedger::where('name', 'LIKE', '%Inventory%')
                ->whereHas('accountGroup', function($q) {
                    $q->where('type', 'asset');
                })
                ->first();
            
            $payableLedger = AccountLedger::where('name', 'LIKE', '%Payable%')
                ->whereHas('accountGroup', function($q) {
                    $q->where('type', 'liability');
                })
                ->first();

            if (!$inventoryLedger || !$payableLedger) {
                throw new \Exception('Required ledgers not found.');
            }

            // Create journal entry
            $entry = JournalEntry::create([
                'entry_number' => JournalEntry::generateNumber(),
                'entry_date' => $request->purchase_date,
                'reference' => $request->reference,
                'description' => 'Inventory purchase from vendor ID: ' . $request->vendor_id,
                'status' => 'posted',
                'entry_type' => 'journal',
                'source_module' => 'inventory_purchase',
                'source_id' => $request->vendor_id,
                'branch_id' => auth()->user()->branch_id ?? null,
                'posted_at' => now(),
                'posted_by' => auth()->id(),
                'created_by' => auth()->id(),
            ]);

            // Debit: Inventory
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_ledger_id' => $inventoryLedger->id,
                'debit' => $request->purchase_amount,
                'credit' => 0,
            ]);

            // Credit: Accounts Payable
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_ledger_id' => $payableLedger->id,
                'debit' => 0,
                'credit' => $request->purchase_amount,
            ]);

            // Update balances
            $inventoryLedger->updateBalance($request->purchase_amount, 0);
            $payableLedger->updateBalance(0, $request->purchase_amount);

            DB::commit();
            return response()->json(['success' => true, 'entry_id' => $entry->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Record Academic Fee Collection
     * Called from Academic module when fee is collected
     */
    public function recordAcademicFee(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'fee_amount' => 'required|numeric',
            'collection_date' => 'required|date',
            'reference' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Get ledgers
            $cashLedger = AccountLedger::where('name', 'LIKE', '%Cash%')->first();
            $feeRevenueLedger = AccountLedger::where('name', 'LIKE', '%Fee%')
                ->whereHas('accountGroup', function($q) {
                    $q->where('type', 'revenue');
                })
                ->first();

            if (!$cashLedger || !$feeRevenueLedger) {
                throw new \Exception('Required ledgers not found.');
            }

            // Create journal entry
            $entry = JournalEntry::create([
                'entry_number' => JournalEntry::generateNumber(),
                'entry_date' => $request->collection_date,
                'reference' => $request->reference,
                'description' => 'Fee collection from student ID: ' . $request->student_id,
                'status' => 'posted',
                'entry_type' => 'receipt',
                'source_module' => 'academic_fee',
                'source_id' => $request->student_id,
                'branch_id' => auth()->user()->branch_id ?? null,
                'posted_at' => now(),
                'posted_by' => auth()->id(),
                'created_by' => auth()->id(),
            ]);

            // Debit: Cash
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_ledger_id' => $cashLedger->id,
                'debit' => $request->fee_amount,
                'credit' => 0,
            ]);

            // Credit: Fee Revenue
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_ledger_id' => $feeRevenueLedger->id,
                'debit' => 0,
                'credit' => $request->fee_amount,
            ]);

            // Update balances
            $cashLedger->updateBalance($request->fee_amount, 0);
            $feeRevenueLedger->updateBalance(0, $request->fee_amount);

            DB::commit();
            return response()->json(['success' => true, 'entry_id' => $entry->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Record Fleet Expense
     * Called from Fleet module for fuel, maintenance, etc.
     */
    public function recordFleetExpense(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required',
            'expense_amount' => 'required|numeric',
            'expense_date' => 'required|date',
            'expense_type' => 'required|string',
            'reference' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Get ledgers
            $fleetExpenseLedger = AccountLedger::where('name', 'LIKE', '%Fleet%')
                ->orWhere('name', 'LIKE', '%Transport%')
                ->whereHas('accountGroup', function($q) {
                    $q->where('type', 'expense');
                })
                ->first();
            
            $cashLedger = AccountLedger::where('name', 'LIKE', '%Cash%')->first();

            if (!$fleetExpenseLedger || !$cashLedger) {
                throw new \Exception('Required ledgers not found.');
            }

            // Create journal entry
            $entry = JournalEntry::create([
                'entry_number' => JournalEntry::generateNumber(),
                'entry_date' => $request->expense_date,
                'reference' => $request->reference,
                'description' => 'Fleet ' . $request->expense_type . ' for vehicle ID: ' . $request->vehicle_id,
                'status' => 'posted',
                'entry_type' => 'payment',
                'source_module' => 'fleet_expense',
                'source_id' => $request->vehicle_id,
                'branch_id' => auth()->user()->branch_id ?? null,
                'posted_at' => now(),
                'posted_by' => auth()->id(),
                'created_by' => auth()->id(),
            ]);

            // Debit: Fleet Expense
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_ledger_id' => $fleetExpenseLedger->id,
                'debit' => $request->expense_amount,
                'credit' => 0,
            ]);

            // Credit: Cash
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_ledger_id' => $cashLedger->id,
                'debit' => 0,
                'credit' => $request->expense_amount,
            ]);

            // Update balances
            $fleetExpenseLedger->updateBalance($request->expense_amount, 0);
            $cashLedger->updateBalance(0, $request->expense_amount);

            DB::commit();
            return response()->json(['success' => true, 'entry_id' => $entry->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
