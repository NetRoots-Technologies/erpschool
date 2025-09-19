<?php

namespace App\Http\Controllers\Admin;

use constants;
use Exception;
use App\Models\Admin\Bank;
use App\Helper\CoreAccounts;
use Illuminate\Http\Request;
use App\Services\BankService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class BankController extends Controller
{

    protected $BankService;
    
    public function __construct(BankService $BankService)
    {
        $this->BankService = $BankService;
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
        $BankService = $this->BankService->getdata();
        return $BankService;
    }

    public function index()
    {
if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        return view('fee.banks.index');
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
        try {
            DB::beginTransaction();

            $request->validate([
                'name' => 'required',
            ]);
            
            $bank = $this->BankService->store($request);

            $data["name"] = $request->name;
            $data["parent_id"] = config('constants.FixedGroups.banks');
            $data['parent_type_id'] = $bank->id;
            $data['parent_type'] = Bank::class;

            CoreAccounts::createGroup($data);

            DB::commit();
            return response()->json(["message" => "Success"]);

        } catch (Exception $exception) {
            DB::rollback();
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
        return $Bank = $this->BankService->update($request, $id);

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
        return $Bank = $this->BankService->destroy($id);

    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $bank = Bank::find($id);
            if ($bank) {
                $bank->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $bill = $this->BankService->changeStatus($request);

    }
}

