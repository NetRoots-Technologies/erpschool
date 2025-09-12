<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Company;
use App\Models\Admin\FeeCategory;
use App\Services\FeeCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FeeCategoryController extends Controller
{
    public function __construct(FeeCategoryService $feeCategoryService)
    {
        $this->FeeCategoryService = $feeCategoryService;
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
        return view('fee.fee_category.index');
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
        $companies = Company::where('status', 1)->get();
        $sessions = UserHelper::session_name();
        return view('fee.fee_category.create', compact('companies', 'sessions'));

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
        try {
            $result = $this->FeeCategoryService->store($request);

            if ($result !== null) {
                return redirect()->back()->with('error', $result);
            }

            return redirect()->route('admin.fee-category.index')->with('success', 'Fee category created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the Fee category');
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
        $feeCategory = FeeCategory::find($id);
        $companies = Company::where('status', 1)->get();
        $sessions = UserHelper::session_name();
        return view('fee.fee_category.edit', compact('feeCategory', 'companies', 'sessions'));

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

            $this->FeeCategoryService->update($request, $id);

            return redirect()->route('admin.fee-category.index')->with('success', 'Fee category Updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the Fee category');
        }
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
        try {
            $this->FeeCategoryService->destroy($id);

            return redirect()->route('admin.fee-category.index')->with('success', 'Fee category Deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while Deleting the Fee category');
        }
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->FeeCategoryService->getData();
    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $ids = $request->get('ids');

        foreach ($ids as $id) {
            $feeCategory = FeeCategory::find($id);
            if ($feeCategory) {
                $feeCategory->delete();
            }
        }
        return response()->json(['message', 'Bulk action completed successfully']);
    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $accountHead = $this->FeeCategoryService->changeStatus($request);
    }
}
