<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use App\Models\Academic\SchoolType;

class SchoolTypeSeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();


        $branches = Branch::where('company_id', $company->id)->get();

        // Realistic school type names
        $schoolTypeNames = [
            'Primary School',
            'Middle School',
            'High School',
            'Higher Secondary School',
            'Matriculation School',
            'O-Level School',
            'A-Level School',
            'Montessori School',
            'Kindergarten',
            'Technical School',
        ];

        foreach ($branches as $branch) {
            $existingCount = SchoolType::where('company_id', $company->id)
                ->where('branch_id', $branch->id)
                ->count();

            $needed = max(0, 10 - $existingCount);

            for ($i = 0; $i < $needed; $i++) {
                SchoolType::create([
                    'name' => $schoolTypeNames[$i],
                    'company_id' => $company->id,
                    'branch_id' => $branch->id,
                    'status' => 1,
                ]);
            }

        }
    }
}
