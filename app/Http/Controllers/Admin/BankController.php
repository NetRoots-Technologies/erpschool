<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Bank;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::orderBy('id', 'DESC')->get();
        return view('admin.banks.index', compact('banks'));
    }

    public function create()
    {
        return view('admin.banks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Bank::create([
            'name' => $request->name,
            'status' => 1,
        ]);

        return redirect()->route('admin.banks.index')->with('success', 'Bank created successfully.');
    }

    public function edit($id)
    {
        $bank = Bank::findOrFail($id);
        return view('admin.banks.edit', compact('bank'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $bank = Bank::findOrFail($id);
        $bank->update([
            'name' => $request->name,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.banks.index')->with('success', 'Bank updated successfully.');
    }

    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();

        return redirect()->route('admin.banks.index')->with('success', 'Bank deleted successfully.');
    }
}
