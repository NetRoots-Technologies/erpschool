<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\Models\Admin\Bank;
use Illuminate\Support\Str;
use App\Helper\CoreAccounts;
use Illuminate\Http\Request;
use App\Models\Admin\BankBranch;
use Illuminate\Support\Facades\DB;
use App\Services\BankBranchService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class BankBranchesController extends Controller
{
    protected $BankBranchService;

    public function __construct(BankBranchService $BankBranchService)
    {
        $this->BankBranchService = $BankBranchService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    

    public function index()
{
    $branches = BankBranch::with('bank')->orderBy('id', 'DESC')->get();

    return view('admin.bank_branches.index', compact('branches'));
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banks = Bank::where('status', 1)->get();

        return view('admin.bank_branches.create' ,  compact('banks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(Request $request)
{
    $request->validate([
        'bank_id' => 'required|exists:banks,id',
        'branch_name' => 'required|string|max:255',
        'branch_code' => 'required|string|max:50',
    ]);

    BankBranch::create([
        'bank_id' => $request->bank_id,
        'branch_name' => $request->branch_name,
        'branch_code' => $request->branch_code,
        'status' => 1
    ]);

    return redirect()->route('admin.banks_branches.index')->with('success', 'Branch created successfully.');
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function edit($id)
{
    if (!Gate::allows('Dashboard-list')) {
        return abort(503);
    }

    $branch = BankBranch::findOrFail($id);
    $banks = Bank::where('status', 1)->get();

    return view('admin.bank_branches.edit', compact('branch', 'banks'));
}

public function update(Request $request, $id)
{
    if (!Gate::allows('Dashboard-list')) {
        return abort(503);
    }

    $request->validate([
        'bank_id' => 'required|exists:banks,id',
        'branch_name' => 'required|string|max:255',
        'branch_code' => 'required|string|max:50',
        'status' => 'required|boolean',
    ]);

    $branch = BankBranch::findOrFail($id);

    $branch->update([
        'bank_id' => $request->bank_id,
        'branch_name' => $request->branch_name,
        'branch_code' => $request->branch_code,
        'status' => $request->status,
    ]);

    return redirect()->route('admin.banks_branches.index')->with('success', 'Branch updated successfully.');
}

public function destroy($id)
{
    if (!Gate::allows('Dashboard-list')) {
        return abort(503);
    }

    $branch = BankBranch::findOrFail($id);
    $branch->delete();

    return redirect()->route('admin.banks_branches.index')->with('success', 'Branch deleted successfully.');
}


    public function changeStatus(Request $request)
    {

        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $feeFactor = $this->BankBranchService->changeStatus($request);
    }

    public function fetchBankBranch(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $banks = BankBranch::where('bank_id', $request->bank_id)->get();

        return response()->json($banks);
    }
}

