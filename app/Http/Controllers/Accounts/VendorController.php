<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Vendor;
use App\Models\Accounts\AccountLedger;
use App\Models\Accounts\AccountGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::with(['accountLedger', 'branch'])->latest()->paginate(20);
        return view('accounts.payables.vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('accounts.payables.vendors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:account_vendors,code',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Create vendor ledger
            $payablesGroup = AccountGroup::where('type', 'liability')
                ->where('name', 'LIKE', '%Payable%')
                ->first();

            if (!$payablesGroup) {
                throw new \Exception('Accounts Payable group not found. Please setup chart of accounts first.');
            }

            $ledger = AccountLedger::create([
                'name' => 'Vendor - ' . $request->name,
                'code' => 'VEN-' . $request->code,
                'account_group_id' => $payablesGroup->id,
                'opening_balance' => 0,
                'opening_balance_type' => 'credit',
                'current_balance' => 0,
                'current_balance_type' => 'credit',
                'linked_module' => 'vendor',
                'branch_id' => auth()->user()->branch_id,
                'created_by' => auth()->id(),
            ]);

            // Create vendor
            $vendor = Vendor::create([
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
                'account_ledger_id' => $ledger->id,
                'branch_id' => auth()->user()->branch_id,
                'created_by' => auth()->id(),
            ]);

            // Update ledger link
            $ledger->linked_id = $vendor->id;
            $ledger->save();

            DB::commit();
            return redirect()->route('accounts.payables.vendors.index')->with('success', 'Vendor created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('accounts.payables.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:account_vendors,code,' . $id,
            'email' => 'nullable|email',
        ]);

        $vendor = Vendor::findOrFail($id);
        $vendor->update($request->all());

        return redirect()->route('accounts.payables.vendors.index')->with('success', 'Vendor updated successfully');
    }
}
