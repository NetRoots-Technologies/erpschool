<?php

namespace Database\Seeders;

use App\Models\Admin\CourseType;
use Illuminate\Database\Seeder;

class CreateCourseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CourseType::create([
            'name' => 'MATRICULATION',
            'description' => 'Matriculation Course',
        ]);
        CourseType::create([
            'name' => 'SCIENCE',
            'description' => 'Science Course',
        ]);
        CourseType::create([
            'name' => 'COMMERCE',
            'description' => 'Commerce Course',
        ]);
        CourseType::create([
            'name' => 'ARTS',
            'description' => 'Arts Course',
        ]);
    }
}
