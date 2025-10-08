<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ProfitCenter;
use App\Models\Accounts\JournalEntryLine;
use App\Models\Accounts\AccountLedger;
use Illuminate\Http\Request;

class ProfitCenterController extends Controller
{
    public function index()
    {
        $profitCenters = ProfitCenter::with(['parent', 'branch'])->latest()->paginate(20);
        return view('accounts.profit_centers.index', compact('profitCenters'));
    }

    public function create()
    {
        $profitCenters = ProfitCenter::where('is_active', true)->get();
        return view('accounts.profit_centers.create', compact('profitCenters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:account_profit_centers,code',
            'type' => 'required|in:product,service,region,division,other',
        ]);

        ProfitCenter::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'branch_id' => auth()->user()->branch_id,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('accounts.profit_centers.index')->with('success', 'Profit Center created successfully');
    }

    public function edit($id)
    {
        $profitCenter = ProfitCenter::findOrFail($id);
        $profitCenters = ProfitCenter::where('id', '!=', $id)->where('is_active', true)->get();
        return view('accounts.profit_centers.edit', compact('profitCenter', 'profitCenters'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:account_profit_centers,code,' . $id,
        ]);

        $profitCenter = ProfitCenter::findOrFail($id);
        $profitCenter->update($request->all());

        return redirect()->route('accounts.profit_centers.index')->with('success', 'Profit Center updated successfully');
    }

    public function reports()
    {
        $profitCenters = ProfitCenter::where('is_active', true)->get();
        
        $data = [];
        foreach ($profitCenters as $center) {
            $revenue = JournalEntryLine::where('profit_center_id', $center->id)
                ->whereHas('journalEntry', function($q) {
                    $q->where('status', 'posted');
                })
                ->whereHas('accountLedger.accountGroup', function($q) {
                    $q->where('type', 'revenue');
                })
                ->sum('credit');
            
            $expenses = JournalEntryLine::where('profit_center_id', $center->id)
                ->whereHas('journalEntry', function($q) {
                    $q->where('status', 'posted');
                })
                ->whereHas('accountLedger.accountGroup', function($q) {
                    $q->where('type', 'expense');
                })
                ->sum('debit');
            
            $data[] = [
                'profit_center' => $center,
                'revenue' => $revenue,
                'expenses' => $expenses,
                'profit' => $revenue - $expenses,
            ];
        }

        return view('accounts.profit_centers.reports', compact('data'));
    }
}
