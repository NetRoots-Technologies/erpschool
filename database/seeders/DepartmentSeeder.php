<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Department;
use App\Models\Admin\Branch;
use App\Models\Category;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        // Department name => category_id
        $departments = [
            'Current Assets' => 1,
            'Admissions' => 1,
            'Admin' => 1,
            'Visiting' => 1,
            'Marketing Team' => 1,
            'HR Department' => 1,
            'Custodian Staff' => 2,
            'SEN-CSS' => 2,
            'Central Office - Admin' => 2,
            'Academics' => 1,
            'Security' => 2,
            'Accounts' => 2,
            'IT' => 1,
            'HR' => 1,
            'Teacher' => 2,
        ];

        foreach ($departments as $name => $categoryId) {
            Department::firstOrCreate(
                [
                    'name' => $name,
                    'company_id' => 1,
                    'branch_id' => 1,
                ],
                [
                    'category_id' => $categoryId,
                    'parent_id' => null,
                ]
            );
        }
    }
}
