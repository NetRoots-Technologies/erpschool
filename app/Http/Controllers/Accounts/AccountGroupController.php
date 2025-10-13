<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\AccountGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountGroupController extends Controller
{
    public function index()
    {
        $groups = AccountGroup::with('parent')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->get();
        
        return view('accounts.groups.index', compact('groups'));
    }

    public function create()
    {
        $parentGroups = AccountGroup::where('is_active', true)
            ->whereNull('parent_id')
            ->get();
        
        return view('accounts.groups.create', compact('parentGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:account_groups,code',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
        ]);

        DB::beginTransaction();
        try {
            $level = 1;
            if ($request->parent_id) {
                $parent = AccountGroup::find($request->parent_id);
                $level = $parent ? $parent->level + 1 : 1;
            }

            AccountGroup::create([
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
                'type' => $request->type,
                'parent_id' => $request->parent_id,
                'level' => $level,
                'is_active' => true,
                'branch_id' => auth()->user()->branch_id ?? null,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('accounts.groups.index')
                ->with('success', 'Account Group created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $group = AccountGroup::findOrFail($id);
        $parentGroups = AccountGroup::where('id', '!=', $id)
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->get();
        
        return view('accounts.groups.edit', compact('group', 'parentGroups'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:account_groups,code,' . $id,
            'type' => 'required|in:asset,liability,equity,revenue,expense',
        ]);

        DB::beginTransaction();
        try {
            $group = AccountGroup::findOrFail($id);
            
            $level = $group->level;
            if ($request->parent_id && $request->parent_id != $group->parent_id) {
                $parent = AccountGroup::find($request->parent_id);
                $level = $parent ? $parent->level + 1 : 1;
            }

            $group->update([
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
                'type' => $request->type,
                'parent_id' => $request->parent_id,
                'level' => $level,
                'updated_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('accounts.groups.index')
                ->with('success', 'Account Group updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $group = AccountGroup::findOrFail($id);
        
        // Check if group has ledgers
        if ($group->ledgers()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete group with existing ledgers']);
        }

        // Check if group has children
        if ($group->children()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete group with sub-groups']);
        }

        $group->delete();
        return redirect()->route('accounts.groups.index')
            ->with('success', 'Account Group deleted successfully');
    }
}

