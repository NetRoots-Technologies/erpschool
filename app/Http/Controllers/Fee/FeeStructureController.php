<?php

namespace App\Http\Controllers\Fee;

use App\Http\Controllers\Controller;
use App\Services\FeeStructureService;
use App\Models\Fee\FeeCategory;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Student\AcademicSession;
use App\Models\Academic\AcademicClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class FeeStructureController extends Controller
{
    protected $feeStructureService;

    public function __construct(FeeStructureService $feeStructureService)
    {
        $this->feeStructureService = $feeStructureService;
    }

    public function index()
    {
        if (!Gate::allows('FeeStructure-list')) {
            return abort(403);
        }

        $categories = FeeCategory::where('is_active', 1);
        $branches = Branch::where('status', 1);
        $companies = Company::where('status', 1);
        $sessions = AcademicSession::where('status', 1);
        $classes = AcademicClass::where('status', 1);
        $feeTerms = \App\Models\Fee\FeeTerm::where('is_active', 1);

        if (Auth::check()) {
            $user = Auth::user();

            if (!is_null($user->company_id)) {
                $branches->where('company_id', $user->company_id);
                $categories->where('company_id', $user->company_id);
                $sessions->where('company_id', $user->company_id);
                $classes->where('company_id', $user->company_id);
                $feeTerms->where('company_id', $user->company_id);
            }

            if (!is_null($user->branch_id)) {
                $branches->where('id', $user->branch_id);
                $categories->where('branch_id', $user->branch_id);
                $sessions->where('branch_id', $user->branch_id);
                $classes->where('branch_id', $user->branch_id);
                $feeTerms->where('branch_id', $user->branch_id);
            }
        }

        return view('admin.fee.fee-structures.index', [
            'categories' => $categories->get(),
            'branches' => $branches->get(),
            'companies' => $companies->get(),
            'sessions' => $sessions->get(),
            'classes' => $classes->get(),
            'feeTerms' => $feeTerms->get(),
        ]);
    }

    public function create()
    {
        if (!Gate::allows('FeeStructure-create')) {
            return abort(403);
        }
        return view('fee.fee_structure.create');
    }

    public function store(Request $request)
    {
        return $this->feeStructureService->store($request);
    }

    public function show($id)
    {
        if (!Gate::allows('FeeStructure-list')) {
            return abort(403);
        }
        $feeStructure = $this->feeStructureService->edit($id);
        return view('fee.fee_structure.show', compact('feeStructure'));
    }

    public function edit($id)
    {
        if (!Gate::allows('FeeStructure-edit')) {
            return abort(403);
        }
        $feeStructure = $this->feeStructureService->edit($id);
        return view('fee.fee_structure.edit', compact('feeStructure'));
    }

    public function update(Request $request, $id)
    {
        return $this->feeStructureService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->feeStructureService->destroy($id);
    }

    public function getdata()
    {
        return $this->feeStructureService->getdata();
    }

    public function changeStatus(Request $request)
    {
        return $this->feeStructureService->changeStatus($request);
    }

    public function handleBulkAction(Request $request)
    {
        $ids = $request->get('ids');
        $this->feeStructureService->bulkDelete($ids);
        return response()->json(['message' => 'Bulk action completed successfully']);
    }
}