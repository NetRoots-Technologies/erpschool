<?php

namespace App\Imports;

use App\Helper\Helpers;
use App\Helpers\GeneralSettingsHelper;
use App\Http\Controllers\HR\ZktecoController;
use App\Http\Controllers\Student\StudentController;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Admin\Department;
use App\Models\HR\Designation;
use App\Models\HR\EmployeeWelfare;
use App\Models\HR\Eobi;
use App\Models\HR\MedicalAllowance;
use App\Models\HR\ProfitFund;
use App\Models\HR\WorkShift;
use App\Models\HRM\Employees;
use App\Models\User;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
class EmployeeImport implements ToCollection
{
    protected $ZktecoController;
    protected $requiredColumns = [
        'Applicant Name',
        'Father Name',
        'CNIC',
        'Mobile No',
        'Emergency Contact Number',
        'Personal Email Address',
        'Professional Email Address',
        'Password',
        'Present Address',
        'Permanent Address',
        'Date of Birth',
        'Gender',
        'Date of Joining',
        'Institution',
        'Year',
        'Certification/Degree',
        'CGPA',
        'Specialization',
        'Company Name',
        'Branch Name',
        'Department',
        'Designation',
        'Work Shift',
        'Report To',
        'Job Type',
        'Applied before at cornerstone?',
        'Were you ever employed here?',
        'Engagements in any other business or employment?',
        'Skills or training related to the position?'
    ];

    protected $providentFundPercentage;
    protected $eobiPercentage;
    protected $employeeWelfareMultiplier;

    public function __construct(ZktecoController $zktecoController)
    {
        $this->ZktecoController = $zktecoController;
        $this->providentFundPercentage = GeneralSettingsHelper::getSetting('providentFund')['percentage'] ?? 0;
        $this->eobiPercentage = GeneralSettingsHelper::getSetting('eobiPercentage') ?? 0;
        $this->employeeWelfareMultiplier = GeneralSettingsHelper::getSetting('employeeWelfare')['value'] ?? 0;
    }

    private function validateHeaders(array $headers)
    {
        $uniqueHeaders = array_unique($headers);
        $missing = array_diff($this->requiredColumns, $uniqueHeaders);
        if (count($missing)) {
            throw new \Exception('Missing required columns: ' . implode(', ', $missing));
        }
    }

    public function collection(Collection $rows)
    {
        // dd($rows);
        $firstRow = true;
        $columnNames = [];
        $data = [];
        // dd($rows);
        foreach ($rows as $row_key => $row) {
            if ($this->isRowNull($row))
                continue;

            if ($firstRow) {
                $columnNames = $row->toArray();
                $this->validateHeaders($columnNames);
                $firstRow = false;
                continue;
            }

            $values = $row->toArray();
            $row_data = [];

            foreach ($columnNames as $key => $columnHeader) {
                $row_data[$columnHeader] = $values[$key] ?? null;
            }

            $data[$row_key] = $row_data;
        }

        foreach ($data as $item) {
            $email = $item['Professional Email Address'];

            if (!$email) continue;

            $existingEmployee = Employees::where('email_address', $email)->first();
            if ($existingEmployee) {
                throw new \Exception('Duplicate email found: ' . $email);
            }

            $designationId = optional(Designation::where('name', $item['Designation'])->first())->id;
            $departmentId = optional(Department::where('name', $item['Department'])->first())->id;
            $branchId = optional(Branch::where('name', $item['Branch Name'])->first())->id;
            $companyId = optional(Company::where('name', $item['Company Name'])->first())->id;

            $grossSalary = 0;
            $providentFund = 0;
            $eobi = 0;
            $netSalary = 0;
            $deductedAmount = 0;
            $employeeWelfare = 0;

            // $formattedDOB = $this->formatDate($item['Date of Birth']);
            // $formattedDOJ = $this->formatDate($item['Date of Joining']);

            $employee = Employees::create([
                'name' => $item['Applicant Name'],
                'father_name' => $item['Father Name'],
                'cnic_card' => $item['CNIC'],
                'mobile_no' => $item['Mobile No'],
                'tell_no' => $item['Mobile No'],
                'emergency_contact_no' => $item['Emergency Contact Number'],
                'personal_email_address' => $item['Personal Email Address'],
                'email_address' => $item['Professional Email Address'],
                'permanent_address' => $item['Permanent Address'],
                'present_address' => $item['Present Address'],
                'dob' => Helpers::transformDate($item['Date of Birth']),
                'start_date' => Helpers::transformDate($item['Date of Joining']),
                'gender' => match (strtolower(trim($item['Gender']))) {
                    'male' => 'M',
                    'female' => 'F',
                    default => '',
                },
                'applied' => $item['Applied before at cornerstone?'],
                'employed' => $item['Were you ever employed here?'],
                'engaged_business' => $item['Engagements in any other business or employment?'],
                'designation_id' => $designationId,
                'department_id' => $departmentId,
                'branch_id' => $branchId,
                'applied'=>$item['Applied before at cornerstone?'],
                'employed'=>$item['Were you ever employed here?'],
                'engaged_business'=>$item['Engagements in any other business or employment?'],
                'skills'=>$item['Skills or training related to the position?'],
                'specialization_subject'=>$item['Specialization'],
                'company_id' => $companyId,
                'emp_id'=>$item['Employee ID'],
                'job_seeking' => $item['Job Type'],
                'skills' => $item['Skills or training related to the position?'],
                'martial_status' => $item['Martial Status'],
                'specialization_subject' => $item['Specialization'],
                'work_shift_id' => WorkShift::where('name', trim($item['Work Shift']))->first()->id
            ]);

            $password = $item['Password'] ?? '12345678';

            $user = User::create([
                'name' => $item['Applicant Name'],
                'email' => $email,
                'password' => Hash::make($password),
                'employee_id' => $employee->id,
            ]);

            $roles = Role::where('name', 'General Employee')->pluck('id');
            $user->syncRoles($roles);

            if ($employeeWelfare) {
                EmployeeWelfare::create([
                    'employee_id' => $employee->id,
                    'month' => now()->format('M'),
                    'year' => now()->format('Y'),
                    'welfare_amount' => $employeeWelfare,
                ]);
            }

            if ($providentFund) {
                ProfitFund::create([
                    'employee_id' => $employee->id,
                    'month' => now()->format('M'),
                    'year' => now()->format('Y'),
                    'providentFund' => $providentFund,
                ]);
            }

            if ($deductedAmount) {
                MedicalAllowance::create([
                    'medicalAllowance' => $deductedAmount,
                    'month' => now()->format('M'),
                    'year' => now()->format('Y'),
                    'employee_id' => $employee->id,
                ]);
            }

            $eobiSettings = GeneralSettingsHelper::getSetting('eobi');
            Eobi::create([
                'employee_id' => $employee->id,
                'total' => $eobiSettings['total'] ?? 0,
                'employee_percent' => $eobiSettings['employee'] ?? 0,
                'company' => $eobiSettings['company'] ?? 0,
            ]);

            $this->ZktecoController->employeeGenerated([$employee->id]);
        }
    }

    private function formatDate($value)
    {
        if (!$value)
            return null;

        $formats = ['d-m-Y', 'd/m/Y', 'Y-m-d'];
        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $value);
            if ($date)
                return $date->format('Y-m-d');
        }

        return null;
    }

    private function isRowNull($row)
    {
        foreach ($row as $value) {
            if (!is_null($value))
                return false;
        }
        return true;
    }
}
