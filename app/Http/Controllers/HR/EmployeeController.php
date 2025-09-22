<?php

namespace App\Http\Controllers\HR;

use App\Helpers\GeneralSettingsHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Imports\EmployeeImport;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Admin\Departments;
use App\Models\HR\Attendance;
use App\Models\HR\Designation;
use App\Models\HR\EmployeeAllowance;

use App\Models\HR\WorkShift;
use App\Models\HRM\Employees;
use App\Models\HRM\EmployeeTypes;
use App\Models\User;
use App\Services\EmployeeServices;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Rats\Zkteco\Lib\ZKTeco;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\Academic\Section;
use App\Models\EmployeeChild;
use App\Imports\EmployeeExcelImport;
use Illuminate\Validation\ValidationException;
use App\Exports\EmployeeSampleExport;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $EmployeeServices;
    protected $ZktecoController;

    public function __construct(EmployeeServices $EmployeeServices, ZktecoController $zktecoController)
    {
        $this->EmployeeServices = $EmployeeServices;
        $this->ZktecoController = $zktecoController;
    }


    public function index()
    {

        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        // $salary = Employees::where('status',1)->sum('net_salary');
        $salary = Employees::all();
        return view('hr.employee.index', compact('salary'));
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
        $nationalities = UserHelper::getNationalities();
        $religions = UserHelper::getReligions();
        $pakistaniBanks = UserHelper::getPakistaniBanks();
        $bloodGroups = UserHelper::getBloodGroups();

        $type = EmployeeTypes::all();
        $departments = Departments::where('status', 1)->get();
        $designations = Designation::all();
        $companies = Company::all();
        $roles = Role::all();
        $employees = Employees::where('status', 1)->get();
        //        $allowances = Allowance::where('status',1)->get();
        $employeeTypes = GeneralSettingsHelper::getSetting('employeeType');
        $workShifts = WorkShift::with('workdays')->get();
        return view('hr.employee.create', compact('bloodGroups', 'pakistaniBanks', 'religions', 'nationalities', 'type', 'departments', 'employees', 'companies', 'roles', 'designations', 'workShifts', 'employeeTypes'));
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
        DB::beginTransaction();
        $request->validate([
            'email_address' => 'required|unique:users,email',
        ]);
        try {
            
            $this->EmployeeServices->store($request);
            // dd('hello');
            DB::commit();
            return redirect()->route('hr.employee.index')->with('success', 'Employee created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
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
        $pakistaniBanks = UserHelper::getPakistaniBanks();

        $nationalities = UserHelper::getNationalities();
        $religions = UserHelper::getReligions();
        $bloodGroups = UserHelper::getBloodGroups();

        $companies = Company::all();
        $departments = Departments::where('status', 1)->get();
        $designations = Designation::all();
        $branches = Branch::all();
        $workShifts = WorkShift::with('workdays')->get();
        $employees = Employees::where('id', '!=', $id)->get();
        $employeeTypes = GeneralSettingsHelper::getSetting('employeeType');
        //        $allowances = Allowance::where('status',1)->get();
        $employeeAllowances = EmployeeAllowance::with('employee')->get();
        $employee = $this->EmployeeServices->edit($id);

        $designation_name = Designation::where('id', $employee->designation_id)->first();

        $designationName = $designation_name->name;
        // $designationName = @$designation_nameEmployeeSampleExport->name;

        if (!$employee) {
            return redirect()->back()->with('error', 'Did not find any Employee');
        }

        $employee_childrens = EmployeeChild::where('employee_id', $id)->get();


        return view('hr.employee.edit', compact('bloodGroups', 'pakistaniBanks', 'nationalities', 'religions', 'designationName', 'employee', 'companies', 'departments', 'designations', 'branches', 'workShifts', 'employees', 'employeeTypes', 'employeeAllowances', 'employee_childrens'));
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
        $user = User::where('employee_id', $id)->first();
        if ($user) {
            $validated = $request->validate([
                'name' => 'required',
                'email_address' => "required|email|unique:users,email,$user->id",
            ]);
        }
        try {

            $this->EmployeeServices->update($request, $id);

            return redirect()->route('hr.employee.index')
                ->with('success', 'Employee updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $employee = $this->EmployeeServices->getdata();
        return $employee;
    }

    public function get_employee_attendance()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $employee_attendance = $this->EmployeeServices->get_employee_attendance();
        return $employee_attendance;
    }

    public function employee_attendance()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $attendance = $this->EmployeeServices->employee_attendance();
        return $attendance;
    }

    public function sync_employee_attendance()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $attendance = $this->EmployeeServices->sync_employee_attendance();
        return $attendance;
    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->EmployeeServices->destroy($id);

        return redirect()->route('hr.employee.index')
            ->with('success', 'Employee deleted successfully');
    }

    public function fetchBranches(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $branches = Branch::where('company_id', $request->companyid)->get();
        return response()->json($branches);
    }


    public function fetchDepartment(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $departments = Departments::where('company_id', $request->companyid)->get();
        //        dd($departments);
        return response()->json($departments);
    }

    public function fetchDesignation(Request $request)
    {

        $departments = Designation::where('department_id', $request->department_id)->get();
        return response()->json($departments);
    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $employee = Employees::find($id);
            if ($employee) {
                $employee->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action completed Successfully']);
    }


    public function addAttendance($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        ini_set('max_execution_time', 200);

        $branch = Branch::where('status', 1)->find($id);
        if (!$branch) {
            return 'Branch not found';
        }


        $zk = new ZKTeco($branch->ip_config, $branch->port);

        if ($zk->connect()) {
            $attendance = $zk->getAttendance();
            if ($attendance != null) {
                foreach ($attendance as $item) {
                    //                    dd($item);
                    $datetime = $item['timestamp'];
                    $currentDate = Carbon::parse($datetime)->format('Y-m-d');
                    $date_arr = explode(" ", $datetime);
                    $date = $date_arr[0];
                    $time = $date_arr[1];

                    $employee = Employees::where('id', $item['id'])->with('branch')->first();

                    if ($employee) {
                        $existingAttendance = Attendance::where('employee_id', $employee->id)
                            ->where('attendance_date', $date)
                            ->first();

                        if ($existingAttendance) {

                            if ($item['type'] == 1) {
                                $existingAttendance->timeOut = $time;
                                $existingAttendance->save();
                            }
                        } else {

                            $attendance = new Attendance();
                            $attendance->branch_id = $employee->branch_id;
                            $attendance->employee_id = $employee->id;
                            $attendance->attendance_date = $date;

                            if ($item['type'] == 0) {
                                $attendance->timeIn = $time;
                            }
                            $attendance->machine_status = 1;
                            $attendance->status = 1;
                            $attendance->save();
                        }
                    }
                }
            } else {
                return 'No attendance Found';

            }

            return true;


        } else {
            return 'Failed to connect to device';
        }
    }


    public function syncData(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $id = $request->get('id');
        //
//        $employees_id = OtherBranch::where('branch_id',$id)->pluck('employee_id');
//dd($employees_id);
        $this->ZktecoController->employeeGenerated($id);

        return response()->json(['message' => 'Employee added Successfully']);
    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $employee = $this->EmployeeServices->changeStatus($request);

    }


    public function import(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        try {
            Excel::import(new EmployeeImport($this->ZktecoController), $request->file('file'));
            return redirect()->back()->with('success', 'File imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred during file import: ' . $e->getMessage());
        }
    }

    public function fetchSection(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $request->validate([
            'class_id' => 'required|integer|exists:classes,id',
        ]);

        $sections = Section::select('id', 'name')
            ->where('class_id', $request->class_id)
            ->get();

        return response()->json($sections);
    }

    public function addBankDetails($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $employee = Employees::findOrFail($id);
        $pakistaniBanks = ['HBL', 'UBL', 'MCB', 'Meezan Bank']; // Example list

        return view('hr.employee.add-edit-bank-details', compact('employee', 'pakistaniBanks'));
    }

    public function saveBankDetails(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $request->validate([
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'grossSalary' => 'required|numeric',
            'salary' => 'required|numeric',
        ]);

        $employee = Employees::findOrFail($id);
        $employee->update([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'grossSalary' => $request->grossSalary,
            'salary' => $request->salary,
        ]);

        return redirect()->route('hr.employee.index')->with('success', 'Bank details updated.');
    }


    public function exportbulkfile()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Excel::download(new EmployeeSampleExport, 'employee_bulk_sample.xlsx');
    }

    public function importBulkFile(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new EmployeeImport($this->ZktecoController), $request->file('import_file'));

            return back()->with('success', 'Employee imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            // Optional: Log details
            Log::error('Excel Import Validation Failed', ['errors' => $failures]);

            // Get first failure message
            $firstError = $failures[0]->errors()[0] ?? 'Import failed due to validation error.';

            return back()->with('error', 'Import Failed: ' . $firstError);
        } catch (\Throwable $e) {
            Log::error('Excel Import Exception: ' . $e->getMessage());
            return back()->with('error', 'Import Failed: ' . $e->getMessage());
        }
    }





}

