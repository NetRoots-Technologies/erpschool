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
            'name' => 'AMAZON TRAINING',
            'description' => 'AMAZON TRAINING',

        ]);
        CourseType::create([
            'name' => 'DIGITAL MARKETING',
            'description' => 'DIGITAL MARKETING',
        ]);
        CourseType::create([
            'name' => 'DEVELOPMENT',
            'description' => 'DEVELOPMENT',
        ]);
        CourseType::create(
            [
                'name' => 'GRAPHICS',
                'description' => 'GRAPHICS',

            ]
        );
    }
}
