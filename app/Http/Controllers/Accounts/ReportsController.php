<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\AccountGroup;
use App\Models\Accounts\AccountLedger;
use App\Models\Accounts\JournalEntry;
use App\Models\Accounts\JournalEntryLine;
use App\Models\Accounts\VendorBill;
use App\Models\Accounts\CustomerInvoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function trialBalance(Request $request)
    {
        $asOfDate = $request->as_of_date ?? now()->format('Y-m-d');
        
        $ledgers = AccountLedger::with('accountGroup')
            ->where('is_active', true)
            ->get()
            ->map(function($ledger) use ($asOfDate) {
                $balance = $this->calculateLedgerBalance($ledger->id, $asOfDate);
                return [
                    'ledger' => $ledger,
                    'debit' => $balance['type'] == 'debit' ? $balance['amount'] : 0,
                    'credit' => $balance['type'] == 'credit' ? $balance['amount'] : 0,
                ];
            });

        $totalDebit = collect($ledgers)->sum('debit');
        $totalCredit = collect($ledgers)->sum('credit');

        return view('accounts.reports.trial_balance', compact('ledgers', 'totalDebit', 'totalCredit', 'asOfDate'));
    }

    public function balanceSheet(Request $request)
    {
        $asOfDate = $request->as_of_date ?? now()->format('Y-m-d');
        
        $assets = $this->getGroupBalance('asset', $asOfDate);
        $liabilities = $this->getGroupBalance('liability', $asOfDate);
        $equity = $this->getGroupBalance('equity', $asOfDate);

        return view('accounts.reports.balance_sheet', compact('assets', 'liabilities', 'equity', 'asOfDate'));
    }

    public function incomeStatement(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');
        
        $revenue = $this->getGroupBalance('revenue', $endDate, $startDate);
        $expenses = $this->getGroupBalance('expense', $endDate, $startDate);
        $netIncome = $revenue['total'] - $expenses['total'];

        return view('accounts.reports.income_statement', compact('revenue', 'expenses', 'netIncome', 'startDate', 'endDate'));
    }

    public function cashFlow(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');
        
        $operating = $this->getOperatingCashFlow($startDate, $endDate);
        $investing = $this->getInvestingCashFlow($startDate, $endDate);
        $financing = $this->getFinancingCashFlow($startDate, $endDate);
        
        $netCashFlow = $operating + $investing + $financing;

        return view('accounts.reports.cash_flow', compact('operating', 'investing', 'financing', 'netCashFlow', 'startDate', 'endDate'));
    }

    public function generalLedger(Request $request)
    {
        $ledgerId = $request->ledger_id;
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');
        
        $ledger = AccountLedger::findOrFail($ledgerId);
        $transactions = JournalEntryLine::where('account_ledger_id', $ledgerId)
            ->whereHas('journalEntry', function($q) use ($startDate, $endDate) {
                $q->where('status', 'posted')
                  ->whereBetween('entry_date', [$startDate, $endDate]);
            })
            ->with('journalEntry')
            ->get();

        return view('accounts.reports.general_ledger', compact('ledger', 'transactions', 'startDate', 'endDate'));
    }

    public function agedPayables(Request $request)
    {
        $asOfDate = $request->as_of_date ?? now()->format('Y-m-d');
        
        $bills = VendorBill::with('vendor')
            ->whereIn('status', ['pending', 'partially_paid', 'overdue'])
            ->get()
            ->map(function($bill) use ($asOfDate) {
                $daysOverdue = Carbon::parse($bill->due_date)->diffInDays($asOfDate, false);
                return [
                    'bill' => $bill,
                    'days_overdue' => $daysOverdue,
                    'aging_bucket' => $this->getAgingBucket($daysOverdue),
                ];
            });

        return view('accounts.reports.aged_payables', compact('bills', 'asOfDate'));
    }

    public function agedReceivables(Request $request)
    {
        $asOfDate = $request->as_of_date ?? now()->format('Y-m-d');
        
        $invoices = CustomerInvoice::with('customer')
            ->whereIn('status', ['sent', 'partially_paid', 'overdue'])
            ->get()
            ->map(function($invoice) use ($asOfDate) {
                $daysOverdue = Carbon::parse($invoice->due_date)->diffInDays($asOfDate, false);
                return [
                    'invoice' => $invoice,
                    'days_overdue' => $daysOverdue,
                    'aging_bucket' => $this->getAgingBucket($daysOverdue),
                ];
            });

        return view('accounts.reports.aged_receivables', compact('invoices', 'asOfDate'));
    }

    public function budgetAnalysis(Request $request)
    {
        // Placeholder for budget vs actual analysis
        return view('accounts.reports.budget_analysis');
    }

    // Helper methods
    private function calculateLedgerBalance($ledgerId, $asOfDate)
    {
        $ledger = AccountLedger::findOrFail($ledgerId);
        $balance = $ledger->opening_balance;
        $type = $ledger->opening_balance_type;

        $transactions = JournalEntryLine::where('account_ledger_id', $ledgerId)
            ->whereHas('journalEntry', function($q) use ($asOfDate) {
                $q->where('status', 'posted')
                  ->where('entry_date', '<=', $asOfDate);
            })
            ->get();

        foreach ($transactions as $trans) {
            if ($type == 'debit') {
                $balance += $trans->debit - $trans->credit;
            } else {
                $balance += $trans->credit - $trans->debit;
            }
        }

        return ['amount' => abs($balance), 'type' => $balance >= 0 ? $type : ($type == 'debit' ? 'credit' : 'debit')];
    }

    private function getGroupBalance($groupType, $endDate, $startDate = null)
    {
        $ledgers = AccountLedger::whereHas('accountGroup', function($q) use ($groupType) {
            $q->where('type', $groupType);
        })->get();

        $total = 0;
        $details = [];

        foreach ($ledgers as $ledger) {
            $balance = $this->calculateLedgerBalance($ledger->id, $endDate);
            $amount = $balance['amount'];
            $total += $amount;
            $details[] = ['ledger' => $ledger, 'amount' => $amount];
        }

        return ['total' => $total, 'details' => $details];
    }

    private function getOperatingCashFlow($startDate, $endDate)
    {
        // Simplified: Net income + adjustments
        return 0; // Placeholder
    }

    private function getInvestingCashFlow($startDate, $endDate)
    {
        // Asset purchases/sales
        return 0; // Placeholder
    }

    private function getFinancingCashFlow($startDate, $endDate)
    {
        // Loans, equity
        return 0; // Placeholder
    }

    private function getAgingBucket($days)
    {
        if ($days < 0) return 'Current';
        if ($days <= 30) return '1-30 days';
        if ($days <= 60) return '31-60 days';
        if ($days <= 90) return '61-90 days';
        return '90+ days';
    }
}
