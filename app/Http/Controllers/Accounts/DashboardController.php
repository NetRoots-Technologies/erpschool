<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\JournalEntry;
use App\Models\Accounts\VendorBill;
use App\Models\Accounts\CustomerInvoice;
use App\Models\Accounts\AccountLedger;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_assets' => $this->getTotalAssets(),
            'total_liabilities' => $this->getTotalLiabilities(),
            'total_revenue' => $this->getTotalRevenue(),
            'total_expenses' => $this->getTotalExpenses(),
            'accounts_payable' => VendorBill::whereIn('status', ['pending', 'partially_paid', 'overdue'])->sum('balance'),
            'accounts_receivable' => CustomerInvoice::whereIn('status', ['sent', 'partially_paid', 'overdue'])->sum('balance'),
            'recent_entries' => JournalEntry::with('lines')->latest()->take(10)->get(),
            'overdue_bills' => VendorBill::where('status', 'overdue')->count(),
            'overdue_invoices' => CustomerInvoice::where('status', 'overdue')->count(),
        ];

        return view('accounts.dashboard', $data);
    }

    private function getTotalAssets()
    {
        return AccountLedger::whereHas('accountGroup', function($q) {
            $q->where('type', 'asset');
        })->where('current_balance_type', 'debit')->sum('current_balance');
    }

    private function getTotalLiabilities()
    {
        return AccountLedger::whereHas('accountGroup', function($q) {
            $q->where('type', 'liability');
        })->where('current_balance_type', 'credit')->sum('current_balance');
    }

    private function getTotalRevenue()
    {
        return AccountLedger::whereHas('accountGroup', function($q) {
            $q->where('type', 'revenue');
        })->where('current_balance_type', 'credit')->sum('current_balance');
    }

    private function getTotalExpenses()
    {
        return AccountLedger::whereHas('accountGroup', function($q) {
            $q->where('type', 'expense');
        })->where('current_balance_type', 'debit')->sum('current_balance');
    }
}
