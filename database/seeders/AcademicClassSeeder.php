<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use Illuminate\Support\Facades\DB;
use App\Models\Academic\SchoolType;
use App\Models\Academic\AcademicClass;
use Carbon\Carbon;

class AcademicClassSeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();
        if (!$company) {
            echo "❌ No company found.\n";
            return;
        }

        $branch = Branch::where('company_id', $company->id)->where('id', 1)->first();
        if (!$branch) {
            echo "❌ Branch with ID 1 not found for company {$company->id}\n";
            return;
        }

        $session = DB::table('acadmeic_sessions')->orderBy('id', 'desc')->first();
        if (!$session) {
            echo "❌ No session found.\n";
            return;
        }

        $schoolTypes = SchoolType::where([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
        ])->get();

        foreach ($schoolTypes as $i => $schoolType) {
            // 🔹 Create class
            $class = AcademicClass::create([
                'name' => 'Class-' . ($i + 1),
                'school_type_id' => $schoolType->id,
                'branch_id' => $branch->id,
                'company_id' => $company->id,
                'session_id' => $session->id,
            ]);

            // 🔹 Create sections for that class
            $sections = ['A', 'B']; // Add more if needed

            foreach ($sections as $sectionName) {
                DB::table('sections')->insert([
                    'name' => 'Section ' . $sectionName,
                    'class_id' => $class->id,
                    'session_id' => $session->id,
                    'active_session_id' => $session->id,
                    'branch_id' => $branch->id,
                    'company_id' => $company->id,
                    'status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

    }
}
