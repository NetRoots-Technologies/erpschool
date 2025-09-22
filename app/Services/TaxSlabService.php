<?php

namespace App\Services;


use App\Models\HR\TaxSlab;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class TaxSlabService
{


    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        TaxSlab::truncate();

        foreach ($request->fix_amount as $key => $value) {

            TaxSlab::create([
                'financial_year_id' => $request->financial_year_id[$key],
                'fix_amount' => $value,
                'tax_percent' => $request->tax_percent[$key],
                'start_range' => $request->start_range[$key],
                'end_range' => $request->end_range[$key],
                'tax_type' => $request->tax_type[$key],
            ]);
        }

    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = TaxSlab::with('financial')->get();

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
            ->rawColumns(['financialYear'])
            ->make(true);
    }

}

