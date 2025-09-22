<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Department;
use App\Models\HR\Designation;

class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();

        foreach ($departments as $department) {
           Designation::firstOrCreate(
    [
        'name' => 'Manager - ' . $department->name,
        'department_id' => $department->id,
    ],
    [
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ]
);

        }
    }
}
