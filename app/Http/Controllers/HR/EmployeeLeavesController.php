<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Services\EmployeeLeavesServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EmployeeLeavesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(EmployeeLeavesServices $EmployeeLeavesServices)
    {
        $this->EmployeeLeavesServices = $EmployeeLeavesServices;
    }

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $this->EmployeeLeavesServices->index();
        return $data;
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
        $employee_leaves = $this->EmployeeLeavesServices->create();
        return $employee_leaves;
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
        $this->EmployeeLeavesServices->store($request);
        return redirect()->route('hr.employee_leaves.index')
            ->with('success', 'Employee Leave created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $employee_leaves = $this->EmployeeLeavesServices->edit($id);
        return $employee_leaves;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $employee_leaves = $this->EmployeeLeavesServices->update($request, $id);
        return redirect()->route('hr.employee_leaves.index')
            ->with('success', 'Employee Leave updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $employee_leaves = $this->EmployeeLeavesServices->destroy($id);
        return $employee_leaves;
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $employee_leaves = $this->EmployeeLeavesServices->getdata();
        return $employee_leaves;
    }
}
