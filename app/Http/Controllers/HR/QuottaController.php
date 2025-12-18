<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Admin\Department;
use App\Models\HR\Designation;
use App\Services\QuottaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class QuottaController extends Controller
{

    public function __construct(QuottaService $quottaService)
    {
        $this->QuottaService = $quottaService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Quota list')) {
            return abort(503);
        }
        return view('hr.quota_settings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if (!Gate::allows('Quota create')) {
        //     return abort(503);
        // }
        $departments = Department::all();
        return view('hr.quota_settings.create', compact('departments'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (!Gate::allows('Quota create')) {
        //     return abort(503);
        // }
        try {
            $this->QuottaService->store($request);

            return redirect()->route('hr.qouta_sections.index')->with('success', 'Quota created successfully');
        } catch (\Exception $e) {
            return redirect()->route('hr.qouta_sections.index')->with('error', 'An error occurred while creating Quota: ');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('Quota list')) {
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
        if (!Gate::allows('Quota edit')) {
            return abort(503);
        }
        $departments = Department::all();

        $quota = $this->QuottaService->edit($id);
        if (!$quota) {
            return redirect()->back()->with('error', 'Did not find any Quota');
        }
        return view('hr.quota_settings.edit', compact('quota', 'departments'));
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
        if (!Gate::allows('Quota edit')) {
            return abort(503);
        }
        $this->QuottaService->update($request, $id);

        return redirect()->route('hr.qouta_sections.index')
            ->with('success', 'Quota Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('Quota delete')) {
            return abort(503);
        }
        $this->QuottaService->destroy($id);

        return redirect()->route('hr.qouta_sections.index')
            ->with('success', 'Quota deleted successfully');
    }

    public function getData()
    {
        if (!Gate::allows('Quota list')) {
            return abort(503);
        }
        $quota = $this->QuottaService->getdata();
        return $quota;
    }
}

