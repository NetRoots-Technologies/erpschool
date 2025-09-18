<?php

namespace App\Http\Controllers\Fee;

use App\Http\Controllers\Controller;
use App\Models\Fee\FeeSection;
use App\Models\Fee\FeeCategory;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Services\FeeSectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class FeeSectionController extends Controller
{
    protected $feeSectionService;

    public function __construct(FeeSectionService $feeSectionService)
    {
        $this->feeSectionService = $feeSectionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('FeeSections-list')) {
            abort(403);
        }

        $categories = FeeCategory::where('is_active', 1);
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

        return view('admin.fee.fee-sections.index', [
            'categories' => $categories->get(),
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
        if (!Gate::allows('FeeSections-create')) {
            abort(403);
        }

        $categories = FeeCategory::where('is_active', 1);
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

        return view('fee.fee_section.create', [
            'categories' => $categories->get(),
            'branches' => $branches->get(),
            'companies' => $companies->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('FeeSections-create')) {
            abort(403);
        }
        return $this->feeSectionService->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('FeeSections-list')) {
            abort(403);
        }

        $feeSection = FeeSection::find($id);

        if (!$feeSection) {
            return response()->json(['error' => 'Fee section not found'], 404);
        }

        return response()->json($feeSection);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('FeeSections-edit')) {
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
        if (!Gate::allows('FeeSections-edit')) {
            abort(403);
        }
        return $this->feeSectionService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('FeeSections-delete')) {
            abort(403);
        }
        return $this->feeSectionService->destroy($id);
    }

    /**
     * Get data for DataTables
     */
    public function getdata(Request $request)
    {
        if (!Gate::allows('FeeSections-list')) {
            abort(403);
        }
        return $this->feeSectionService->getdata($request);
    }

    /**
     * Change status of fee section
     */
    public function changeStatus(Request $request)
    {
        if (!Gate::allows('FeeSections-edit')) {
            abort(403);
        }
        return $this->feeSectionService->changeStatus($request);
    }

    /**
     * Handle bulk actions
     */
    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('FeeSections-delete')) {
            abort(403);
        }
        return $this->feeSectionService->handleBulkAction($request);
    }
}