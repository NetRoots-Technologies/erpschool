<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
public function run()
{
    // Fetch all departments
    $departments = DB::table('departments')->get();

    // Fetch all work shifts
    $workShiftIds = DB::table('work_shifts')->pluck('id')->toArray();

    if ($departments->isEmpty() || empty($workShiftIds)) {
        $this->command->error('Departments or work shifts not found. Please seed those tables first.');
        return;
    }

    $employees = [];
    $i = 1;

    foreach ($departments as $department) {
        // Try to find a designation for this department
        $designationId = DB::table('designations')
            ->where('department_id', $department->id)
            ->inRandomOrder()
            ->value('id');

        // If no designation found, skip
        if (!$designationId) {
            $this->command->warn("No designation found for department ID: {$department->id}, skipping...");
            continue;
        }

        // Random work shift
        $workShiftId = $workShiftIds[array_rand($workShiftIds)];

        $employees[] = [
            'name' => 'Employee ' . $i,
            'emp_id' => 'EMP-' . str_pad($i, 4, '0', STR_PAD_LEFT),
            'email' => 'employee' . $i . '@example.com',
            'father_name' => 'Father ' . $i,
            'cnic_card' => '35202-123456' . $i,
            'tell_no' => '042-123456' . $i,
            'mobile_no' => '03' . rand(10, 49) . rand(1000000, 9999999),
            'email_address' => 'employee' . $i . '@mail.com',
            'present_address' => 'Street ' . $i . ', Lahore',
            'permanent_address' => 'Village ' . $i . ', Punjab',
            'working_hour' => '8',
            'hour_salary' => '500',
            'visitingLecturer' => 'No',
            'employeeWelfare' => 'Yes',
            'deductedAmount' => '0',
            'dob' => Carbon::now()->subYears(25)->format('Y-m-d'),
            'work_shift_id' => $workShiftId,
            'company_id' => $department->company_id,
            'branch_id' => $department->branch_id,
            'department_id' => $department->id,
            'designation_id' => $designationId,
            'employee_id' => null,
            'other_branch' => null,
            'job_seeking' => 'No',
            'start_date' => Carbon::now()->subYears(1)->format('Y-m-d'),
            'salary' => '40000',
            'applied' => 'No',
            'applied_yes' => null,
            'employed' => 'Yes',
            'when_employed_yes' => '2022',
            'engaged_business' => 'No',
            'when_business_yes' => null,
            'skills' => 'Laravel, PHP, HTML',
            'nationality' => 'Pakistani',
            'religion' => 'Islam',
            'blood_group' => 'B+',
            'marital_status' => 'Single',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $i++;
    }

    // Insert all generated employees
    DB::table('hrm_employees')->insert($employees);
}


}
