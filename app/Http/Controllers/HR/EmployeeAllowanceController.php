<?php

namespace App\Http\Controllers\HR;

use App\Helpers\GeneralSettingsHelper;
use App\Http\Controllers\Controller;
use App\Models\HR\Allowance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EmployeeAllowanceController extends Controller
{
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $allowances = Allowance::where('status', 1)->get();
        $employeeTypes = GeneralSettingsHelper::getSetting('employeeType');
        return view('hr.employee_allowances.index', compact('allowances', 'employeeTypes'));
    }
}
