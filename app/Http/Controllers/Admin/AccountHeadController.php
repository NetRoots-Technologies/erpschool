<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\CoreAccounts;
use App\Models\Admin\AccountHead;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\AccountHeadService;
use Illuminate\Support\Facades\Gate;

class AccountHeadController extends Controller
{
    protected $AccountHeadService;
    protected $coreAccounts;

    public function __construct(AccountHeadService $accountHeadService, CoreAccounts $coreAccounts)
    {
        $this->AccountHeadService = $accountHeadService;
        $this->coreAccounts = $coreAccounts;
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $accountHead = $this->AccountHeadService->getdata();
        return $accountHead;
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
        return view('admin.account_head.index');
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->AccountHeadService->store($request);

        return response()->json(['message', 'Account Head successfully Created']);

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
        return $accountHead = $this->AccountHeadService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountHead $AccountHead)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            DB::beginTransaction();

            foreach($AccountHead->groups as $group){

                if ($group->children->count()) {
                    throw new Exception('First! Delete Group Children');
                }

                if ($group->ledgers->count()) {
                    throw new Exception('First! Delete Group Ledgers');
                }

                $group->delete();
            }

            $AccountHead->delete();

            DB::commit();
            return response()->json(['message' => "Account Head and Group deleted successfully"]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $accountHead = $this->AccountHeadService->changeStatus($request);
    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $accountHead = AccountHead::find($id);
            if ($accountHead) {
                $accountHead->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }
}
