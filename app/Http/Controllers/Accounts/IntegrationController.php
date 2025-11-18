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
            // Get or create ledgers
            $salaryExpenseLedger = AccountLedger::where('name', 'LIKE', '%Salary%')
                ->where('linked_module', 'expense')
                ->first();
            
            if (!$salaryExpenseLedger) {
                $salaryExpenseLedger = AccountLedger::create([
                    'name' => 'Salary Expense',
                    'code' => 'EXP-SAL-001',
                    'description' => 'Employee salaries and wages',
                    'account_group_id' => 17, // Salary Expense
                    'opening_balance' => 0,
                    'opening_balance_type' => 'debit',
                    'current_balance' => 0,
                    'current_balance_type' => 'debit',
                    'linked_module' => 'expense',
                    'is_active' => true,
                    'created_by' => auth()->id() ?? 1
                ]);
            }
            
            $cashLedger = AccountLedger::where('name', 'LIKE', '%Cash%')->first();
            if (!$cashLedger) {
                $cashLedger = AccountLedger::create([
                    'name' => 'Cash Account',
                    'code' => 'AST-CASH-001',
                    'description' => 'Cash in hand',
                    'account_group_id' => 2, // Current Assets
                    'opening_balance' => 0,
                    'opening_balance_type' => 'debit',
                    'current_balance' => 0,
                    'current_balance_type' => 'debit',
                    'is_active' => true,
                    'created_by' => auth()->id() ?? 1
                ]);
            }

            // Create journal entry
            $entry = JournalEntry::create([
                'entry_number' => JournalEntry::generateNumber(),
                'entry_date' => $request->payment_date,
                'reference' => $request->reference,
                'description' => 'Salary payment for employee ID: ' . $request->employee_id,
                'status' => 'posted',
                'entry_type' => 'bank_receipt_voucher',
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

        // dd($request->all());
        $request->validate([
            'vendor_id' => 'required',
            'purchase_amount' => 'required|numeric',
            'purchase_date' => 'required|date',
            'reference' => 'required|string',
            'type' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Get or create ledgers
            $inventoryLedger = AccountLedger::where('name', 'LIKE', '%Inventory%')
                ->whereHas('accountGroup', function($q) {
                    $q->where('type', 'asset');
                })
                ->first();
            
            if (!$inventoryLedger) {
                $inventoryLedger = AccountLedger::create([
                    'name' => 'Inventory' .  $request->type,
                    'code' => 'AST-INV-001',
                    'description' => 'Inventory and stock items',
                    'account_group_id' => 17, // Current Assets
                    'opening_balance' => 0,
                    'opening_balance_type' => 'debit',
                    'current_balance' => 0,
                    'current_balance_type' => 'debit',
                    'linked_module' => 'inventory',
                    'is_active' => true,
                    'created_by' => auth()->id() ?? 1
                ]);
            }
            
            $payableLedger = AccountLedger::where('name', 'LIKE', '%Payable%')
                ->whereHas('accountGroup', function($q) {
                    $q->where('type', 'liability');
                })
                ->first();
            
            // If no payable ledger found, use any liability ledger or create one
            if (!$payableLedger) {
                $payableLedger = AccountLedger::whereHas('accountGroup', function($q) {
                    $q->where('type', 'liability');
                })->first();
                
                if (!$payableLedger) {
                    $payableLedger = AccountLedger::create([
                        'name' => 'Accounts Payable',
                        'code' => 'LIA-PAY-001',
                        'description' => 'Amounts owed to suppliers and vendors',
                        'account_group_id' => 647, // Accounts Payable
                        'opening_balance' => 0,
                        'opening_balance_type' => 'credit',
                        'current_balance' => 0,
                        'current_balance_type' => 'credit',
                        'is_active' => true,
                        'created_by' => auth()->id() ?? 1
                    ]);
                }
            }

            // Create journal entry
            $entry = JournalEntry::create([
                'entry_number' => JournalEntry::generateNumber(),
                'entry_date' => $request->purchase_date,
                'reference' => $request->reference,
                'description' => 'Inventory purchase from vendor ID: ' . $request->vendor_id,
                'status' => 'posted',
                'entry_type' => 'journal_voucher',
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

            
        \Log::info("=== INTEGRATION: recordAcademicFee START ===");
        \Log::info("Request data: " . json_encode($request->all()));
        
        DB::beginTransaction();
        try {
            // Get or create ledgers
            \Log::info("Searching for Cash ledger...");
            $cashLedger = AccountLedger::where('name', 'LIKE', '%MCB%')->first();
            
            if (!$cashLedger) {
                \Log::info("Cash ledger not found, creating new one...");
                $cashLedger = AccountLedger::create([
                    'name' => 'MCB - Bank - Main Account',
                    'code' => 'AST-' . str_pad($cashLedger->id, 4, '0', STR_PAD_LEFT),
                    'description' => 'Auto-created bank ledger for MCB - Bank',
                    'account_group_id' => 22, // Bank MCB Assets
                    'opening_balance' => 0,
                    'opening_balance_type' => 'debit',
                    'current_balance' => 0,
                    'current_balance_type' => 'debit',
                    'is_active' => true,
                    'created_by' => auth()->id() ?? 1
                ]);
                \Log::info("✅ Cash ledger created with ID: {$cashLedger->id}");
            } else {
                \Log::info("✅ Cash ledger found with ID: {$cashLedger->id}, Name: {$cashLedger->name}");
            }
            
            \Log::info("Searching for Fee Revenue ledger...");
            $feeRevenueLedger = AccountLedger::where('name', 'LIKE', '%revenue%')
            ->whereHas('accountGroup', function($q) {
                $q->where('type', 'revenue');
            })
            ->first();
            
            if (!$feeRevenueLedger) {
                \Log::info("Fee Revenue ledger not found, creating new one...");
                $feeRevenueLedger = AccountLedger::create([
                    'name' => 'Fee Revenue',
                    'code' => 'REV-FEE-' . time(),
                    'description' => 'Student fees and tuition revenue',
                    'account_group_id' => 85, // Revenue
                    'opening_balance' => 0,
                    'opening_balance_type' => 'credit',
                    'current_balance' => 0,
                    'current_balance_type' => 'credit',
                    'linked_module' => 'revenue',
                    'is_active' => true,
                    'created_by' => auth()->id() ?? 1
                ]);
                \Log::info("✅ Fee Revenue ledger created with ID: {$feeRevenueLedger->id}");
            } else {
                \Log::info("✅ Fee Revenue ledger found with ID: {$feeRevenueLedger->id}, Name: {$feeRevenueLedger->name}");
            }


            
            // Create journal entry
            \Log::info("Creating journal entry...");
            $entry = JournalEntry::create([
                'entry_number' => JournalEntry::generateNumber(),
                'entry_date' => $request->collection_date,
                'reference' => $request->reference,
                'description' => 'Fee collection from student ID: ' . $request->student_id,
                'status' => 'posted',
                'entry_type' => 'bank_receipt_voucher',
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
            
            \Log::info("✅ Journal entry created with ID: {$entry->id}, Number: {$entry->entry_number}");
            
            // Update balances
            \Log::info("Updating ledger balances...");
            $cashLedger->updateBalance($request->fee_amount, 0);
            $feeRevenueLedger->updateBalance(0, $request->fee_amount);
            \Log::info("✅ Ledger balances updated successfully");

            
             
              

            DB::commit();
            \Log::info("✅ Transaction committed successfully");
            \Log::info("=== INTEGRATION: recordAcademicFee SUCCESS ===");
            
            return response()->json(['success' => true, 'entry_id' => $entry->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("=== INTEGRATION: recordAcademicFee FAILED ===");
            \Log::error("Error: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
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

        \Log::info("=== FLEET EXPENSE ACCOUNTING START ===");
        \Log::info("Request: " . json_encode($request->all()));

        DB::beginTransaction();
        try {
            // Get or create ledgers
            \Log::info("Searching for Fleet Expense ledger...");
            $fleetExpenseLedger = AccountLedger::where('name', 'LIKE', '%Fleet%')
                ->orWhere('name', 'LIKE', '%Transport%')
                ->whereHas('accountGroup', function($q) {
                    $q->where('type', 'expense');
                })
                ->first();
            
            if (!$fleetExpenseLedger) {
                \Log::info("Fleet Expense ledger not found, creating...");
                $fleetExpenseLedger = AccountLedger::create([
                    'name' => 'Transport Expense',
                    'code' => 'EXP-TRAN-' . time(),
                    'description' => 'Vehicle fuel and maintenance expenses',
                    'account_group_id' => 119, // Transport Expense
                    'opening_balance' => 0,
                    'opening_balance_type' => 'debit',
                    'current_balance' => 0,
                    'current_balance_type' => 'debit',
                    'is_active' => true,
                    'created_by' => auth()->id() ?? 1
                ]);
                \Log::info("✅ Fleet Expense ledger created: ID {$fleetExpenseLedger->id}");
            } else {
                \Log::info("✅ Fleet Expense ledger found: ID {$fleetExpenseLedger->id}");
            }
            
            \Log::info("Searching for Cash ledger...");
            $cashLedger = AccountLedger::where('name', 'LIKE', '%Cash%')->first();
            if (!$cashLedger) {
                \Log::info("Cash ledger not found, creating...");
                $cashLedger = AccountLedger::create([
                    'name' => 'Cash Account',
                    'code' => 'AST-CASH-' . time(),
                    'description' => 'Cash in hand',
                    'account_group_id' => 17, // Current Assets
                    'opening_balance' => 0,
                    'opening_balance_type' => 'debit',
                    'current_balance' => 0,
                    'current_balance_type' => 'debit',
                    'is_active' => true,
                    'created_by' => auth()->id() ?? 1
                ]);
                \Log::info("✅ Cash ledger created: ID {$cashLedger->id}");
            } else {
                \Log::info("✅ Cash ledger found: ID {$cashLedger->id}");
            }

            // Create journal entry
            \Log::info("Creating journal entry...");
            $entry = JournalEntry::create([
                'entry_number' => JournalEntry::generateNumber(),
                'entry_date' => $request->expense_date,
                'reference' => $request->reference,
                'description' => 'Fleet ' . $request->expense_type . ' for vehicle ID: ' . $request->vehicle_id,
                'status' => 'posted',
                'entry_type' => 'bank_payment_voucher',
                'source_module' => 'fleet_expense',
                'source_id' => $request->vehicle_id,
                'branch_id' => auth()->user()->branch_id ?? null,
                'posted_at' => now(),
                'posted_by' => auth()->id(),
                'created_by' => auth()->id(),
            ]);
            \Log::info("✅ Journal entry created: ID {$entry->id}");

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
            \Log::info("✅ Journal entry lines created");

            // Update balances
            $fleetExpenseLedger->updateBalance($request->expense_amount, 0);
            $cashLedger->updateBalance(0, $request->expense_amount);
            \Log::info("✅ Ledger balances updated");

            DB::commit();
            \Log::info("✅ Transaction committed");
            \Log::info("=== FLEET EXPENSE ACCOUNTING SUCCESS ===");
            
            return response()->json(['success' => true, 'entry_id' => $entry->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("=== FLEET EXPENSE ACCOUNTING FAILED ===");
            \Log::error("Error: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
