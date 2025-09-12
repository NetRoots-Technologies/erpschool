<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Payroll;
use App\Models\HR\PayrollApproval;
use App\Models\HR\SalarySlip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;
use App\Models\EmployeeChild;

class PaySlipController extends Controller
{

    public function index()
    {
       if (!Gate::allows('students')) {
            return abort(503);
        }

        return view('hr.payroll.payroll_slip_search');
    }

    public function getData(Request $request)
    {

       if (!Gate::allows('students')) {
            return abort(503);
        }

        $data = $request->all();
        $startDate = $data['salary_year'] ?? '';


        $data = SalarySlip::with('employee')->where('generated_month_year', $startDate)->get();
        //dd($data);
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('month', function ($row) {
                return Carbon::parse($row->generated_month)->format('F');
            })
            ->addColumn('year', function ($row) {
                return Carbon::parse($row->generated_month)->format('Y');
            })
            ->addColumn('created_at', function ($row) {
                if ($row->created_at) {
                    return $row->created_at;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('employee', function ($row) {
                if ($row->employee) {
                    return $row->employee->name;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('action', function ($row) {
                $action_column = '<a target="blank" href="salarySlip/' . $row->id . '" class="btn btn-xs btn-warning" style="margin: 1px;"><i class="fa fa-eye"></i> View</a>';
                return $action_column;
            })
            ->rawColumns(['action', 'employee'])
            ->make(true);
    }

    public function salarySlip($id)
    {
       if (!Gate::allows('students')) {
            return abort(503);
        }

        $SalarySlip = SalarySlip::with(['employee', 'employee.childrens.student'])->findOrFail($id);


        //dd($SalarySlip);

        $employee = $SalarySlip->employee;
        $children = $employee->childrens ?? collect();

        // Determine first child and apply fee discount
        $firstChild = $children->first(); // Get the first related child
        $feeDiscountStudent = null;
        if ($firstChild && $firstChild->student) {
            $feeDiscountStudent = $firstChild->student;
        }

        return view('hr.payroll.payroll_slip', compact('SalarySlip', 'employee', 'feeDiscountStudent'));
    }

}
