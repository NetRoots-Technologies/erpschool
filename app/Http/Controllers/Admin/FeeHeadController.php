<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Helpers\UserHelper;
use Illuminate\Http\Request;
use App\Helpers\CoreAccounts;
use App\Models\Admin\Company;
use App\Models\Admin\FeeHead;
use App\Services\LedgerService;
use App\Models\Admin\FeeSection;
use App\Services\FeeHeadService;
use App\Models\Admin\AccountHead;
use App\Models\Admin\FeeCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class FeeHeadController extends Controller
{
    protected $FeeHeadService;
    protected $coreAccounts;
    protected $ledgerService;

    public function __construct(FeeHeadService $feeHeadService, CoreAccounts $coreAccounts, LedgerService $ledgerService)
    {
        $this->FeeHeadService = $feeHeadService;
        $this->ledgerService = $ledgerService;
        $this->coreAccounts = $coreAccounts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('fee.fee_heads.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $accountHeads = AccountHead::where('status', 1)->get();
        $companies = Company::where('status', 1)->get();
        $sessions = UserHelper::session_name();
        return view('fee.fee_heads.create', compact('accountHeads', 'sessions', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        DB::beginTransaction();
        try {
            $feeHead = $this->FeeHeadService->store($request);
            $data['name'] = $request->fee_head;
            $data['account_type_id'] = 1;
            $data['parent_type_id'] = $feeHead->id;
            $data['parent_type'] = FeeHead::class;

            $feeHeads = config("constants.FixedGroups.both_fee_heads");

            foreach ($feeHeads as $key => $fee) {

                $data['parent_id'] = $fee;
                $group = $this->coreAccounts->createGroup($data);

                // $data = [
                //     'name' => $request->fee_head,
                //     'parent_type' => FeeHead::class,
                //     'parent_type_id' => $feeHead->id,
                //     'parent_id' => $fee,
                //     'branch_id' => (int) $request->get('branch_id'),
                //     'group_id' => $group['id'],
                //     'group_number' => $group['groups']['number'],
                //     'account_type_id' => $group['groups']['account_type_id'],
                //     'balanceType' => $balanceType,
                // ];

                // $this->ledgerService->createLedger($data);
                $this->ledgerService->createAutoLedgers([$group['id']] , $request->fee_head, (int) $request->get('branch_id'), FeeHead::class, $feeHead->id);

            }
            DB::commit();
            return redirect()->route('admin.fee-heads.index')->with('success', 'Fee Head, Group & Ledger created successfully');
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            Log::info($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
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
       if (!Gate::allows('students')) {
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
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeHead = FeeHead::find($id);
        $accountHeads = AccountHead::where('status', 1)->get();
        $companies = Company::where('status', 1)->get();
        $sessions = UserHelper::session_name();
        return view('fee.fee_heads.edit', compact('accountHeads', 'sessions', 'companies', 'feeHead'));
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
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            $this->FeeHeadService->update($request, $id);

            return redirect()->route('admin.fee-heads.index')->with('success', 'Fee Head updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the Fee Head');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeeHead $FeeHead): RedirectResponse
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {

            DB::beginTransaction();
            foreach ($FeeHead->groups as $group) {

                if ($group->children->count()) {
                    throw new Exception('First! Delete Group Children');
                }

                if ($group->ledgers->count()) {
                    throw new Exception('First! Delete Group Ledgers');
                }

                $group->delete();
            }

            $FeeHead->delete();

            DB::commit();
            return redirect()->back()->with('success', "Fee Head and Group deleted successfully");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function fetchFeeSection(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeSection = FeeSection::where('branch_id', $request->branch_id)->get();
        return $feeSection;
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->FeeHeadService->getdata();
    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $ids = $request->get('ids');

        foreach ($ids as $id) {
            $feeHead = FeeHead::find($id);
            if ($feeHead) {
                $feeHead->delete();
            }
        }
        return response()->json(['message', 'Bulk action completed successfully']);
    }
}
