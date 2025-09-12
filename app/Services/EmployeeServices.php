<?php

namespace App\Services;

use File;
use DataTables;
use App\Models\User;
use App\Models\HR\Eobi;
use Rats\Zkteco\Lib\ZKTeco;
use App\Helpers\ImageHelper;
use App\Models\HR\ProfitFund;
use App\Models\HRM\Employees;
use App\Models\HR\OtherBranch;
use Dflydev\DotAccessData\Data;
use App\Models\HRM\EmployeeTypes;
use App\Models\HR\EmployeeWelfare;
use App\Models\HRM\EmployeeFamily;
use Spatie\Permission\Models\Role;
use App\Models\HR\MedicalAllowance;
use App\Models\HR\EmployeeAllowance;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Models\HRM\EmployeeEducation;
use App\Helpers\GeneralSettingsHelper;
use Illuminate\Support\Facades\Config;
use App\Models\HRM\HrmEmployeeAttendance;
use App\Models\HRM\EmployeeWorkExperience;
use App\Http\Controllers\HR\ZktecoController;
use App\Models\EmployeeChild;


class EmployeeServices
{
    protected $ip;
    protected $port;
    protected $ZktecoController;

    public function __construct(ZktecoController $zktecoController)
    {
        $this->ip = Config::get('zkteco.ip');
        $this->port = Config::get('zkteco.port');
        $this->ZktecoController = $zktecoController;
    }

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Employees::all();
    }

    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $type = EmployeeTypes::all();
        return view('hr.employee.create', compact('type'));

    }

    public function sync_employee_attendance() //hr.sync_employee_attendance
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $zk = new ZKTeco($this->ip, $this->port);

        if ($zk->connect()) {
            $attendance = $zk->getAttendance();

            date_default_timezone_set("Asia/Karachi");
            foreach ($attendance as $item) {
                $datetime = $item['timestamp'];
                $date_arr = explode(" ", $datetime);
                $date = $date_arr[0];
                $time = $date_arr[1];
                $currentdate = date('Y-m-d');


                $user1 = HrmEmployeeAttendance::where('user_id', $item['id'])->where('date', $date)->first();
                //&& ($date == $currentdate)
                if ($user1 && ($user1->user_id == $item['id'])) {


                    if ($item['type'] == 0) {
                        $user1->checkin_time = $time;
                    }
                    if ($item['type'] == 1) {
                        $user1->checkout_time = $time;
                    }
                    if ($item['type'] == 4) {
                        $user1->overtime_in = $time;
                    }
                    if ($item['type'] == 5) {
                        $user1->overtime_out = $time;
                    }


                    $user1->save();
                } else {
                    $user2 = new HrmEmployeeAttendance();
                    $user2->is_machine = 'Machine';
                    $user2->user_id = $item['id'];
                    $user2->date = $date;
                    $user2->manual_attendance = null;
                    if (isset($item['status'])) {
                        $user2->status = $item['status'];
                    } else {
                        $user2->status = 1;
                    }
                    if ($item['type'] == 0) {
                        $user2->checkin_time = $time;
                    }
                    if ($item['type'] == 1) {
                        $user2->checkout_time = $time;
                    }
                    if ($item['type'] == 4) {
                        $user2->overtime_in = $time;
                    }
                    if ($item['type'] == 5) {
                        $user2->overtime_out = $time;
                    }
                    $user2->save();
                }

            }
            //   $zk->clearAttendance();
        }

    }

    public function employee_attendance()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $attendance = HrmEmployeeAttendance::with('user_name')->get();
        return view('hr.zkt.ZktShowAttendence', compact('attendance'));

    }

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $request->validate([
            'email_address' => 'required|email|unique:users,email',
        ]);
        
        //          dd($request->all());
        $data = [];
        ini_set('max_execution_time', 120000);
        
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imageFile = $request->file('image');
            $employee_profile = ImageHelper::uploadImage($imageFile, 'employee_profile_picture');
        } else {
            $employee_profile = null;
        }
        
        $professionalEmail = $request->input('email_address');
        
        $existingUser = User::where('email', $professionalEmail)->first();
        if ($existingUser) {
            return redirect()->back()->withErrors(['email_address' => 'The email address is already taken. Please choose a different one.'])->withInput();
        }
        
        $employee = Employees::create([
            'emp_id' => $request->input('emp_id'),
            'name' => $request->input('name'),
            // 'employee_profile' => $employee_profile,
            'father_name' => $request->input('father_name'),
            'cnic_card' => $request->input('cnic_card'),
            'tell_no' => $request->input('tell_no'),
            'dob' => $request->input('dob'),
            'mobile_no' => $request->input('mobile_no'),
            'email' => $professionalEmail,
            'email_address' => $professionalEmail,
            // 'personal_email_address' => $request->input('personal_email_address'),
            'present_address' => $request->input('present_address'),
            'permanent_address' => $request->input('permanent_address'),
            'company_id' => $request->input('companySelect'),
            'branch_id' => $request->input('branchSelect'),
            'job_seeking' => $request->input('job_seeking'),
            'start_date' => $request->input('start_date'),
            'salary' => $request->input('salary'),
            'applied' => $request->input('applied_before'),
            'applied_yes' => $request->input('applied_when'),
            'employed' => $request->input('employed_here'),
            'when_employed_yes' => $request->input('employed_when'),
            'engaged_business' => $request->input('engaged_in_other_employment'),
            'when_business_yes' => $request->input('other_employment_details'),
            'skills' => $request->input('skills'),
            'nationality' => $request->input('nationality'),
            'religion' => $request->input('religion'),
            'blood_group' => $request->input('blood_group'),
            'marital_status' => $request->input('marital_status'),
            // 'specialization_subject' => $request->input('specialization_subject'),
            'designation_id' => $request->input('designationSelect'),
            'department_id' => $request->input('departmentSelect'),
            'work_shift_id' => $request->input('workShift'),
            'working_hour' => $request->input('working_hour'),
            'hour_salary' => $request->input('hour_salary'),
            'visitingLecturer' => $request->input('visitingLecturer'),
            'employee_id' => $request->input('employee_id'),
            // 'grossSalary' => $request->input('grossSalary'),
            // 'gender' => $request->input('selectGender'),
            'account_number' => $request->input('account_number'),
            'bank_name' => $request->input('bank_name'),
            'provident_fund' => $request->input('provident_fund')
        ]);
        
        if ($request->input('employeeWelfare')) {
            $existingEmployeeWelfare = EmployeeWelfare::where('employee_id', $employee->id)->first();
            if ($existingEmployeeWelfare) {
                $existingEmployeeWelfare->delete();
            }
            $employeeWelfare = EmployeeWelfare::create([
                'employee_id' => $employee->id,
                'month' => now()->format('M'),
                'year' => now()->format('Y'),
                'welfare_amount' => $request->input('employeeWelfare'),
            ]);
        }
        if (!empty($request->input('providentFund'))) {
            $existingEmployeeFund = ProfitFund::where('employee_id', $employee->id)->first();
            if ($existingEmployeeFund) {
                $existingEmployeeFund->delete();
            }
            $providentFund = ProfitFund::create([
                'employee_id' => $employee->id,
                'month' => now()->format('M'),
                'year' => now()->format('Y'),
                'providentFund' => $request->input('providentFund'),
            ]);
        }
        
        $medicalAllowance = $request->medicalAllowance ?? 0;
        
        //for allowance
        MedicalAllowance::create([
            'medicalAllowance' => $medicalAllowance,
            'month' => now()->format('M'),
            'year' => now()->format('Y'),
            'employee_id' => $employee->id,
        ]);

        
        
        if ($request->has('email_address') && $request->has('password')) {
            // dd("hello", $request->get('email_address'),$request->all());

            $user = User::create([
                'password' => Hash::make($request->get('password', '12345678')),
                'email' => $professionalEmail,
                'name' => $request->input('name'),
                'employee_id' => $employee->id,
                'branch_id' => $request->input('branchSelect')
            ]);


            $roles = Role::where('name', 'General Employee')->pluck('id');
            $user->syncRoles($roles);
        }
        
        
        //employee education
        if ($request->has('education_institution')) {
            foreach ($request->input('education_institution') as $key => $institution) {
                
                $educationImage = null;
                if ($request->hasfile('education_image') && isset($request->education_image[$key])) {
                    $educationImageFile = $request->file('education_image')[$key];
                    $educationImage = ImageHelper::uploadImage($educationImageFile, 'employee_files');
                }
                
                EmployeeEducation::create([
                    'hrm_employee_id' => $employee->id,
                    'institution' => $institution,
                    'year' => $request->input('year')[$key],
                    'certification' => $request->input('certification')[$key],
                    'cgpa' => $request->input('cgpa')[$key],
                    'specialization' => $request->input('specialization')[$key],
                    'education_images' => $educationImage,
                ]);
            }
        }


        if ($request->has('student_id')) {
            $studentIds = $request->input('student_id');
            
            $childrenData = [];
            foreach ($studentIds as $studentId) {
                if (!empty($studentId)) {
                    $childrenData[] = [
                        'employee_id' => $employee->id,
                        'student_id' => $studentId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($childrenData)) {
                EmployeeChild::insert($childrenData);
            }
        }
        
        //for other branch
        if ($request->input('branchSelect')) {
            OtherBranch::create([
                'employee_id' => $employee->id,
                'branch_id' => $request->input('branchSelect'),
                'main_branch' => 1,
            ]);
        }

        if ($request->has('otherBranchSelect')) {
            foreach ($request->input('otherBranchSelect') as $otherBranch) {
                
                OtherBranch::create([
                    'employee_id' => $employee->id,
                    'branch_id' => $otherBranch,
                    'main_branch' => 0,
                ]);
            }
        }
        
        // family information
        if ($request->has('sr_no')) {
            foreach ($request->input('sr_no') as $key => $srNo) {
                EmployeeFamily::create([
                    'hrm_employee_id' => $employee->id,
                    'sr_no' => $srNo,
                    'name' => $request->input('name')[$key],
                    'relation' => $request->input('relation')[$key],
                    'gender' => $request->input('gender')[$key],
                    'dob' => $request->input('family_dob')[$key],
                    'cnic' => $request->input('cnic')[$key],
                    'workstation' => $request->input('workplace')[$key],
                    
                ]);
            }
        }
        
        // work experince
        if ($request->has('designation')) {
            foreach ($request->input('designation') as $key => $designation) {
                EmployeeWorkExperience::create([
                    'hrm_employee_id' => $employee->id,
                    's_no' => $request->input('work_sno')[$key],
                    'name_of_institution' => $request->input('work_institution')[$key],
                    'designation' => $designation,
                    'duration' => $request->input('duration')[$key],
                    'from' => $request->input('from')[$key],
                    'till' => $request->input('till')[$key],
                    
                ]);
            }
        }
        
        $eobiSettings = GeneralSettingsHelper::getSetting('eobi');

        $company = $eobiSettings['company'] ?? 0;
        $total = $eobiSettings['total'] ?? 0;
        $employee_value = $eobiSettings['employee'] ?? 0;
        
        Eobi::create([
            'employee_id' => $employee->id,
            'total' => $total,
            'employee_percent' => $employee_value,
            'company' => $company,
        ]);

        $data = [
            $employee->id
        ];

        // $this->ZktecoController->employeeGenerated($data);

    }

    /**
     * @return mixed
     */

    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Employees::with('company', 'branch', 'department')->orderBy('created_at', 'desc')->get();


        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';

                if (Gate::allows('Employee-edit'))
                    $btn .= '<a href="' . route("hr.employee.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';

                if (Gate::allows('Employee-destroy')) {
                    $btn .= '
                    <form method="POST" id="delete-form-' . $row->id . '" action="' . route("hr.employee.destroy", $row->id) . '">
                        ' . method_field('DELETE') . csrf_field() . '
                        <button type="button" class="btn btn-danger btn-sm" style="margin-right: 4px;" onclick="confirmDelete(' . $row->id . ')">Delete</button>
                    </form>
                ';

                    $btn .= '
                    <script>
                        function confirmDelete(id) {
                            Swal.fire({
                                title: "Are you sure?",
                                text: "You won\'t be able to revert this!",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#d33",
                                cancelButtonColor: "#3085d6",
                                confirmButtonText: "Yes, delete it!"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $("#delete-form-" + id).submit();
                                }
                            });
                        }
                    </script>
                ';

                }


                if (Gate::allows('Employee-edit')) {
                    $btn .= '<a href="' . route("hr.addEditBankDetail", $row->id) . '" class="btn btn-info btn-sm" style="margin-right: 4px;">Bank Detail</a>';
                }



                if ($row->machine_status == 0) {
                    $btn .= '<button type="button" class="btn btn-success btn-sm employee_attendance" data-id="' . $row->id . '" data-status="inactive">Add</button>';
                }

                $btn .= '</div>';

                return $btn;

            })
            ->addColumn('sync_Data', function ($row) {

                $syncbtn = '<button type="button" class="btn btn-success btn-sm employee_attendance" data-id="' . $row->id . '" data-status="inactive">Add Employee</button>';


                return $syncbtn;
            })
            ->addColumn('company', function ($row) {


                if ($row->company) {
                    return $row->company->name;

                } else {
                    return "N/A";
                }


            })->addColumn('branch', function ($row) {


                if ($row->branch) {
                    return $row->branch->name;

                } else {
                    return "N/A";
                }


            })->addColumn('department', function ($row) {
                if ($row->department) {
                    return $row->department->name;

                } else {
                    return "N/A";
                }
            })->addColumn('emp_id', function ($row) {
                if ($row->emp_id) {
                    return $row->emp_id;

                } else {
                    return "N/A";
                }
            })->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->rawColumns(['action', 'company', 'branch', 'sync_Data', 'status', 'emp_id'])
            ->make(true);
    }



    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Employees::with('educations', 'workExperince', 'employeeFamily', 'providentFund', 'Otherbranch', 'employeeAllowance', 'employeeWelfare')->find($id);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        //        dd($request->all());
        $employee = Employees::findOrFail($id);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imageFile = $request->file('image');
            $employee_profile = ImageHelper::uploadImage($imageFile, 'employee_profile_picture');

            if ($employee->employee_profile) {
                File::delete(public_path($employee->employee_profile));
            }
        } else {
            $employee_profile = $employee->employee_profile;
        }

        $employee->update([
            'name' => $request->input('name'),
            // 'employee_profile' => $employee_profile,
            'father_name' => $request->input('father_name'),
            'cnic_card' => $request->input('cnic_card'),
            'tell_no' => $request->input('tell_no'),
            'dob' => $request->input('dob'),
            'mobile_no' => $request->input('mobile_no'),
            'email_address' => $request->input('email_address'),
            // 'personal_email_address' => $request->input('personal_email_address'),
            'present_address' => $request->input('present_address'),
            'permanent_address' => $request->input('permanent_address'),
            'company_id' => $request->input('companySelect'),
            'branch_id' => $request->input('branchSelect'),
            'job_seeking' => $request->input('job_seeking'),
            'start_date' => $request->input('start_date'),
            'salary' => $request->input('salary'),
            'applied' => $request->input('applied_before'),
            'applied_yes' => $request->input('applied_when'),
            'employed' => $request->input('employed_here'),
            'when_employed_yes' => $request->input('employed_when'),
            'engaged_business' => $request->input('engaged_in_other_employment'),
            'when_business_yes' => $request->input('other_employment_details'),
            'skills' => $request->input('skills'),
            'nationality' => $request->input('nationality'),
            'religion' => $request->input('religion'),
            'blood_group' => $request->input('blood_group'),
            'marital_status' => $request->input('marital_status'),
            'other_branch' => $request->input('otherBranchSelect'),
            'designation_id' => $request->input('designationSelect'),
            'department_id' => $request->input('departmentSelect'),
            'work_shift_id' => $request->input('workShift'),
            'working_hour' => $request->input('working_hour'),
            'hour_salary' => $request->input('hour_salary'),
            'visitingLecturer' => $request->input('visitingLecturer'),
            'employee_id' => $request->input('employee_id'),
            // 'grossSalary' => $request->input('grossSalary'),
            // 'gender' => $request->input('selectGender'),
            // 'specialization_subject' => $request->input('specialization_subject'),
            'account_number' => $request->input('account_number'),
            'bank_name' => $request->input('bank_name'),
            'emp_id' => $request->input('emp_id'),
            'provident_fund' => $request->input('provident_fund')

            //            'welfareAmount' => $request->input('employeeWelfare'),
//            'deductedAmount' => $request->input('deductedAmount'),

        ]);

        $employee->educations()->delete();

        if ($request->has('education_institution')) {
            foreach ($request->input('education_institution') as $key => $institution) {
                $educationImage = null;
                if ($request->hasfile('education_image') && isset($request->education_image[$key])) {
                    $educationImageFile = $request->file('education_image')[$key];
                    $educationImage = ImageHelper::uploadImage($educationImageFile, 'employee_files');
                }

                $educationData = [
                    'hrm_employee_id' => $employee->id,
                    'institution' => $institution,
                    'year' => $request->input('year')[$key],
                    'certification' => $request->input('certification')[$key],
                    'cgpa' => $request->input('cgpa')[$key],
                    'specialization' => $request->input('specialization')[$key],
                    'education_images' => $educationImage,
                ];

                $employee->educations()->Create($educationData);
            }
        }

        if ($request->has('otherBranchSelect')) {
            $employee->Otherbranch()->delete();

            //for other branch
            foreach ($request->input('otherBranchSelect') as $otherBranch) {
                $otherBranch = [
                    'employee_id' => $employee->id,
                    'branch_id' => $otherBranch,
                ];
                $employee->Otherbranch()->create($otherBranch);
            }
        }
        //        dd($employee->employeeFamily()->delete());

        $employee->employeeFamily()->delete();

        if ($request->has('sr_no')) {
            foreach ($request->input('sr_no') as $key => $srNo) {

                $familyData = [
                    'hrm_employee_id' => $employee->id,
                    'sr_no' => $srNo,
                    'name' => $request->input('name')[$key],
                    'relation' => $request->input('relation')[$key],
                    'gender' => $request->input('gender')[$key],
                    'dob' => $request->input('family_dob')[$key],
                    'cnic' => $request->input('cnic')[$key],
                    'workstation' => $request->input('workplace')[$key],
                ];

                $employee->employeeFamily()->create($familyData);
            }
        }

        $employee->workExperince()->delete();

        if ($request->has('designation')) {
            foreach ($request->input('designation') as $key => $designation) {
                $workExperienceData = [
                    'hrm_employee_id' => $employee->id,
                    's_no' => $request->input('work_sno')[$key],
                    'name_of_institution' => $request->input('work_institution')[$key],
                    'designation' => $designation,
                    'duration' => $request->input('duration')[$key],
                    'from' => $request->input('from')[$key],
                    'till' => $request->input('till')[$key],
                ];

                $employee->workExperince()->Create($workExperienceData);
            }
        }


        $employeeWelfare = EmployeeWelfare::where('employee_id', $employee->id)->first();
        if ($employeeWelfare && !empty($request->input('employeeWelfare'))) {
            $employeeWelfare->update([
                'welfare_amount' => $request->input('employeeWelfare'),
            ]);
        } else {
            EmployeeWelfare::create([
                'employee_id' => $employee->id,
                'welfare_amount' => $request->input('employeeWelfare') ?? 0,
            ]);
        }


        $employeeMedical = MedicalAllowance::where('employee_id', $employee->id)->first();
        if ($employeeMedical && !empty($request->input('employeeWelfare'))) {
            $employeeMedical->update([
                'medicalAllowance' => $request->input('medicalAllowance'),
            ]);
        } else {
            EmployeeWelfare::create([
                'employee_id' => $employee->id,
                'welfare_amount' => $request->input('employeeWelfare') ?? 0,
            ]);
        }

        $providentFund = ProfitFund::where('employee_id', $employee->id)->first();
        if ($providentFund && !empty($request->input('providentFund'))) {
            $providentFund->update([
                'providentFund' => $request->input('providentFund'),
            ]);
        } else {
            ProfitFund::create([
                'employee_id' => $employee->id,
                'providentFund' => $request->input('providentFund') ?? 0,
            ]);
        }

        $eobiSettings = GeneralSettingsHelper::getSetting('eobi');
        $company = $eobiSettings['company'] ?? null;
        $total = $eobiSettings['total'] ?? null;
        $employee_value = $eobiSettings['employee'] ?? null;

        $eobiFund = Eobi::where('employee_id', $employee->id)->first();
        if ($eobiFund) {
            $eobiFund->update([
                'total' => $total,
                'employee_percent' => $employee_value,
                'company' => $company,
            ]);
        } else {
            Eobi::create([
                'employee_id' => $employee->id,
                'total' => $total,
                'employee_percent' => $employee_value,
                'company' => $company,
            ]);
        }



        if ($request->has('student_id')) {
            $studentIds = $request->input('student_id'); // array of student IDs
            $childIds = $request->input('employee_child_id'); // array of existing child IDs (or null for new)

            foreach ($studentIds as $index => $studentId) {
                if (empty($studentId)) {
                    continue;
                }

                if (!empty($childIds[$index])) {
                    // Update existing child record
                    EmployeeChild::where('id', $childIds[$index])->update([
                        'student_id' => $studentId,
                        'updated_at' => now(),
                    ]);
                } else {
                    // Create new child record
                    EmployeeChild::create([
                        'employee_id' => $employee->id,
                        'student_id' => $studentId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }


        //        $employee->employeeAllowance()->delete();
////        for allowance
//
//            foreach ($request->input('allowance') as $key => $allowance){
//                EmployeeAllowance::create([
//                    'allowance' => $allowance,
//                    'allowance_price' => $request->input('allowance_price')[$key],
//                    'employee_id' => $employee->id,
//                ]);
//            }
        $data = [
            $employee->id
        ];
        //        $this->ZktecoController->employeeGenerated($data);

    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $employee = Employees::find($id);
        $employee->workExperince()->delete();
        $employee->educations()->delete();
        $employee->employeeFamily()->delete();
        $employee->employeeWelfare()->delete();
        $employee->providentFund()->delete();

        //dd($employee);
        $employee->delete();

        $user = User::where('employee_id', $id)->first();
        if ($user) {
            $user->delete();
        }
    }

    public function changeStatus($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $employee = Employees::find($request->id);
        if ($employee) {
            $employee->status = ($request->status == 'active') ? 1 : 0;
            $employee->reason_leaving = $request->reason_leaving;
            $employee->leaving_date = $request->leaving_date;
            $employee->save();
            return $employee;
        }
    }
}

