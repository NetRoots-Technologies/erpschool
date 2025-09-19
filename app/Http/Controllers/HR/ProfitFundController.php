<?php

namespace App\Http\Controllers\HR;

use App\Helpers\GeneralSettingsHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Branch;
use App\Models\HR\ProfitFund;
use App\Models\HRM\Employees;
use App\Services\ProfitFundService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Monolog\Handler\IFTTTHandler;

class ProfitFundController extends Controller
{
    protected $ProfitFundService;
    public function __construct(ProfitFundService $profitFundService)
    {
        $this->ProfitFundService = $profitFundService;
    }


    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('hr.profit_fund.index');
    }

    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $employees = Employees::all();
        $branches = Branch::where('status', 1)->get();

        return view('hr.profit_fund.create', compact('employees', 'branches'));

    }

    public function store(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        try {
            $this->ProfitFundService->store($request);
            return redirect()->route('hr.profit-funds.index')->with('success', 'PF created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating PF');
        }
    }


    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $profitFund = ProfitFund::with('employee')->find($id);
        if (!$profitFund) {
            return redirect()->back()->with('error', 'Did not find any ProfitFund');
        }
        $branches = Branch::where('status', 1)->get();

        return view('hr.profit_fund.edit', compact('profitFund', 'branches'));
    }


    public function profitData(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $request->all();
        $branch_id = $data['branch_id'];
        $department_id = $data['department_id'];
        $employee_id = $data['employee_id'];


        $providentFund = GeneralSettingsHelper::getSetting('providentFund');

        $provit = $providentFund['percentage'];

        if ($employee_id) {
            $employee = Employees::find($employee_id);
            return view('hr.profit_fund.data', compact('provit', 'employee'));
        } else {
            $employees = Employees::where('branch_id', $branch_id)->orWhere('department_id', $department_id)->get();
            return view('hr.profit_fund.data', compact('provit', 'employees'));
        }
    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $profitFund = $this->ProfitFundService->getData();
        return $profitFund;
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->ProfitFundService->update($request, $id);

        return redirect()->route('hr.profit-funds.index')
            ->with('success', 'PF Updated successfully');
    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->ProfitFundService->destroy($id);

        return redirect()->route('hr.profit-funds.index')
            ->with('success', 'PF Deleted successfully');

    }

}

