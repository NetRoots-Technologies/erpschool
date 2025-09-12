<?php

namespace App\Http\Controllers\Inventory;

use App\Models\SupplierItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class SupplierItemController extends Controller
{
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupplierItem  $supplierItem
     * @return \Illuminate\Http\Response
     */
    public function show(SupplierItem $supplierItem)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SupplierItem  $supplierItem
     * @return \Illuminate\Http\Response
     */
    public function edit(SupplierItem $supplierItem)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupplierItem  $supplierItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupplierItem $supplierItem)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SupplierItem  $supplierItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupplierItem $supplierItem)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }
}
