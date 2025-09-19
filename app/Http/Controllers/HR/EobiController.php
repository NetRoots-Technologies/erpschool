<?php

namespace App\Http\Controllers\HR;

use App\Helpers\GeneralSettingsHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Branch;
use App\Models\HR\Eobi;
use App\Models\HRM\Employees;
use App\Services\EobiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EobiController extends Controller
{

    public function __construct(EobiService $eobiService)
    {
        $this->EobiService = $eobiService;
    }

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
        return view('hr.eobi.index');
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
        $employees = Employees::where('status', 1)->get();
        $branches = Branch::where('status', 1)->get();
        $eobi_values = GeneralSettingsHelper::getSetting('eobi');

        return view('hr.eobi.create', compact('eobi_values', 'employees', 'branches'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        try {
            $this->EobiService->store($request);
            return redirect()->route('hr.eobis.index')->with('success', 'Eobi(s) created successfully, some might have been skipped if they already existed');
        } catch (\Exception $e) {
            return redirect()->route('hr.eobis.index')->with('error', 'An error occurred while creating Eobi');
        }
    }



    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('Dashboard-list')) {
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
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $eobi = Eobi::with('employee')->find($id);
        if (!$eobi) {
            return redirect()->back()->with('error', 'Did not find any Eobi');

        }
        $branches = Branch::where('status', 1)->get();


        return view('hr.eobi.edit', compact('eobi', 'branches'));
    }

    public function eobiData(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $request->all();
        $branch_id = $data['branch_id'];
        $department_id = $data['department_id'];
        $employee_id = $data['employee_id'];


        $eobiSettings = GeneralSettingsHelper::getSetting('eobi');
        $company = $eobiSettings['company'] ?? null;
        $total = $eobiSettings['total'] ?? null;
        $employee_value = $eobiSettings['employee'] ?? null;


        if ($employee_id) {
            $employee = Employees::find($employee_id);
            return view('hr.eobi.data', compact('employee', 'company', 'employee_value', 'total'));
        } else {
            $employees = Employees::where('branch_id', $branch_id)->orWhere('department_id', $department_id)->get();
            return view('hr.eobi.data', compact('employees', 'company', 'employee_value', 'total'));
        }
    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $eobi = $this->EobiService->getData();
        return $eobi;
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->EobiService->update($request, $id);

        return redirect()->route('hr.eobis.index')
            ->with('success', 'Eobi Updated successfully');
    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->EobiService->destroy($id);

        return redirect()->route('hr.eobis.index')
            ->with('success', 'Eobi Deleted successfully');

    }
}

