<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\HRM\Employees;
use App\Models\EmployeeBenefit;
use Illuminate\Support\Facades\Gate;

class EmployeeBenefitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        if (!Gate::allows('EOBIReport-list') || !Gate::allows('PFReport-list') || !Gate::allows('SSReport-list')) {
            return abort(503);
        }
        $currentYear = date('Y');
        $employees = Employees::Active()->get();

        return view('hr.employee_benefit.index', compact('type', 'currentYear', 'employees'));
    }

    public function show()
    {
         if (!Gate::allows('EOBIReport-list') || !Gate::allows('PFReport-list') || !Gate::allows('SSReport-list')) {
            return abort(503);
        }
        $request = request();
        $type = $request->get('type');
        $year = $request->get('year');
        $month = Carbon::parse($request->get('month'))->format('m');
        $employee_id = $request->get('employee_id');

        $query = EmployeeBenefit::query();

        if ($type == 'EOBI') {
            $query = $query->EOBI();
        } elseif ($type == 'PF') {
            $query = $query->PF();
        } elseif ($type == 'SS') {
            $query = $query->SS();
        }

        if ($year) {
            $query = $query->where('year', $year);
        }

        if ($month) {
            $query = $query->where('month', $month);
        }

        if ($employee_id) {
            $query = $query->where('employee_id', $employee_id);
        }

        $data = $query->with('employee:id,name')->get();

        return response()->json($data);
    }

}

