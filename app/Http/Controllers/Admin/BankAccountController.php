<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BankAccount;
use App\Models\Admin\Bank;
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

        BankAccount::create($validated);


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
