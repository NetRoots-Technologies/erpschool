<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        // Fetch all department IDs
        $departmentIds = DB::table('departments')->pluck('id')->toArray();
        $workShiftIds = DB::table('work_shifts')->pluck('id')->toArray();

        if (empty($departmentIds) || empty($workShiftIds)) {
            $this->command->error('Departments or work shifts not found. Please seed those tables first.');
            return;
        }

        // 🔤 First & last name options
        $firstNames = ['Ali', 'Ahmad', 'Abdullah', 'Usman', 'Zain', 'Bilal', 'Hamza', 'Farhan', 'Sami', 'Raza'];
        $lastNames  = ['Khan', 'Ahmed', 'Butt', 'Sheikh', 'Rana', 'Malik', 'Qureshi', 'Mirza', 'Nawaz', 'Tariq'];

        $employees = [];

        for ($i = 1; $i <= 10; $i++) {
            // 🔁 Random full name
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $fullName = $firstName . ' ' . $lastName;

            $departmentId = $departmentIds[array_rand($departmentIds)];
            $designationId = DB::table('designations')->where('department_id', $departmentId)->inRandomOrder()->value('id');
            $workShiftId = $workShiftIds[array_rand($workShiftIds)];

            $employees[] = [
                'name' => $fullName,
                'emp_id' => 'ERTY-1 ' . $i,
                'email' => strtolower(str_replace(' ', '', $fullName)) . $i . '@example.com',
                'father_name' => 'Father ' . $i,
                'cnic_card' => '35202-123456' . $i . '-1',
                'tell_no' => '042-123456' . $i,
                'mobile_no' => '03' . rand(10, 49) . rand(1000000, 9999999),
                'email_address' => strtolower($firstName) . '.' . strtolower($lastName) . $i . '@mail.com',
                'present_address' => 'Street ' . $i . ', Lahore',
                'permanent_address' => 'Village ' . $i . ', Punjab',
                'working_hour' => '8',
                'hour_salary' => '500',
                'visitingLecturer' => 'No',
                'employeeWelfare' => 'Yes',
                'deductedAmount' => '0',
                'dob' => Carbon::now()->subYears(25)->format('Y-m-d'),
                'work_shift_id' => $workShiftId,
                'company_id' => 1,
                'branch_id' => 1,
                'department_id' => $departmentId,
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
        }

        DB::table('hrm_employees')->insert($employees);
    }
}
