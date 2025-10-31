<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $designations = [
           
    // 🧹 Housekeeping (id = 1)
    ['name' => 'Supervisor', 'status' => 1, 'department_id' => 1, 'created_at' => now()],
    ['name' => 'Maid', 'status' => 1, 'department_id' => 1, 'created_at' => now()],
    ['name' => 'Office Boy', 'status' => 1, 'department_id' => 1, 'created_at' => now()],
    ['name' => 'Sweeper', 'status' => 1, 'department_id' => 1, 'created_at' => now()],
    ['name' => 'Painter', 'status' => 1, 'department_id' => 1, 'created_at' => now()],

    // 🏫 Academics Teaching (id = 2)
    ['name' => 'Visiting Teacher', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'Teacher', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'Therapist', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'ABA Therapist', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'Behavior Intervention Specialist', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'Clinical Psychologist', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'Occupational Therapist', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'Teacher Assistant', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'Speech and Language Pathologist', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'Speech Therapist', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'SEN Psychologist', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'SEN Coordinator', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'Entrepreneurship Teacher', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'Music Teacher', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'Sports Teacher', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'Sports Manager', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'IT Support Engineer', 'status' => 1, 'department_id' => 2, 'created_at' => now()],
    ['name' => 'Data Analyst', 'status' => 1, 'department_id' => 2, 'created_at' => now()],

    // 🧾 Academics Non Teaching (id = 3)
    ['name' => 'SEN Program Manager', 'status' => 1, 'department_id' => 3, 'created_at' => now()],
    ['name' => 'Coordinator', 'status' => 1, 'department_id' => 3, 'created_at' => now()],
    ['name' => 'Principal', 'status' => 1, 'department_id' => 3, 'created_at' => now()],
    ['name' => 'Manager Accounts and Finance', 'status' => 1, 'department_id' => 3, 'created_at' => now()],
    ['name' => 'Vice Principal', 'status' => 1, 'department_id' => 3, 'created_at' => now()],
    ['name' => 'Daycare Taker', 'status' => 1, 'department_id' => 3, 'created_at' => now()],

    // 🏢 Admin (id = 4)
    ['name' => 'Waiter', 'status' => 1, 'department_id' => 4, 'created_at' => now()],
    ['name' => 'Bakery Chef', 'status' => 1, 'department_id' => 4, 'created_at' => now()],
    ['name' => 'Headmistress Operations Services', 'status' => 1, 'department_id' => 4, 'created_at' => now()],
    ['name' => 'Event Organizer', 'status' => 1, 'department_id' => 4, 'created_at' => now()],
    ['name' => 'Jackie Chef', 'status' => 1, 'department_id' => 4, 'created_at' => now()],
    ['name' => 'Website Maintenance Officer', 'status' => 1, 'department_id' => 4, 'created_at' => now()],
    ['name' => 'Librarian', 'status' => 1, 'department_id' => 4, 'created_at' => now()],
    ['name' => 'Head of Marketing and Branding', 'status' => 1, 'department_id' => 4, 'created_at' => now()],
    ['name' => 'Media Incharge', 'status' => 1, 'department_id' => 4, 'created_at' => now()],
    ['name' => 'CCTV Operator', 'status' => 1, 'department_id' => 4, 'created_at' => now()],
    ['name' => 'Admission Officer', 'status' => 1, 'department_id' => 4, 'created_at' => now()],
    ['name' => 'Photographer / Cameraman', 'status' => 1, 'department_id' => 4, 'created_at' => now()],
    ['name' => 'Gardner', 'status' => 1, 'department_id' => 4, 'created_at' => now()],
    ['name' => 'Electrician', 'status' => 1, 'department_id' => 4, 'created_at' => now()],
    ['name' => 'AC Technician', 'status' => 1, 'department_id' => 4, 'created_at' => now()],
    ['name' => 'Front Desk Officer', 'status' => 1, 'department_id' => 4, 'created_at' => now()],
    ['name' => 'Nurse', 'status' => 1, 'department_id' => 4, 'created_at' => now()],

    // 🚍 Transport (id = 5)
    ['name' => 'Driver', 'status' => 1, 'department_id' => 5, 'created_at' => now()],
    ['name' => 'Bus Helper', 'status' => 1, 'department_id' => 5, 'created_at' => now()],

    // 🛡️ Safety and Security (id = 6)
    ['name' => 'Senior Supervisor', 'status' => 1, 'department_id' => 6, 'created_at' => now()],
    ['name' => 'Security Guard', 'status' => 1, 'department_id' => 6, 'created_at' => now()],

    // 💰 Fee (id = 7)
    ['name' => 'Fee Officer', 'status' => 1, 'department_id' => 7, 'created_at' => now()],





        ];
        DB::table('designations')->insert($designations);
    }
}
