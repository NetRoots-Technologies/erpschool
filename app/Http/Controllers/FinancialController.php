<?php

namespace App\Http\Controllers;

use App\Models\Financial;
use Illuminate\Http\Request;
use App\Services\FinancialService;
use Illuminate\Support\Facades\Gate;

class FinancialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $FinancialService;

    public function __construct(FinancialService $financialService)
    {
        $this->FinancialService = $financialService;
    }


    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $financial = $this->FinancialService->getdata();
        return $financial;
    }

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('admin.financial_years.index');
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
        return $financial = $this->FinancialService->store($request);

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
        return $Company = $this->FinancialService->update($request, $id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->FinancialService->destroy($id);
    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $financial = $this->FinancialService->changeStatus($request);
    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $ids = $request->get('ids');

        foreach ($ids as $id) {
            $financial = Financial::find($id);
            if ($financial) {
                $financial->delete();
            }
        }
        return response()->json(['message', 'Bulk action completed successfully']);
    }
}
