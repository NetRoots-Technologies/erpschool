<?php

namespace Database\Seeders;

use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Academic\SchoolType;

class SchoolTypeSeeder extends Seeder
{
    public function run()
    {
        // ✅ Get the first company
        $company = Company::first();
        if (!$company) {
            echo "❌ No company found.\n";
            return;
        }

        // ✅ Get the first branch of that company
        $branch = Branch::where('company_id', $company->id)->first();
        if (!$branch) {
            echo "❌ No branch found for company ID {$company->id}.\n";
            return;
        }

        // ✅ Predefined 5 school type names
        $schoolTypeNames = [
            'Primary School',
            'Middle School',
            'High School',
            'A Level School',
            'O Level School',
        ];

        // ✅ Check how many already exist
        $existingCount = SchoolType::where('company_id', $company->id)
            ->where('branch_id', $branch->id)
            ->count();

        $needed = max(0, 5 - $existingCount);

        for ($i = 0; $i < $needed; $i++) {
           $one = SchoolType::create([
                'name' => $schoolTypeNames[$i],
                'company_id' => $company->id,
                'branch_id' => $branch->id,
                'status' => 1,
            ]);

             DB::table("branches_schools")->insert(
                [
                'school_type_id' => $one->id,
                'branch_id' => $one->branch_id,     
                'created_at' => now(),
                'updated_at' => now(),           
              ]
        );


        }

    }
}
