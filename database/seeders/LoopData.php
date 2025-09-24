<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use App\Models\Admin\Session;
use App\Models\Category;
use App\Models\Admin\Department;
use App\Models\Student\Students;
use App\Models\Academic\AcademicClass;
use App\Models\Academic\Section;

class LoopData extends Seeder
{
    public function run()
    {
        if (env('APP_ENV') !== 'local') {
            return;
        }

        // ✅ Get existing company
        $company = Company::first();
        if (!$company) {
            echo "⚠️ No company found.\n";
            return;
        }

        // ✅ Get existing branches by name
        $branches = Branch::whereIn('name', ['Global Campus', 'PTCHS Campus'])->get();
        if ($branches->count() < 2) {
            echo "⚠️ One or both branches not found. Please check branch names.\n";
            return;
        }

        // ✅ Create shared category and session
        $category = Category::create(["name" => "Category " . now()]);
        $session = Session::create([
            "course_id" => 1,
            'title' => now()->format('Y') . '-' . now()->addYear()->format('Y') . ' Session',
            'start_date' => now()->subMonth()->startOfMonth()->toDateString(),
            'end_date' => now()->addMonths(2)->endOfMonth()->toDateString(),
            'status' => '1'
        ]);

        // ✅ Sample name data
        $firstNames = ['Ahmed', 'Ali', 'Zain', 'Usman', 'Bilal', 'Hamza', 'Ayesha', 'Fatima', 'Sara', 'Zara'];
        $lastNames  = ['Khan', 'Malik', 'Sheikh', 'Butt', 'Chaudhry', 'Raza', 'Hussain', 'Shah', 'Qureshi', 'Hashmi'];
        $fatherNames = ['Tariq', 'Nadeem', 'Aslam', 'Rashid', 'Sohail', 'Ijaz', 'Jameel', 'Rafiq', 'Faisal', 'Khalid'];

        // ✅ Loop through each existing branch
        foreach ($branches as $branch) {

            $class = AcademicClass::where('branch_id', $branch->id)->first();
            $section = Section::where('branch_id', $branch->id)->first();

            if (!$class || !$section) {
                echo "⚠️ No class or section found for Branch: {$branch->name}. Skipping student creation.\n";
                continue;
            }

            // ✅ Create 2 departments per branch
            for ($k = 0; $k < 2; $k++) {
                $department = Department::create([
                    "name" => "Department $k",
                    "branch_id" => $branch->id,
                    "company_id" => $company->id,
                    "status" => 1,
                    "category_id" => $category->id
                ]);

                for ($m = 0; $m < 2; $m++) {
                    $father_cnic = time() + rand(1000, 9999); // Unique-ish CNIC

                    for ($n = 0; $n < 3; $n++) {
                        $gender = ['m', 'f'];
                        $firstName = $firstNames[array_rand($firstNames)];
                        $lastName = $lastNames[array_rand($lastNames)];
                        $fatherName = $fatherNames[array_rand($fatherNames)];

                        Students::create([
                            "admission_class" => $class->name,
                            "campus" => $branch->id,
                            "first_name" => $firstName,
                            "last_name" => $lastName,
                            "father_name" => $fatherName,
                            "father_cnic" => $father_cnic,
                            "gender" => $gender[$n % 2],
                            "city" => "City " . $department->id,
                            "country" => "Pakistan",
                            "class_id" => $class->id,
                            "session_id" => $session->id,
                            "section_id" => $section->id,
                            "branch_id" => $branch->id,
                            "company_id" => $company->id,
                            "student_email" => strtolower($firstName . '.' . $lastName . rand(100,999) . '@gmail.com')
                        ]);
                    }

                    sleep(1); // prevent CNIC collision
                }
            }
        }
    }
}
