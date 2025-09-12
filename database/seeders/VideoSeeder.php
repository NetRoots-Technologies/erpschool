<?php

namespace Database\Seeders;

use App\Models\Admin\VideoUpload;
use App\Models\HR\Agent;
use App\Models\HRM\Employees;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VideoUpload::create([
            'name' => 'Product Hunting Techniques',
            'url' => 'https://vimeo.com/753844295',
            'video_id' => '753844295',
            'video_description' => 'Product Hunting techniques that will help you to earn money from home',
            'video_categories_id' => '1',
            'course_id' => '1',
        ]);
    }
}
