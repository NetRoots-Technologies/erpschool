<?php

namespace Database\Seeders;

use App\Models\Academic\AcademicClass;
use App\Models\Academic\Section;
use App\Models\Category;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Admin\Session;
use Illuminate\Database\Seeder;
use App\Models\Admin\Department;
use App\Models\Student\Students;
use App\Models\Academic\SchoolType;

class LoopData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env('APP_ENV') != 'local') {
            return;
        }
        $category = Category::create(["name" => "Category " . now()]);
        $session = Session::create([
            "course_id" => 1,
            'title' => now()->format('Y') . '-' . now()->addYear()->format('Y') . ' Session',
            'start_date' => now()->subMonth()->startOfMonth()->toDateString(),
            'end_date' => now()->addMonths(2)->endOfMonth()->toDateString(),
            'status' => '1'
        ]);

        for ($i = 0; $i < 2; $i++) {
            $company = Company::create([
                "name" => "Company $i"
            ]);
            for ($j = 0; $j < 2; $j++) {
                $branch = Branch::create([
                    "company_id" => $company->id,
                    "name" => "Branch $j"
                ]);
                $school_type = SchoolType::create([
                    "name" => "School " . $branch->id,
                    "company_id" => $company->id,
                    "branch_id" => $branch->id,
                    "status" => 1
                ]);
                $class = AcademicClass::create([
                    "name" => "Class $j",
                    "school_type_id" => $school_type->id,
                    "branch_id" => $branch->id,
                    "session_id" => $session->id,
                    "company_id" => $company->id,
                ]);
                $section = Section::create([
                    "name" => "Section $j",
                    "class_id" => $class->id,
                    "session_id" => $session->id,
                    "branch_id" => $branch->id,
                    "company_id" => $company->id,
                    'status' => '1'
                ]);
                for ($k = 0; $k < 2; $k++) {
                    $department = Department::create([
                        "name" => "Department $k",
                        "branch_id" => $branch->id,
                        "company_id" => $company->id,
                        "status" => 1,
                        "category_id" => $category->id
                    ]);
                    for ($m = 0; $m < 2; $m++) {
                        $father_cnic = time();
                        for ($n = 0; $n < 3; $n++) {
                            $gender = ['m', 'f'];
                            $student = Students::create([
                                "admission_class" => "class $n",
                                "campus" => $branch->id,
                                "first_name" => "First $n",
                                "last_name" => "Last $n",
                                "father_name" => "fater name $m",
                                "father_cnic" => $father_cnic,
                                "gender" => $gender[$n] ?? "m",
                                "city" => "city " . $department->id,
                                "country" => "country " . $department->id,
                                "class_id" => $class->id,
                                "session_id" => $session->id,
                                "section_id" => $section->id,
                                "branch_id" => $branch->id,
                                "company_id" => $company->id,
                                "student_email" => "student" . $company->id . $branch->id . $department->id . $n . "@gmail.com"
                            ]);
                        }
                        sleep(1);
                    }
                }
            }
        }
    }
}
