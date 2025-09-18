<?php

namespace App\Http\Controllers\Fee;

use App\Http\Controllers\Controller;
use App\Models\Fee\FeeCategory;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Services\FeeCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class FeeCategoryController extends Controller
{
    protected $feeCategoryService;

    public function __construct(FeeCategoryService $feeCategoryService)
    {
        $this->feeCategoryService = $feeCategoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('FeeCategory-list')) {
            abort(403);
        }

        $branches = Branch::where('status', 1);
        $companies = Company::where('status', 1);

        if (Auth::check()) {
            $user = Auth::user();

            if (!is_null($user->company_id)) {
                $branches->where('company_id', $user->company_id);
            }

            if (!is_null($user->branch_id)) {
                $branches->where('id', $user->branch_id);
            }
        }

        return view('admin.fee.fee-categories.index', [
            'branches' => $branches->get(),
            'companies' => $companies->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('FeeCategory-create')) {
            abort(403);
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
        if (!Gate::allows('FeeCategory-create')) {
            abort(403);
        }
        return $this->feeCategoryService->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('FeeCategory-view')) {
            abort(403);
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
        if (!Gate::allows('FeeCategory-edit')) {
            abort(403);
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
        if (!Gate::allows('FeeCategory-edit')) {
            abort(403);
        }
        return $this->feeCategoryService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('FeeCategory-delete')) {
            abort(403);
        }
        return $this->feeCategoryService->destroy($id);
    }

    public function getdata()
    {
        if (!Gate::allows('FeeCategory-list')) {
            abort(403);
        }
        return $this->feeCategoryService->getdata();
    }

    public function changeStatus(Request $request)
    {
        return $this->feeCategoryService->changeStatus($request);
    }

    public function handleBulkAction(Request $request)
    {
        $ids = $request->get('ids');
        FeeCategory::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Bulk action completed successfully']);
    }
}