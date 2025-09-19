<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Financial;
use App\Models\HR\TaxSlab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class SalaryTaxController extends Controller
{
    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('hr.salary_tax.index');
    }

    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $taxes = TaxSlab::where('tax_type', 'salaryTax')->get();

        $financialYears = Financial::where('status', 1)->get();

        $formattedFinancialYears = [];
        foreach ($financialYears as $financialYear) {
            $formattedFinancialYears[$financialYear->id] = $financialYear->name . ' ' . date('y', strtotime($financialYear->start_date)) . '-' . date('y', strtotime($financialYear->end_date));
        }


        return view('hr.salary_tax.create', compact('taxes', 'formattedFinancialYears'));

    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = TaxSlab::with('financial')->where('tax_type', 'salaryTax')->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('financialYear', function ($row) {
                if ($row->financial_year_id) {
                    $financialYear = $row->financial;
                    return $financialYear->name . ' ' . date('y', strtotime($financialYear->start_date)) . '-' . date('y', strtotime($financialYear->end_date));
                } else {
                    return "N/A";
                }
            })
            ->addColumn('fix_amount', function ($row) {
                if ($row->fix_amount) {
                    return $row->fix_amount;
                } else {
                    return "N/A";
                }
            })
            ->rawColumns(['financialYear', 'fix_amount'])
            ->make(true);
    }

}

