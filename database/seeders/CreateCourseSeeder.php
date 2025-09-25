<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateCourseSeeder extends Seeder
{
      public function run()
    {
        $companyId = 1;
        $branchId = 1;
        $sessionId = null; // ✅ Set to 1 as per your request

        $courses = [
            ['course_type_id' => 1, 'name' => 'Math'],
            ['course_type_id' => 1, 'name' => 'English'],
            ['course_type_id' => 1, 'name' => 'Urdu'],
            ['course_type_id' => 1, 'name' => 'Science'],
            ['course_type_id' => 1, 'name' => 'Islamiyat'],
            ['course_type_id' => 2, 'name' => 'Computer'],
            ['course_type_id' => 2, 'name' => 'Physics'],
            ['course_type_id' => 2, 'name' => 'Chemistry'],
            ['course_type_id' => 2, 'name' => 'Biology'],
            ['course_type_id' => 3, 'name' => 'Economics'],
            ['course_type_id' => 3, 'name' => 'Statistics'],
            ['course_type_id' => 3, 'name' => 'Accounting'],
        ];

        foreach ($courses as $course) {
            DB::table('courses')->insert([
                'name' => $course['name'],
                'course_type_id' => $course['course_type_id'],
                'company_id' => $companyId,
                'branch_id' => $branchId,
                'session_id' => $sessionId,           // ✅ Fixed
                'active_session_id' => $sessionId,    // ✅ Fixed
                'status' => 1,                         // ✅ Always active
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}