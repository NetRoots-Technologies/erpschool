<?php

namespace Database\Seeders;
use App\Models\Admin\Course;
use Illuminate\Database\Seeder;

class CreateCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Course::create([
            'course_type_id' => '1',
            'name' => 'AMAZON PL FBA',
            //'course_duration' => '2 months',
            'fee' => '10000',

        ]);
        Course::create([
            'course_type_id' => '1',
            'name' => 'DROPSHIPPING',
            //'course_duration' => '2 months',
            'fee' => '15000',

        ]);
        Course::create([
            'course_type_id' => '1',
            'name' => 'AMAZON WHOLESALE FBA',
            //'course_duration' => '2 months',
            'fee' => '12000',

        ]);
        Course::create([
            'course_type_id' => '2',
            'name' => 'SEO',
            //'course_duration' => '2 months',
            'fee' => '20000',

        ]);
        Course::create([

            'course_type_id' => '2',
            'name' => 'SMM',
            //'course_duration' => '2 months',
            'fee' => '50000',

        ]);
        Course::create([
            'course_type_id' => '2',
            'name' => 'SEM',
            //'course_duration' => '2 months',
            'fee' => '10000',

        ]);
        Course::create([
            'course_type_id' => '3',
            'name' => 'WEBSITE DEVELOPMENT',
            //'course_duration' => '2 months',
            'fee' => '35000',

        ]);
        Course::create([
            'course_type_id' => '4',
            'name' => 'GRAPHIC DESIGNING',
            //'course_duration' => '2 months',
            'fee' => '15000',

        ]);

    }
}
