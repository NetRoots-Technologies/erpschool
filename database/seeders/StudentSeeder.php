<?php

namespace Database\Seeders;

use App\Models\Admin\City;
use App\Models\Student\StudentDetail;
use App\Models\Student\Students;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $student = Students::create([
            'name' => 'Umar Rasheed',
            'email' => 'umar@gmail.com',
            'mobile_no' => '03164225320',
            'agent_id' => '1',
        ]);
        StudentDetail::create([
            'student_id' => $student->id,
            'gender' => 'male',
            'marital_status' => 'single',
            'student_dob' => '27-09-1997',
            'nationality' => 'Pakistan',
            'passport_cnic' => '123456789',
            'pass_cnic_expiry' => '27-09-2022',
            'address_country' => 'Pakistan',
            'address_state' => 'Punjab',
            'address_city' => 'Lahore',
            'address' => 'Davis Road',
            'guardian_name' => 'Rasheed Ahmed',
            'guardian_occupation' => 'Businessmen',
            'guardian_mobile_no' => '12345678',
            'guardian_relation_with_student' => 'Father',
        ]);
        $student = Students::create([
            'name' => 'ashar Rasheed',
            'email' => 'ashar@gmail.com',
            'mobile_no' => '03164225320',
            'agent_id' => '1',
        ]);
        StudentDetail::create([
            'student_id' => $student->id,
            'gender' => 'male',
            'marital_status' => 'single',
            'student_dob' => '27-09-1997',
            'nationality' => 'Pakistan',
            'passport_cnic' => '123456789',
            'pass_cnic_expiry' => '27-09-2022',
            'address_country' => 'Pakistan',
            'address_state' => 'Punjab',
            'address_city' => 'Lahore',
            'address' => 'Davis Road',
            'guardian_name' => 'Rasheed Ahmed',
            'guardian_occupation' => 'Businessmen',
            'guardian_mobile_no' => '12345678',
            'guardian_relation_with_student' => 'Father',
        ]);
        $student = Students::create([
            'name' => 'test Rasheed',
            'email' => 'ashar@gmail.com',
            'mobile_no' => '03164225320',

        ]);
        StudentDetail::create([
            'student_id' => $student->id,
            'gender' => 'male',
            'marital_status' => 'single',
            'student_dob' => '27-09-1997',
            'nationality' => 'Pakistan',
            'passport_cnic' => '123456789',
            'pass_cnic_expiry' => '27-09-2022',
            'address_country' => 'Pakistan',
            'address_state' => 'Punjab',
            'address_city' => 'Lahore',
            'address' => 'Davis Road',
            'guardian_name' => 'Rasheed Ahmed',
            'guardian_occupation' => 'Businessmen',
            'guardian_mobile_no' => '12345678',
            'guardian_relation_with_student' => 'Father',
        ]);

        $student = Students::create([
            'name' => 'ashar Rasheed',
            'email' => 'ashar@gmail.com',
            'mobile_no' => '03164225320',
            'agent_id' => '1',
        ]);

        $student = Students::create([
            'name' => 'ashar Rasheed',
            'email' => 'ashar@gmail.com',
            'mobile_no' => '03164225320',
            'agent_id' => '1',
        ]);

        $student = Students::create([
            'name' => 'ashar Rasheed',
            'email' => 'ashar@gmail.com',
            'mobile_no' => '03164225320',
            'agent_id' => '1',
        ]);

        $student = Students::create([
            'name' => 'ashar Rasheed',
            'email' => 'ashar@gmail.com',
            'mobile_no' => '03164225320',
            'agent_id' => '1',
        ]);

        $student = Students::create([
            'name' => 'ashar Rasheed',
            'email' => 'ashar@gmail.com',
            'mobile_no' => '03164225320',
            'agent_id' => '1',
        ]);

        $student = Students::create([
            'name' => 'ashar Rasheed',
            'email' => 'ashar@gmail.com',
            'mobile_no' => '03164225320',
            'agent_id' => '1',
        ]);

        $student = Students::create([
            'name' => 'ashar Rasheed',
            'email' => 'ashar@gmail.com',
            'mobile_no' => '03164225320',
            'agent_id' => '1',
        ]);

        $student = Students::create([
            'name' => 'ashar Rasheed',
            'email' => 'ashar@gmail.com',
            'mobile_no' => '03164225320',
            'agent_id' => '1',
        ]);

        $student = Students::create([
            'name' => 'ashar Rasheed',
            'email' => 'ashar@gmail.com',
            'mobile_no' => '03164225320',
            'agent_id' => '1',
        ]);


    }
}
