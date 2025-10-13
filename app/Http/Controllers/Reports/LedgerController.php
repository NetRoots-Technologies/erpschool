<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Accounts\AccountLedger;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function coaListing(Request $request)
    {
        // Placeholder for chart of accounts listing
        return response()->json(['success' => true, 'data' => []]);
    }

    public function toggleStatus(Request $request, $id)
    {
        $ledger = AccountLedger::findOrFail($id);
        $ledger->is_active = !$ledger->is_active;
        $ledger->save();
        
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
}
