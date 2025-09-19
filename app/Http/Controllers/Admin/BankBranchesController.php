<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\Models\Admin\Bank;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Helper\CoreAccounts;
use Illuminate\Http\Request;
use App\Models\Admin\BankBranch;
use Illuminate\Support\Facades\DB;
use App\Services\BankBranchService;
use App\Http\Controllers\Controller;

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

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $BankBranchService = $this->BankBranchService->getdata();
        return $BankBranchService;
    }

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $banks = Bank::where('status', 1)->get();
        return view('fee.bank_branches.index', compact('banks'));
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
        $request->validate([
            // 'bank_id' => 'required',
            'branch_name' => 'required|string|max:255',
            'branch_code' => 'required|string|max:255',
        ]);

        $bankBranch = $this->BankBranchService->store($request);
        $parentGroup = Group::where('parent_type_id', $request->get('bank_id'))->where("parent_type", Bank::class)->first();

        $data['name'] = Str::upper($bankBranch->branch_name."-".$bankBranch->branch_code);
        $data['parent_id'] = $parentGroup->id;
        $data['parent_type'] = BankBranch::class;
        $data['parent_type_id'] = $bankBranch->id;

        CoreAccounts::createGroup($data);

        return response()->json(["message" => "Success"]);

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
        return $BankBranchService = $this->BankBranchService->update($request, $id);
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
        return $BankBranchService = $this->BankBranchService->destroy($id);

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

