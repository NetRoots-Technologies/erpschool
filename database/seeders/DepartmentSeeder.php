<?php

namespace Database\Seeders;

use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Admin\Department;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $company = Company::where('name', 'Cornerstone Pvt Ltd')->first();
        $branches = Branch::pluck('id', 'name'); // ['Global Campus' => 1, 'PTCHS CAMPUS' => 2]
        $categories = Category::pluck('id', 'name'); // ['CFO' => 1, 'Head of Central Office' => 2]

        $userId = 1; // fake created_by, updated_by user (you can update this based on auth or testing user)

        // Create a top-level department (parent)
        $headOffice = Department::create([
            'name' => 'Head Office',
            'company_id' => $company->id,
            'branch_id' => $branches['Global Campus'] ?? null,
            'category_id' => $categories['Head of Central Office'] ?? null,
            'parent_id' => null,
            'created_by' => $userId,
            'updated_by' => $userId,
            'deleted_by' => null,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Now 9 child departments under Head Office
        $departmentNames = [
            'Finance Department',
            'HR Department',
            'IT Support',
            'Academic Affairs',
            'Student Services',
            'Operations',
            'Logistics',
            'Marketing & Admissions',
            'Quality Assurance'
        ];

        foreach ($departmentNames as $name) {
            Department::create([
                'name' => $name,
                'company_id' => $company->id,
                'branch_id' => $branches['Global Campus'] ?? null, // always Global Campus
                'category_id' => $categories['Head of Central Office'] ?? null, // fixed category
                'parent_id' => $headOffice->id,
                'created_by' => $userId,
                'updated_by' => $userId,
                'deleted_by' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
