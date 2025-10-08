<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\AccountGroup;
use App\Models\Accounts\AccountLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartOfAccountsController extends Controller
{
    public function index()
    {
        $groups = AccountGroup::with(['children', 'ledgers'])->whereNull('parent_id')->get();
        return view('accounts.chart_of_accounts.index', compact('groups'));
    }

    public function tree()
    {
        $tree = $this->buildTree();
        return view('accounts.chart_of_accounts.tree', compact('tree'));
    }

    public function create()
    {
        $groups = AccountGroup::where('is_active', true)->get();
        $ledgers = AccountLedger::where('is_active', true)->get();
        return view('accounts.chart_of_accounts.create', compact('groups', 'ledgers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:account_ledgers,code',
            'account_group_id' => 'required|exists:account_groups,id',
            'opening_balance' => 'nullable|numeric',
            'opening_balance_type' => 'required|in:debit,credit',
        ]);

        DB::beginTransaction();
        try {
            $ledger = AccountLedger::create([
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
                'account_group_id' => $request->account_group_id,
                'opening_balance' => $request->opening_balance ?? 0,
                'opening_balance_type' => $request->opening_balance_type,
                'current_balance' => $request->opening_balance ?? 0,
                'current_balance_type' => $request->opening_balance_type,
                'branch_id' => auth()->user()->branch_id,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('accounts.coa.index')->with('success', 'Account created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $ledger = AccountLedger::findOrFail($id);
        $groups = AccountGroup::where('is_active', true)->get();
        return view('accounts.chart_of_accounts.edit', compact('ledger', 'groups'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:account_ledgers,code,' . $id,
            'account_group_id' => 'required|exists:account_groups,id',
        ]);

        $ledger = AccountLedger::findOrFail($id);
        $ledger->update($request->all());

        return redirect()->route('accounts.coa.index')->with('success', 'Account updated successfully');
    }

    public function destroy($id)
    {
        $ledger = AccountLedger::findOrFail($id);
        
        if ($ledger->is_system) {
            return back()->withErrors(['error' => 'System accounts cannot be deleted']);
        }

        $ledger->delete();
        return redirect()->route('accounts.coa.index')->with('success', 'Account deleted successfully');
    }

    private function buildTree($parentId = null)
    {
        $groups = AccountGroup::where('parent_id', $parentId)
            ->where('is_active', true)
            ->with(['ledgers' => function($q) {
                $q->where('is_active', true);
            }])
            ->get();

        $tree = [];
        foreach ($groups as $group) {
            $tree[] = [
                'group' => $group,
                'children' => $this->buildTree($group->id),
            ];
        }

        return $tree;
    }
}
