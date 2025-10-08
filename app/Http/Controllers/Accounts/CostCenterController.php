<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\CostCenter;
use App\Models\Accounts\JournalEntryLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CostCenterController extends Controller
{
    public function index()
    {
        $costCenters = CostCenter::with(['parent', 'branch'])->latest()->paginate(20);
        return view('accounts.cost_centers.index', compact('costCenters'));
    }

    public function create()
    {
        $costCenters = CostCenter::where('is_active', true)->get();
        return view('accounts.cost_centers.create', compact('costCenters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:account_cost_centers,code',
            'type' => 'required|in:department,project,location,other',
        ]);

        CostCenter::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'branch_id' => auth()->user()->branch_id,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('accounts.cost_centers.index')->with('success', 'Cost Center created successfully');
    }

    public function edit($id)
    {
        $costCenter = CostCenter::findOrFail($id);
        $costCenters = CostCenter::where('id', '!=', $id)->where('is_active', true)->get();
        return view('accounts.cost_centers.edit', compact('costCenter', 'costCenters'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:account_cost_centers,code,' . $id,
        ]);

        $costCenter = CostCenter::findOrFail($id);
        $costCenter->update($request->all());

        return redirect()->route('accounts.cost_centers.index')->with('success', 'Cost Center updated successfully');
    }

    public function reports()
    {
        $costCenters = CostCenter::where('is_active', true)->get();
        
        $data = [];
        foreach ($costCenters as $center) {
            $expenses = JournalEntryLine::where('cost_center_id', $center->id)
                ->whereHas('journalEntry', function($q) {
                    $q->where('status', 'posted');
                })
                ->sum('debit');
            
            $data[] = [
                'cost_center' => $center,
                'total_expenses' => $expenses,
            ];
        }

        return view('accounts.cost_centers.reports', compact('data'));
    }
}
