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
        $groups = AccountGroup::with(['ledgers' => function($query) {
            $query->orderBy('code');
        }])
        ->orderBy('name')
        ->get();

        // Add summary statistics
        $stats = [
            'total_groups' => $groups->count(),
            'total_ledgers' => $groups->sum(function($group) {
                return $group->ledgers->count();
            }),
            'active_ledgers' => $groups->sum(function($group) {
                return $group->ledgers->where('is_active', true)->count();
            }),
        ];

        return view('accounts.chart_of_accounts.index', compact('groups', 'stats'));
    }

    // public function tree()
    // {
    //     $groups = AccountGroup::with(['ledgers' => function($query) {
    //         $query->orderBy('code');
    //     }])
    //     ->orderBy('name')
    //     ->get();
        
    //     $tree = $this->buildTree();
    //     return view('accounts.chart_of_accounts.tree', compact('groups', 'tree'));
    // }


    public function tree()
        {
            // $groups = AccountGroup::with('ledgers')->whereNull('parent_id')->orderBy('name')->get();
             $groups = AccountGroup::with(['ledgers', 'childrenRecursive'])->whereNull('parent_id')->orderBy('name')->get();
            // $tree = $this->buildTree($groups);
            return view('accounts.chart_of_accounts.tree', compact('groups'));
        }

        private function buildTree($groups)
        {
            $tree = [];
            foreach ($groups as $group) {
                $children = $group->children()->with('ledgers')->orderBy('name')->get();
                $tree[] = [
                    'id' => $group->id,
                    'name' => $group->name,
                    'ledgers' => $group->ledgers,
                    'children' => $children->count() ? $this->buildTree($children) : []
                ];
            }
            return $tree;
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
                'account_group_id' => 'required|exists:account_groups,id',
                'opening_balance' => 'nullable|numeric',
                'opening_balance_type' => 'required|in:debit,credit',
            ]);
            // dd($request->all());

            DB::beginTransaction();
            
            try {
                // ✅ Determine correct parent ID according to available level
                $parentId = $request->parent_id_level4 
                    ?? $request->parent_id_level3 
                    ?? $request->parent_id_level2 
                    ?? $request->account_group_id;

                // Get the selected parent group
                $parentGroup = AccountGroup::findOrFail($parentId);

                // Check if request has code
                if ($request->filled('code')) {
                    $newCode = $request->code; // Use provided code
                } else {
                    // Otherwise generate new code
                   // Generate next account code
                    $newCode = $this->generateNextGroupCode($parentGroup);
                }

                // Create new group
                $newGroup = AccountGroup::create([
                    'name'        => $request->name,
                    'code'        => $newCode,
                    'description' => $request->description,
                    'type'        => $parentGroup->type,
                    'parent_id'   => $parentId, // ✅ Always correct parent ID
                    'is_active'   => 1,
                    'created_by'  => auth()->id(),
                ]);

                // Create ledger under the new group
                $ledger = AccountLedger::create([
                    'name'                 => $request->name,
                    'code'                 => $newCode,
                    'description'          => $request->description,
                    'account_group_id'     => $newGroup->id,
                    'opening_balance'      => $request->opening_balance ?? 0,
                    'opening_balance_type' => $request->opening_balance_type,
                    'current_balance'      => $request->opening_balance ?? 0,
                    'current_balance_type' => $request->opening_balance_type,
                    'branch_id'            => auth()->user()->branch_id,
                    'created_by'           => auth()->id(),
                ]);

                DB::commit();

                return redirect()->route('accounts.coa.index')
                    ->with('success', 'Account created successfully');

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

    // private function buildTree($parentId = null)
    // {
    //     $groups = AccountGroup::where('parent_id', $parentId)
    //         ->where('is_active', true)
    //         ->with(['ledgers' => function($q) {
    //             $q->where('is_active', true);
    //         }])
    //         ->get();

    //     $tree = [];
    //     foreach ($groups as $group) {
    //         $tree[] = [
    //             'group' => $group,
    //             'children' => $this->buildTree($group->id),
    //         ];
    //     }

    //     return $tree;
    // }

    public function getChildGroups(Request $request)
    {
        $groups = AccountGroup::where('parent_id', $request->parent_id)->get(['id','name']);
        // dd($groups->toArray());
        return response()->json($groups);
    }
    public function getthirdchild(Request $request)
    {
        $groups = AccountGroup::where('parent_id', $request->parent_id)->get(['id','name']);
        // dd($groups->toArray());
        return response()->json($groups);
    }

        private function generateNextGroupCode($parentGroup)
        {
            $prefix = $this->getGroupPrefix($parentGroup);

            // Find the last code globally (not just under same parent)
            $lastGroup = AccountGroup::where('code', 'like', $prefix . '-%')
                ->orderBy('code', 'desc')
                ->first();

            if (!$lastGroup) {
                return $prefix . '-001';
            }

            if (preg_match('/(\d+)$/', $lastGroup->code, $matches)) {
                $lastNum = (int) $matches[1];
                $newNum = str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
                return $prefix . '-' . $newNum;
            }

            return $prefix . '-001';
        }



    // Add this private method inside your ChartOfAccountsController
    private function getGroupPrefix($group)
    {
        // Traverse up to the top-level parent
        $topGroup = $group;
        while ($topGroup->parent_id) {
            $topGroup = AccountGroup::find($topGroup->parent_id);
        }

        // Use first 3 letters of top-level group type or name as prefix
        $prefix = strtoupper(substr($topGroup->type ?? $topGroup->name, 0, 3));

        return $prefix;
    }


            
}
