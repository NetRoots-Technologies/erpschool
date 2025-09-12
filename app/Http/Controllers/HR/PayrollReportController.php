<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Payroll;
use App\Models\HR\SalarySlip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PayrollReportController extends Controller
{
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        return view('hr.payroll.payrollReport');
    }

    public function fetchFilterRecord(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $payrolls = SalarySlip::where('generated_month_year', $request->month_year)->get();
        return view('hr.payroll.payrollReport', compact('payrolls'));
    }
}
