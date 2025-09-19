<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Models\PurchaseOrderItem;
use App\Http\Controllers\Controller;

class PurchaseOrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
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
        if (!Gate::allows('Dashboard-list')) {
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
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrderItem  $purchaseOrderItem
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseOrderItem $purchaseOrderItem)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrderItem  $purchaseOrderItem
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseOrderItem $purchaseOrderItem)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrderItem  $purchaseOrderItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseOrderItem $purchaseOrderItem)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrderItem  $purchaseOrderItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrderItem $purchaseOrderItem)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }
}

