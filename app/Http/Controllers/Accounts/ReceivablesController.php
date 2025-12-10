<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\CustomerInvoice;
use Illuminate\Http\Request;

class ReceivablesController extends Controller
{
    public function index()
    {

        $invoices = CustomerInvoice::with(['customer', 'student', 'branch'])
            ->whereIn('status', ['sent', 'partially_paid', 'overdue'])
            ->latest()
            ->paginate(20);

        
        $summary = [
            'total_receivable' => CustomerInvoice::whereIn('status', ['sent', 'partially_paid', 'overdue'])->sum('balance'),
            'overdue' => CustomerInvoice::where('status', 'overdue')->sum('balance'),
            'due_this_month' => CustomerInvoice::where('status', 'sent')
                ->whereBetween('due_date', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('balance'),
        ]; 

        

        return view('accounts.receivables.index', compact('invoices', 'summary'));
    }
}
