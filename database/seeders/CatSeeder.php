<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
class CatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $cate = [
            'Administration',
            'Academics',
        ];



        foreach ($cate as $name) {
            Category::firstOrCreate(
                [
                    'name' => $name,
                   
                ],
                [
                   'name' => $name,
                    
                ]
            );
        }
    }
}
