<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Financial;
use App\Models\HR\TaxSlab;
use App\Services\TaxSlabService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaxSlabController extends Controller
{
    public function __construct(TaxSlabService $taxSlabService)
    {
        $this->TaxSlabService = $taxSlabService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('hr.tax_slabs.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $taxes = TaxSlab::where('tax_type', 'rentalTax')->get();
        $financialYears = Financial::where('status', 1)->get();

        $formattedFinancialYears = [];
        foreach ($financialYears as $financialYear) {
            $formattedFinancialYears[$financialYear->id] = $financialYear->name . ' ' . date('y', strtotime($financialYear->start_date)) . '-' . date('y', strtotime($financialYear->end_date));
        }


        return view('hr.tax_slabs.create', compact('taxes', 'formattedFinancialYears'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->TaxSlabService->store($request);

        return redirect()->route('hr.' . $request->section_name . '.index')
            ->with('success', 'Rental Tax Created successfully');

    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $rentalTax = $this->TaxSlabService->getdata();
        return $rentalTax;
    }
}
