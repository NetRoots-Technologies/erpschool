<?php

namespace App\Http\Controllers\Fee;

use App\Http\Controllers\Controller;
use App\Models\Fee\FeeHead;
use App\Models\Fee\FeeCategory;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Services\FeeHeadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class FeeHeadController extends Controller
{
    protected $feeHeadService;

    public function __construct(FeeHeadService $feeHeadService)
    {
        $this->feeHeadService = $feeHeadService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('FeeHeads-list')) {
            abort(403);
        }

        $categories = FeeCategory::where('is_active', 1)->get();
        $branches = Branch::where('status', 1);
        $companies = Company::where('status', 1);

        if (Auth::check()) {
            $user = Auth::user();

            if (!is_null($user->company_id)) {
                $branches->where('company_id', $user->company_id);
                $categories->where('company_id', $user->company_id);
            }

            if (!is_null($user->branch_id)) {
                $branches->where('id', $user->branch_id);
                $categories->where('branch_id', $user->branch_id);
            }
        }

        return view('admin.fee.fee-heads.index', [
            'categories' => $categories,
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
        if (!Gate::allows('FeeHeads-create')) {
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
        if (!Gate::allows('FeeHeads-create')) {
            abort(403);
        }
        return $this->feeHeadService->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('FeeHeads-view')) {
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
        if (!Gate::allows('FeeHeads-edit')) {
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
        if (!Gate::allows('FeeHeads-edit')) {
            abort(403);
        }
        return $this->feeHeadService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('FeeHeads-delete')) {
            abort(403);
        }
        return $this->feeHeadService->destroy($id);
    }

    public function getdata()
    {
        if (!Gate::allows('FeeHeads-list')) {
            abort(403);
        }
        return $this->feeHeadService->getdata();
    }

    public function changeStatus(Request $request)
    {
        return $this->feeHeadService->changeStatus($request);
    }

    public function handleBulkAction(Request $request)
    {
        $ids = $request->get('ids');
        FeeHead::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Bulk action completed successfully']);
    }
}