<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Departments;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // 🔹 Example departments (assume already seeded)
        $financeDept   = Departments::where('name', 'Finance Department')->first();
        $academicDept  = Departments::where('name', 'Academic Affairs')->first();
        $teachingDept  = Departments::where('name', 'Head Office')->first();

        $designations = [
            [
                'name' => 'Head Coordinate',
                'department_id' => $financeDept ? $financeDept->id : null,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Academic',
                'department_id' => $academicDept ? $academicDept->id : null,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Teacher',
                'department_id' => $teachingDept ? $teachingDept->id : null,
                'status' => 1,
                'created_at' => now(),
            ],
        ];

        DB::table('designations')->insert($designations);
    }
}
