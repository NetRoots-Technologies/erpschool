<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\VendorBill;
use App\Models\Accounts\Vendor;
use App\Models\Accounts\AccountLedger;
use App\Models\Accounts\JournalEntry;
use App\Models\Accounts\JournalEntryLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayablesController extends Controller
{
    public function index()
    {
        $bills = VendorBill::with(['vendor', 'branch'])
            ->whereIn('status', ['pending', 'partially_paid', 'overdue'])
            ->latest()
            ->paginate(20);
        
        $summary = [
            'total_payable' => VendorBill::whereIn('status', ['pending', 'partially_paid', 'overdue'])->sum('balance'),
            'overdue' => VendorBill::where('status', 'overdue')->sum('balance'),
            'due_this_month' => VendorBill::where('status', 'pending')
                ->whereBetween('due_date', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('balance'),
        ];

        return view('accounts.payables.index', compact('bills', 'summary'));
    }
}
