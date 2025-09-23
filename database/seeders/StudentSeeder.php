<?php

namespace Database\Seeders;

use App\Models\Student\Students;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 10; $i++) {
            Students::create([
                'admission_class' => $faker->randomElement(['Nursery', 'Prep', '1', '2', '3', '4']),
                'admission_date' => $faker->date(),
                'campus' => $faker->randomElement(['Global Campus', 'PTCHS Campus']),
                'special_child' => $faker->randomElement(['Yes', 'No']),
                'special_needs' => $faker->randomElement(['Yes', 'No']),
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'father_name' => $faker->name('male'),
                'father_cnic' => $faker->bothify('#####-#######-#'),
                'is_guardian' => $faker->boolean(20), // 20% chance true
                'guardian_name' => $faker->name,
                'guardian_cnic' => $faker->bothify('#####-#######-#'),
                'gender' => $faker->randomElement(['male', 'female']),
                'student_dob' => $faker->date('Y-m-d', '2005-01-01'),
                'student_current_address' => $faker->address,
                'student_permanent_address' => $faker->address,
                'city' => $faker->city,
                'country' => $faker->country,
                'cell_no' => $faker->phoneNumber,
                'landline' => $faker->phoneNumber,
                'student_email' => $faker->unique()->safeEmail,
                'native_language' => $faker->languageCode,
                'first_language' => $faker->languageCode,
                'second_language' => $faker->languageCode,
                'meal_option' => $faker->randomElement(['Veg', 'Non-Veg', 'None']),
                'easy_urdu' => $faker->boolean(10), // 10% chance
                'student_id' => $faker->unique()->bothify('STU-#####'),
                'class_id' => rand(1, 5), // Assuming you have classes seeded
                'session_id' => rand(1, 3), // Assuming sessions seeded
                'section_id' => rand(1, 3), // Assuming sections seeded
                'branch_id' => rand(1, 2), // Assuming branches seeded
                'company_id' => 1, // Assuming single company for now
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
