<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Academic',
            'Administration',
            'Finance',
            'HR',
            'Executive',
            'Central',
            'Consultant',
            'IT',
            'Curriculum',
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate(['name' => $categoryName]);
        }
    }
}
