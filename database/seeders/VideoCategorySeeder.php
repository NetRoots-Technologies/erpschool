<?php

namespace Database\Seeders;

use App\Models\Admin\Session;
use App\Models\Admin\VideoCategory;
use Illuminate\Database\Seeder;

class VideoCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VideoCategory::create([
            'name' => 'Intoductory Video',
            'course_id' => '1',

        ]);

        VideoCategory::create([
            'name' => 'Product Hunting',
            'course_id' => '1',
        ]);

        VideoCategory::create([
            'name' => 'Dropshipping Introduction',
            'course_id' => '2',
        ]);
    }
}
