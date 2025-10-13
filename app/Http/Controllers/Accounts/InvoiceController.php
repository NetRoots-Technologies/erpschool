<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\CustomerInvoice;
use App\Models\Accounts\Customer;
use App\Models\Accounts\JournalEntry;
use App\Models\Accounts\JournalEntryLine;
use App\Models\Accounts\AccountLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = CustomerInvoice::with(['customer', 'branch'])->latest()->paginate(20);
        return view('accounts.receivables.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        return view('accounts.receivables.invoices.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:account_customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $invoice = CustomerInvoice::create([
                'invoice_number' => CustomerInvoice::generateNumber(),
                'customer_id' => $request->customer_id,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'reference' => $request->reference,
                'subtotal' => $request->subtotal,
                'tax_amount' => $request->tax_amount ?? 0,
                'discount' => $request->discount ?? 0,
                'total_amount' => $request->total_amount,
                'balance' => $request->total_amount,
                'status' => 'sent',
                'notes' => $request->notes,
                'branch_id' => auth()->user()->branch_id ?? null,
                'created_by' => auth()->id(),
            ]);

            // Load the customer relationship with accountLedger
            $invoice->load('customer.accountLedger');

            // Create journal entry
            $this->createJournalEntry($invoice);

            DB::commit();
            return redirect()->route('accounts.receivables.invoices.index')->with('success', 'Invoice created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $invoice = CustomerInvoice::with(['customer', 'journalEntry'])->findOrFail($id);
        return view('accounts.receivables.invoices.show', compact('invoice'));
    }

    public function receive(Request $request, $id)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
        ]);

        $invoice = CustomerInvoice::findOrFail($id);

        DB::beginTransaction();
        try {
            $invoice->received_amount += $request->payment_amount;
            $invoice->balance = $invoice->total_amount - $invoice->received_amount;
            $invoice->updateStatus();
            $invoice->save();

            // Create payment journal entry
            $this->createPaymentEntry($invoice, $request->payment_amount, $request->payment_date);

            DB::commit();
            return redirect()->route('accounts.receivables.invoices.show', $id)->with('success', 'Payment received successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    private function createJournalEntry($invoice)
    {
        // Get or create revenue ledger
        $revenueLedger = AccountLedger::where('linked_module', 'revenue')->first();
        
        if (!$revenueLedger) {
            // Try to find any revenue ledger
            $revenueLedger = AccountLedger::whereHas('accountGroup', function($q) {
                $q->where('type', 'revenue');
            })->first();
            
            // If still not found, create one
            if (!$revenueLedger) {
                $revenueLedger = AccountLedger::create([
                    'name' => 'General Revenue',
                    'code' => 'REV-GEN-' . time(),
                    'description' => 'General business revenue',
                    'account_group_id' => 12, // Revenue
                    'opening_balance' => 0,
                    'opening_balance_type' => 'credit',
                    'current_balance' => 0,
                    'current_balance_type' => 'credit',
                    'linked_module' => 'revenue',
                    'is_active' => true,
                    'created_by' => auth()->id() ?? 1
                ]);
                \Log::info("Revenue ledger auto-created for invoice");
            }
        }
        
        $entry = JournalEntry::create([
            'entry_number' => JournalEntry::generateNumber(),
            'entry_date' => $invoice->invoice_date,
            'reference' => $invoice->invoice_number,
            'description' => 'Invoice to ' . $invoice->customer->name,
            'status' => 'posted',
            'entry_type' => 'journal',
            'source_module' => 'customer_invoice',
            'source_id' => $invoice->id,
            'branch_id' => $invoice->branch_id,
            'posted_at' => now(),
            'posted_by' => auth()->id(),
            'created_by' => auth()->id(),
        ]);

        // Debit customer receivable
        JournalEntryLine::create([
            'journal_entry_id' => $entry->id,
            'account_ledger_id' => $invoice->customer->accountLedger->id,
            'debit' => $invoice->total_amount,
            'credit' => 0,
        ]);

        // Credit revenue
        JournalEntryLine::create([
            'journal_entry_id' => $entry->id,
            'account_ledger_id' => $revenueLedger->id,
            'debit' => 0,
            'credit' => $invoice->total_amount,
        ]);

        $invoice->journal_entry_id = $entry->id;
        $invoice->save();

        // Update ledger balances
        $invoice->customer->accountLedger->updateBalance($invoice->total_amount, 0);
        $revenueLedger->updateBalance(0, $invoice->total_amount);
    }

    private function createPaymentEntry($invoice, $amount, $date)
    {
        $cashLedger = AccountLedger::where('name', 'LIKE', '%Cash%')->first();
        
        if (!$cashLedger) {
            throw new \Exception('Cash ledger not found. Please create a cash account ledger first.');
        }
        
        $entry = JournalEntry::create([
            'entry_number' => JournalEntry::generateNumber(),
            'entry_date' => $date,
            'reference' => 'Payment for ' . $invoice->invoice_number,
            'description' => 'Payment from ' . $invoice->customer->name,
            'status' => 'posted',
            'entry_type' => 'receipt',
            'source_module' => 'invoice_payment',
            'source_id' => $invoice->id,
            'branch_id' => $invoice->branch_id,
            'posted_at' => now(),
            'posted_by' => auth()->id(),
            'created_by' => auth()->id(),
        ]);

        // Debit cash
        JournalEntryLine::create([
            'journal_entry_id' => $entry->id,
            'account_ledger_id' => $cashLedger->id,
            'debit' => $amount,
            'credit' => 0,
        ]);

        // Credit customer receivable
        JournalEntryLine::create([
            'journal_entry_id' => $entry->id,
            'account_ledger_id' => $invoice->customer->accountLedger->id,
            'debit' => 0,
            'credit' => $amount,
        ]);

        // Update ledger balances
        $cashLedger->updateBalance($amount, 0);
        $invoice->customer->accountLedger->updateBalance(0, $amount);
    }
}
