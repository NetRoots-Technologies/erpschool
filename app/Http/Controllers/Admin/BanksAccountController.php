<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\Models\Admin\Bank;
use Illuminate\Http\Request;
use App\Models\Admin\Ledgers;
use App\Services\LedgerService;
use App\Models\Admin\BankBranch;
use App\Models\Admin\BankAccount;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\BankAccountService;
use Illuminate\Support\Facades\Gate;

class BanksAccountController extends Controller
{
    protected $BankAccountService;
    protected $ledgerService;

    public function __construct(BankAccountService $bankAccountService, LedgerService $ledgerService)
    {
        $this->BankAccountService = $bankAccountService;
        $this->ledgerService = $ledgerService;
    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $BankAccount = $this->BankAccountService->getdata();
        return $BankAccount;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('fee.banks_account.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $banks = Bank::where('status', 1)->get();
        return view('fee.banks_account.create', compact('banks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        DB::beginTransaction();
        $request->validate([
            'bank_id' => 'required',
            'bank_branch_id' => 'required',
            'bank_account_no' => 'required|unique:banks_accounts,account_no',
            'type' => 'required',
        ]);
        try{

        $bankAccount = $this->BankAccountService->store($request);
        $bank = Bank::find($request->get('bank_id'));
        $bankBranch = BankBranch::find($request->get('bank_branch_id'));
        $group = Group::where("parent_type_id",$request->get('bank_branch_id'))->where('parent_type',BankBranch::class)->first();
            // dd(BankBranch::class);
            // dd($group);
        $name = $bankAccount->account_no."-".$bank->name."-".$bankBranch->branch_name."-".$bankAccount->type;
        // $ledger['parent_type'] = BankAccount::class;
        // $ledger['parent_type_id'] = $bankAccount->id;
        // $ledger['parent_id'] = $bankAccount->id;
        // // $ledger['group_id'] = $group->id;
        // $ledger['group_number'] = $group->number;
        // $ledger['account_type_id'] = $group->account_type_id;
        // $ledger['balanceType'] = "d";
        // $ledger['dl_balance_type'] = "d";
        // $ledger['gl_balance_type'] = "d";
        // $ledger['branch_id'] = 0;
        // $this->ledgerService->createLedger($ledger);
        $this->ledgerService->createAutoLedgers([$group->id] ,$name, 0, BankAccount::class, $bankAccount->id);
        DB:: commit();
        return redirect()->route('admin.banks_accounts.index')->with('success', 'Bank Account Created Successfully');
    } catch(\Exception $ex){
        dd($ex);
        DB::rollback();
        return redirect()->back()->with('error', $ex->getMessage());
    }

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
        $bankAccount = BankAccount::find($id);
        $banks = Bank::where('status', 1)->get();
        return view('fee.banks_account.edit', compact('banks', 'bankAccount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $bankAccount = $this->BankAccountService->update($request, $id);

        $ledger = Ledgers::where('parent_type', BankAccount::class)
        ->where('parent_type_id', $bankAccount->id)->first();

        $bank = Bank::find($request->get('bank_id'));
        $bankBranch = BankBranch::find($request->get('bank_branch_id'));

        $ledger->name = $bankAccount->account_no."-".$bank->name."-".$bankBranch->branch_name."-".$bankAccount->type;
        $ledger->save();

        return redirect()->route('admin.banks_accounts.index')->with('success', 'Bank Account Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->BankAccountService->delete($id);
        return redirect()->route('admin.banks_accounts.index')->with('success', 'Bank Account deleted Successfully');
    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $bank = BankAccount::find($id);
            if ($bank) {
                $bank->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }
}

