<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\VendorBill;
use App\Models\Accounts\Vendor;
use App\Models\Accounts\JournalEntry;
use App\Models\Accounts\JournalEntryLine;
use App\Models\Accounts\AccountLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function index()
    {
        $bills = VendorBill::with(['vendor', 'branch'])->latest()->paginate(20);
        return view('accounts.payables.bills.index', compact('bills'));
    }

    public function create()
    {
        $vendors = Vendor::where('is_active', true)->get();
        return view('accounts.payables.bills.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:account_vendors,id',
            'bill_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:bill_date',
            'subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $bill = VendorBill::create([
                'bill_number' => VendorBill::generateNumber(),
                'vendor_id' => $request->vendor_id,
                'bill_date' => $request->bill_date,
                'due_date' => $request->due_date,
                'vendor_invoice_number' => $request->vendor_invoice_number,
                'subtotal' => $request->subtotal,
                'tax_amount' => $request->tax_amount ?? 0,
                'discount' => $request->discount ?? 0,
                'total_amount' => $request->total_amount,
                'balance' => $request->total_amount,
                'status' => 'pending',
                'notes' => $request->notes,
                'branch_id' => auth()->user()->branch_id ?? null,
                'created_by' => auth()->id(),
            ]);

            // Load the vendor relationship with accountLedger
            $bill->load('vendor.accountLedger');
            
            // Ensure vendor has an account ledger
            if (!$bill->vendor->accountLedger) {
                $vendorLedger = AccountLedger::create([
                    'name' => 'Vendor - ' . $bill->vendor->name,
                    'code' => 'VEN-' . $bill->vendor->id . '-' . time(),
                    'description' => 'Vendor payable account',
                    'account_group_id' => 647, // Accounts Payable
                    'opening_balance' => 0,
                    'opening_balance_type' => 'credit',
                    'current_balance' => 0,
                    'current_balance_type' => 'credit',
                    'is_active' => true,
                    'created_by' => auth()->id() ?? 1
                ]);
                
                // Update vendor with ledger
                $bill->vendor->account_ledger_id = $vendorLedger->id;
                $bill->vendor->save();
                
                // Reload the relationship
                $bill->load('vendor.accountLedger');
            }

            // Create journal entry
            $this->createJournalEntry($bill);

            DB::commit();
            return redirect()->route('accounts.payables.bills.index')->with('success', 'Bill created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $bill = VendorBill::with(['vendor', 'journalEntry'])->findOrFail($id);
        return view('accounts.payables.bills.show', compact('bill'));
    }

    public function pay(Request $request, $id)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
        ]);

        $bill = VendorBill::with('vendor.accountLedger')->findOrFail($id);

        DB::beginTransaction();
        try {
            $bill->paid_amount += $request->payment_amount;
            $bill->balance = $bill->total_amount - $bill->paid_amount;
            $bill->updateStatus();
            $bill->save();

            // Create payment journal entry
            $this->createPaymentEntry($bill, $request->payment_amount, $request->payment_date);

            DB::commit();
            return redirect()->route('accounts.payables.bills.show', $id)->with('success', 'Payment recorded successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    private function createJournalEntry($bill)
    {
        // Get or create expense ledger
        $expenseLedger = AccountLedger::where('linked_module', 'expense')->first();
        
        if (!$expenseLedger) {
            // Try to find any expense ledger
            $expenseLedger = AccountLedger::whereHas('accountGroup', function($q) {
                $q->where('type', 'expense');
            })->first();
            
            // If still not found, create one
            if (!$expenseLedger) {
                $expenseLedger = AccountLedger::create([
                    'name' => 'Expense Payable Account', // Replace with your desired name General Expenses
                    'code' => 'EXP-GEN-' . time(),
                    'description' => 'General business expenses',
                    'account_group_id' => 60, // Expense Payable Account
                    'opening_balance' => 0,
                    'opening_balance_type' => 'debit',
                    'current_balance' => 0,
                    'current_balance_type' => 'debit',
                    'linked_module' => 'expense',
                    'is_active' => true,
                    'created_by' => auth()->id() ?? 1
                ]);
                \Log::info("Expense ledger auto-created for bill");
            }
        }
        
        $entry = JournalEntry::create([
            'entry_number' => JournalEntry::generateNumber(),
            'entry_date' => $bill->bill_date,
            'reference' => $bill->bill_number,
            'description' => 'Bill from ' . $bill->vendor->name,
            'status' => 'posted',
            'entry_type' => 'journal_voucher',
            'source_module' => 'vendor_bill',
            'source_id' => $bill->id,
            'branch_id' => $bill->branch_id,
            'posted_at' => now(),
            'posted_by' => auth()->id(),
            'created_by' => auth()->id(),
        ]);

        // Debit expense
        JournalEntryLine::create([
            'journal_entry_id' => $entry->id,
            'account_ledger_id' => $expenseLedger->id,
            'debit' => $bill->total_amount,
            'credit' => 0,
        ]);

        // Credit vendor payable
        $vendorLedger = $bill->vendor->accountLedger;
        if (!$vendorLedger) {
            // This should not happen as we create it in store method, but just in case
            throw new \Exception('Vendor account ledger not found. Please ensure vendor has an account ledger.');
        }
        
        JournalEntryLine::create([
            'journal_entry_id' => $entry->id,
            'account_ledger_id' => $vendorLedger->id,
            'debit' => 0,
            'credit' => $bill->total_amount,
        ]);

        $bill->journal_entry_id = $entry->id;
        $bill->save();

        // Update ledger balances
        $expenseLedger->updateBalance($bill->total_amount, 0);
        $bill->vendor->accountLedger->updateBalance(0, $bill->total_amount);
    }

    private function createPaymentEntry($bill, $amount, $date)
    {
        $cashLedger = AccountLedger::where('name', 'LIKE', '%Cash%')->first();
        
        if (!$cashLedger) {
            throw new \Exception('Cash ledger not found. Please create a cash account ledger first.');
        }
        
        $entry = JournalEntry::create([
            'entry_number' => JournalEntry::generateNumber(),
            'entry_date' => $date,
            'reference' => 'Payment for ' . $bill->bill_number,
            'description' => 'Payment to ' . $bill->vendor->name,
            'status' => 'posted',
            'entry_type' => 'bank_payment_voucher',
            'source_module' => 'bill_payment',
            'source_id' => $bill->id,
            'branch_id' => $bill->branch_id,
            'posted_at' => now(),
            'posted_by' => auth()->id(),
            'created_by' => auth()->id(),
        ]);

        // Debit vendor payable (reduce liability)
        $vendorLedger = $bill->vendor->accountLedger;
        if (!$vendorLedger) {
            throw new \Exception('Vendor account ledger not found for payment entry.');
        }
        
        JournalEntryLine::create([
            'journal_entry_id' => $entry->id,
            'account_ledger_id' => $vendorLedger->id,
            'debit' => $amount,
            'credit' => 0,
        ]);

        // Credit cash
        JournalEntryLine::create([
            'journal_entry_id' => $entry->id,
            'account_ledger_id' => $cashLedger->id,
            'debit' => 0,
            'credit' => $amount,
        ]);

        // Update ledger balances
        $bill->vendor->accountLedger->updateBalance($amount, 0);
        $cashLedger->updateBalance(0, $amount);
    }
}
