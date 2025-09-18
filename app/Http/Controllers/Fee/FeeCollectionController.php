<?php

namespace App\Http\Controllers\Fee;

use App\Http\Controllers\Controller;
use App\Services\FeeCollectionService;
use App\Models\Fee\FeeStructure;
use App\Models\Student\Students;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class FeeCollectionController extends Controller
{
    protected $feeCollectionService;

    public function __construct(FeeCollectionService $feeCollectionService)
    {
        $this->feeCollectionService = $feeCollectionService;
    }

    public function index()
    {
        if (!Gate::allows('FeeCollection-list')) {
            return abort(403);
        }

        $students = Students::where('status', 1);
        $feeStructures = FeeStructure::where('status', 1);
        $branches = Branch::where('status', 1);
        $companies = Company::where('status', 1);

        if (Auth::check()) {
            $user = Auth::user();

            if (!is_null($user->company_id)) {
                $students->where('company_id', $user->company_id);
                $feeStructures->where('company_id', $user->company_id);
                $branches->where('company_id', $user->company_id);
            }

            if (!is_null($user->branch_id)) {
                $students->where('branch_id', $user->branch_id);
                $feeStructures->where('branch_id', $user->branch_id);
                $branches->where('id', $user->branch_id);
            }
        }

        return view('admin.fee.fee-collections.index', [
            'students' => $students->get(),
            'feeStructures' => $feeStructures->get(),
            'branches' => $branches->get(),
            'companies' => $companies->get(),
        ]);
    }

    public function create()
    {
        if (!Gate::allows('FeeCollection-create')) {
            return abort(403);
        }
        return view('fee.fee_collection.create');
    }

    public function store(Request $request)
    {
        return $this->feeCollectionService->store($request);
    }

    public function show($id)
    {
        if (!Gate::allows('FeeCollection-list')) {
            return abort(403);
        }
        $feeCollection = $this->feeCollectionService->edit($id);
        return view('fee.fee_collection.show', compact('feeCollection'));
    }

    public function edit($id)
    {
        if (!Gate::allows('FeeCollection-edit')) {
            return abort(403);
        }
        $feeCollection = $this->feeCollectionService->edit($id);
        return view('fee.fee_collection.edit', compact('feeCollection'));
    }

    public function update(Request $request, $id)
    {
        return $this->feeCollectionService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->feeCollectionService->destroy($id);
    }

    public function getdata()
    {
        return $this->feeCollectionService->getdata();
    }

    public function changeStatus(Request $request)
    {
        return $this->feeCollectionService->changeStatus($request);
    }

    public function getStudentFeeDetails(Request $request)
    {
        return $this->feeCollectionService->getStudentFeeDetails($request);
    }

    public function generateReceipt($id)
    {
        if (!Gate::allows('FeeCollection-list')) {
            return abort(403);
        }
        return $this->feeCollectionService->generateReceipt($id);
    }
}