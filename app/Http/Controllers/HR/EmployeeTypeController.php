<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Services\EmployeeTypeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EmployeeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(EmployeeTypeServices $EmployeeTypeServices)
    {
        $this->EmployeeTypeServices = $EmployeeTypeServices;
    }


    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('hr.employee_type.index');
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
        return view('hr.employee_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $validated = $request->validate([
            'name' => 'required',
        ]);
        $this->EmployeeTypeServices->store($request);

        return redirect()->route('hr.employee_type.index')
            ->with('success', 'Employee Type created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $employee = $this->EmployeeTypeServices->edit($id);
        return view('hr.employee_type.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $validated = $request->validate([
            'name' => 'required',
        ]);
        $this->EmployeeTypeServices->update($request, $id);

        return redirect()->route('hr.employee_type.index')
            ->with('success', 'Employee Type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $employee = $this->EmployeeTypeServices->getdata();
        return $employee;
    }

    public function destroy($id)
    {
       if (!Gate::allows('students')) {
            return abort(503);
        }
        $agent = $this->EmployeeTypeServices->destroy($id);
        return redirect()->route('hr.employee_type.index')
            ->with('success', 'Employee Type deleted successfully');
    }
}
