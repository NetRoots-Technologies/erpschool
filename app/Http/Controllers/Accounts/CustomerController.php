<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Customer;
use App\Models\Accounts\AccountLedger;
use App\Models\Accounts\AccountGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with(['accountLedger', 'branch'])->latest()->paginate(20);
        return view('accounts.receivables.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('accounts.receivables.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:account_customers,code',
            'email' => 'nullable|email',
        ]);

        DB::beginTransaction();
        try {
            // Create customer ledger
            $receivablesGroup = AccountGroup::where('type', 'asset')
                ->where('name', 'LIKE', '%Receivable%')
                ->first();

            if (!$receivablesGroup) {
                throw new \Exception('Accounts Receivable group not found. Please setup chart of accounts first.');
            }

            $ledger = AccountLedger::create([
                'name' => 'Customer - ' . $request->name,
                'code' => 'CUS-' . $request->code,
                'account_group_id' => $receivablesGroup->id,
                'opening_balance' => 0,
                'opening_balance_type' => 'debit',
                'current_balance' => 0,
                'current_balance_type' => 'debit',
                'linked_module' => 'customer',
                'branch_id' => auth()->user()->branch_id,
                'created_by' => auth()->id(),
            ]);

            // Create customer
            $customer = Customer::create([
                'name' => $request->name,
                'code' => $request->code,
                'email' => $request->email,
                'phone' => $request->phone,
                'contact_person' => $request->contact_person,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'tax_number' => $request->tax_number,
                'payment_terms' => $request->payment_terms,
                'credit_limit' => $request->credit_limit ?? 0,
                'account_ledger_id' => $ledger->id,
                'branch_id' => auth()->user()->branch_id,
                'created_by' => auth()->id(),
            ]);

            // Update ledger link
            $ledger->linked_id = $customer->id;
            $ledger->save();

            DB::commit();
            return redirect()->route('accounts.receivables.customers.index')->with('success', 'Customer created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('accounts.receivables.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:account_customers,code,' . $id,
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return redirect()->route('accounts.receivables.customers.index')->with('success', 'Customer updated successfully');
    }
}
