<?php

namespace App\Http\Controllers\HR;

use App\Models\Admin\Branch;
use Illuminate\Http\Request;
use App\Models\HRM\Employees;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use App\Models\HR\SocialSecurity;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Helpers\GeneralSettingsHelper;
use App\Services\SocialSecurityService;

class SocialSecurityController extends Controller
{
    protected $SocialSecurityService;
    public function __construct(SocialSecurityService $socialSecurityService)
    {
        $this->SocialSecurityService = $socialSecurityService;
    }

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('hr.social_security.index');
    }

    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $branches = Branch::where('status', 1)->get();
        return view('hr.social_security.create', compact('branches'));
    }


    public function store(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        DB::beginTransaction();
        try {
            $this->SocialSecurityService->store($request);
            DB::commit();
            return redirect()->route('hr.social-security.index')->with('success', 'Social Security created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the Security');
        }

    }

    public function fetchSocialEmployees(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $socialSecurity = GeneralSettingsHelper::getSetting('socialSecurity');
        $minSalary = $socialSecurity['min-salary'];
        $employees = Employees::where('status', 1)->where('department_id', $request->department_id)
            ->where('salary', '<=', $minSalary)
            ->get();

        return response()->json($employees);
    }

    public function socialSecurityData(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $request->all();
        $branch_id = $data['branch_id'];
        $department_id = $data['department_id'];
        $employee_id = $data['employee_id'];


        $socialSecurity = GeneralSettingsHelper::getSetting('socialSecurity');
        $minSalary = $socialSecurity['min-salary'];
        $percentage = $socialSecurity['percentage'] ?? null;

        //dd($percentage);
        if ($employee_id) {
            $employee = Employees::find($employee_id);
            return view('hr.social_security.data', compact('employee', 'percentage'));
        } else {
            $employees = Employees::where('status', 1)->where(function ($query) use ($branch_id, $department_id) {
                $query->where('branch_id', $branch_id)
                    ->orWhere('department_id', $department_id);
            })
                ->where('salary', '<=', $minSalary)
                ->get();
            //            dd($employees);

            return view('hr.social_security.data', compact('employees', 'percentage'));
        }

    }

    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $socialSecurity = SocialSecurity::with('employee')->find($id);
        if (!$socialSecurity) {
            return redirect()->back()->with('error', 'Did not find any socialSecurity');

        }
        $branches = Branch::where('status', 1)->get();
        return view('hr.social_security.edit', compact('socialSecurity', 'branches'));
    }

    public function update(Request $request, $id)
    {

        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->SocialSecurityService->update($request, $id);
        return redirect()->route('hr.social-security.index')
            ->with('success', 'Social Security Updated successfully');

    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $social = $this->SocialSecurityService->getdata();

    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->SocialSecurityService->destroy($id);

        return redirect()->route('hr.social-security.index')
            ->with('success', 'Social Security Deleted successfully');

    }




}

