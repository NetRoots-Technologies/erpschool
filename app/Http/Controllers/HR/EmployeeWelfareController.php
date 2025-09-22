<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Admin\Branch;
use App\Models\HR\EmployeeWelfare;
use App\Models\HRM\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class EmployeeWelfareController extends Controller
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
        return view('hr.employee_welfare.index');
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
        $employees = Employees::all();
        $branches = Branch::where('status', 1)->get();
        return view('hr.employee_welfare.create', compact('employees', 'branches'));

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
            foreach ($request->get('employee_id') as $key => $employee) {
                EmployeeWelfare::create([
                    'employee_id' => $employee,
                    'gross_amount' => $request->get('grossSalary')[$key],
                    'net_amount' => $request->get('netSalary')[$key],
                    'deducted_amount' => $request->get('total')[$key],
                    'welfare_amount' => $request->get('remaining')[$key],
                ]);
            }
            return redirect()->route('hr.employee-welfare.index')->with('success', 'Employee Welfare created successfully');
        } catch (\Exception $e) {
            return redirect()->route('hr.eobis.index')->with('error', 'An error occurred while creating Employee Welfare');
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
        $employeeWelfare = EmployeeWelfare::find($id);
        if (!$employeeWelfare) {
            return redirect()->route('hr.eobis.index')->with('error', 'An error occurred while finding Employee Welfare');

        }
        $employees = Employees::all();
        $branches = Branch::where('status', 1)->get();
        return view('hr.employee_welfare.edit', compact('employees', 'branches', 'employeeWelfare'));
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
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $employeeWelfare = EmployeeWelfare::find($id);
        if ($employeeWelfare) {
            $employeeWelfare->delete();
        }
        return redirect()->route('hr.employee-welfare.index')->with('success', 'Employee Welfare Deleted successfully');

    }

    public function employeeWelfareData(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $request->all();
        $branch_id = $data['branch_id'];
        $department_id = $data['department_id'];
        $employee_id = $data['employee_id'];

        if ($employee_id) {
            $employee = Employees::find($employee_id);
            return view('hr.employee_welfare.data', compact('employee'));
        } else {
            $employees = Employees::where('branch_id', $branch_id)->orWhere('department_id', $department_id)->get();
            return view('hr.employee_welfare.data', compact('employees'));
        }
    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = DB::table('employee_welfare')
            ->join('hrm_employees', 'employee_welfare.employee_id', '=', 'hrm_employees.id')
            ->select('employee_welfare.*', 'hrm_employees.name AS employee_name')
            ->get();
        //        dd($data);
        return Datatables::of($data)->addIndexColumn()
            //            ->addColumn('action', function ($row) {
//                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("hr.employee-welfare.destroy", $row->id) . '"> ';
//
////                $btn = $btn . '<a href="' . route("hr.employee-welfare.edit", $row->id) . '" class="btn btn-primary  ml-2 mr-2 btn-sm">Edit</a>';
//
//                $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
//                $btn = $btn . method_field('DELETE') . '' . csrf_field();
//                $btn = $btn . ' </form>';
//                return $btn;
//            })
            ->addColumn('employee', function ($row) {
                return $row->employee_name ?? 'N/A';
            })
            ->rawColumns(['action', 'employee'])
            ->make(true);
    }

}

