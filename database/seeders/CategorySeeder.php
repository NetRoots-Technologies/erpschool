<?php

namespace Database\Seeders;

use App\Models\Category;
use FontLib\Table\Type\name;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { {
            Category::create([
                'name' => 'CFO',
                'created_at' => now(),
            ]);

            Category::create([
                'name' => 'Head of Central Office',
                'created_at' => now(),
            ]);
        }
    }
}
