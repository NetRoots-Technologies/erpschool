<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Company;
use App\Models\Admin\FeeTerm;
use App\Services\FeeTermService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FeeTermController extends Controller
{
    public function __construct(FeeTermService $feeTermService)
    {
        $this->FeeTermService = $feeTermService;
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
        return view('fee.fee_term.index');
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
        $sessions = UserHelper::session_name();
        $companies = Company::where('status', 1)->get();
        $terms = UserHelper::classTerms();
        return view('fee.fee_term.create', compact('sessions', 'companies', 'terms'));

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
        $response = $this->FeeTermService->store($request);

        if ($response === 'success') {
            return redirect()->route('admin.fee-terms.index')->with('success', 'Fee Term created successfully');
        } elseif ($response === 'exists') {
            return redirect()->back()->with('error', 'Fee Term already exists against this Class');
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
        $feeTerm = FeeTerm::find($id);
        $sessions = UserHelper::session_name();
        $companies = Company::where('status', 1)->get();
        $terms = UserHelper::classTerms();
        return view('fee.fee_term.edit', compact('sessions', 'companies', 'terms', 'feeTerm'));
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
        $this->FeeTermService->update($request, $id);

        return redirect()->route('admin.fee-terms.index')->with('success', 'Fee Term update successfully');

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
        $this->FeeTermService->destroy($id);

        return redirect()->route('admin.fee-terms.index')->with('success', 'Fee Term deleted successfully');
    }

    public function feeTermData(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        dd($request->all());
    }
    public function getData()
    {
        return $this->FeeTermService->getData();
    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $voucher = FeeTerm::find($id);
            if ($voucher) {
                $voucher->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }

}
