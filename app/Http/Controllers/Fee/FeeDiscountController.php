<?php

namespace App\Http\Controllers\Fee;

use App\Http\Controllers\Controller;
use App\Services\FeeDiscountService;
use App\Models\Fee\FeeCategory;
use App\Models\Student\Students;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class FeeDiscountController extends Controller
{
    protected $feeDiscountService;

    public function __construct(FeeDiscountService $feeDiscountService)
    {
        $this->feeDiscountService = $feeDiscountService;
    }

    public function index()
    {
        if (!Gate::allows('FeeDiscount-list')) {
            return abort(403);
        }

        $students = Students::where('status', 1);
        $categories = FeeCategory::where('is_active', 1);
        $branches = Branch::where('status', 1);
        $companies = Company::where('status', 1);

        if (Auth::check()) {
            $user = Auth::user();

            if (!is_null($user->company_id)) {
                $students->where('company_id', $user->company_id);
                $categories->where('company_id', $user->company_id);
                $branches->where('company_id', $user->company_id);
            }

            if (!is_null($user->branch_id)) {
                $students->where('branch_id', $user->branch_id);
                $categories->where('branch_id', $user->branch_id);
                $branches->where('id', $user->branch_id);
            }
        }

        return view('admin.fee.fee-discounts.index', [
            'students' => $students->get(),
            'categories' => $categories->get(),
            'branches' => $branches->get(),
            'companies' => $companies->get(),
        ]);
    }

    public function create()
    {
        if (!Gate::allows('FeeDiscount-create')) {
            return abort(403);
        }
        return view('fee.fee_discount.create');
    }

    public function store(Request $request)
    {
        return $this->feeDiscountService->store($request);
    }

    public function show($id)
    {
        if (!Gate::allows('FeeDiscount-list')) {
            return abort(403);
        }
        $feeDiscount = $this->feeDiscountService->edit($id);
        return view('fee.fee_discount.show', compact('feeDiscount'));
    }

    public function edit($id)
    {
        if (!Gate::allows('FeeDiscount-edit')) {
            return abort(403);
        }
        $feeDiscount = $this->feeDiscountService->edit($id);
        return view('fee.fee_discount.edit', compact('feeDiscount'));
    }

    public function update(Request $request, $id)
    {
        return $this->feeDiscountService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->feeDiscountService->destroy($id);
    }

    public function getdata()
    {
        return $this->feeDiscountService->getdata();
    }

    public function changeStatus(Request $request)
    {
        return $this->feeDiscountService->changeStatus($request);
    }

    public function applyBulkDiscount(Request $request)
    {
        return $this->feeDiscountService->applyBulkDiscount($request);
    }
}