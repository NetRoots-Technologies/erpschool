<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Services\AllowanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AllowanceController extends Controller
{
    protected $AllowanceService;
    public function __construct(AllowanceService $allowanceService)
    {
        $this->AllowanceService = $allowanceService;
    }

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('hr.allowances.index');
    }

    public function store(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $allowance = $this->AllowanceService->store($request);

    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $allowance = $this->AllowanceService->getData();
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $allowance = $this->AllowanceService->update($request, $id);
    }


    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $this->AllowanceService->destroy($id);
    }


    public function changeStatus(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $allowance = $this->AllowanceService->changeStatus($request);
    }

}

