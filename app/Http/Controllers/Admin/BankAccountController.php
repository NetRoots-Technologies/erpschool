<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accounts\AccountGroup;
use App\Models\Accounts\AccountLedger;
use App\Models\Admin\Bank;
use App\Models\Admin\BankAccount;
use App\Models\Admin\BankBranch;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index()
    {
        $bankAccounts = BankAccount::with(['bank', 'branches'])->get();
        // dd($bankAccounts->toArray());
        return view('admin.bank_accounts.index', compact('bankAccounts'));
    }

    public function create()
    {
        $banks = Bank::all();
        $branches = BankBranch::all();
        // dd($branches->toArray());
        return view('admin.bank_accounts.create', compact('banks', 'branches'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'bank_id' => 'required|exists:banks,id',
            'bank_branch_id' => 'required',
            'account_no' => 'required|string|unique:banks_accounts,account_no',
            'type' => 'required|in:MOA,MCA',
        ]);

            $bankAccount = BankAccount::create($validated);

                $assetsId = AccountGroup::where('name', 'Assets')->orWhere('code', 'AST-000')->value('id');
                $bankGroup = AccountGroup::firstOrCreate(
                    ['name' => 'Bank Accounts'],
                    [
                        'code' => 'AST-'. time(),
                        'parent_id' => AccountGroup::where('name', 'Current Assets')->value('id') ?? $assetsId,
                        'description' => 'Bank accounts under current assets',
                        'is_active' => 1,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]
                );

                $bankGroupId = $bankGroup->id;

        AccountLedger::create([
            'name'                  => 'Bank - ' . $bankAccount->account_no,
            'code'                  => 'BANK-' . str_pad($bankAccount->id, 4, '0', STR_PAD_LEFT),
            'description'           => 'Ledger for bank account #' . $bankAccount->account_no,
            'account_group_id'      => $bankGroupId, // Assets → Current Assets → Bank
            'opening_balance'       => 0,
            'opening_balance_type'  => 'debit',
            'current_balance'       => 0,
            'current_balance_type'  => 'debit',
            'currency_id'           => 1, // PKR
            'is_active'             => 1,
            'is_system'             => 0,
            'linked_module'         => BankAccount::class,
            'linked_id'             => $bankAccount->id,
            'branch_id'             => $request->branch_id ?? null,
            'created_by'            => auth()->id(),
            'updated_by'            => auth()->id(),
        ]);


        return redirect()->route('admin.bank_accounts.index')->with('success', 'Bank account created successfully.');
    }

    public function edit($id)
    {
        $account = BankAccount::findOrFail($id);
        $banks = Bank::all();
        $branches = BankBranch::all();
        return view('admin.bank_accounts.edit', compact('account', 'banks', 'branches'));
    }

    public function update(Request $request, $id)
    {
        $account = BankAccount::findOrFail($id);

        $validated = $request->validate([
            'bank_id' => 'required|exists:banks,id',
            'bank_branch_id' => 'required',
            'account_no' => 'required|string|unique:banks_accounts,account_no,' . $account->id,
            'type' => 'required|in:MOA,MCA',
        ]);

        $account->update($validated);
        return redirect()->route('admin.bank_accounts.index')->with('success', 'Bank account updated successfully.');
    }

    public function destroy($id)
    {
        BankAccount::findOrFail($id)->delete();
        return redirect()->route('admin.bank_accounts.index')->with('success', 'Bank account deleted.');
    }
}
