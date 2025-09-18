<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Fee\FeeCollection;
// use App\Models\Fee\StudentFee; // Removed - model no longer exists
use App\Models\HR\ChildBenefit;
use App\Models\HRM\Employees;
use App\Models\Student\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ChildBenefitController extends Controller
{
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('hr.child_benefit.index');
    }
    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $employees = Employees::all();
        $students = Students::all();
        return view('hr.child_benefit.create', compact('employees', 'students'));
    }

    public function store(Request $request)
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        $childBenefit = new ChildBenefit();



    }
}
