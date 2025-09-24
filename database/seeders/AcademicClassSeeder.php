<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Company;
use App\Models\Admin\Session;
use App\Models\Academic\SchoolType;
use App\Models\Academic\AcademicClass;

class AcademicClassSeeder extends Seeder
{
    public function run()
    {
        // ✅ Get the first company
        $company = Company::first();
        if (!$company) {
            echo "❌ No company found.\n";
            return;
        }

        // ✅ Get the latest session
        $session = Session::latest()->first();
        if (!$session) {
            echo "❌ No session found.\n";
            return;
        }

        // ✅ Get all school types for the company
        $schoolTypes = SchoolType::where('company_id', $company->id)->get();

        if ($schoolTypes->isEmpty()) {
            echo "❌ No school types found for company.\n";
            return;
        }

        foreach ($schoolTypes as $schoolType) {
            // Count existing classes
            $existingCount = AcademicClass::where('school_type_id', $schoolType->id)->count();

            $needed = max(0, 10 - $existingCount); // Ensure at least 10

            for ($i = 1; $i <= $needed; $i++) {
                AcademicClass::create([
                    'name' => 'Class ' . $i,
                    'school_type_id' => $schoolType->id,
                    'branch_id' => $schoolType->branch_id,
                    'company_id' => $schoolType->company_id,
                    'session_id' => $session->id,
                ]);
            }

            echo "✅ Created $needed classes for SchoolType ID: {$schoolType->id} (Branch: {$schoolType->branch_id})\n";
        }
    }
}
