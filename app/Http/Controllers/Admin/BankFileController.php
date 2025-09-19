<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\BankImport;
use App\Models\Admin\BankAccount;
use App\Models\Admin\BankBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class BankFileController extends Controller
{
    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $bank_list = BankAccount::with('bank', 'bankBranch')->get();

        $formatted_banks = [];

        foreach ($bank_list as $bank_branch) {
            $bank_name = @$bank_branch->bank->name;
            $branch_name = $bank_branch->bankBranch->branch_name ?? null;
            $branch_account_no = $bank_branch->account_no;

            $formatted_banks[$bank_branch->id] = $bank_name . ' (' . $branch_name . ' - ' . $branch_account_no . ')';
        }

        return view('fee.banks_file.index', compact('formatted_banks'));
    }

    public function import(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        Excel::import(new BankImport(), $request->file('excel_file'));

        return back()->with('success', 'Users imported successfully.');
    }


}

