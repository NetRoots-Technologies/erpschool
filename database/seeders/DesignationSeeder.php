<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $designations = [
            [
                'name' => 'Head Coordinate',
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Academic',
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Teacher',
                'status' => 1,
                'created_at' => now(),
            ],

        ];
        DB::table('designations')->insert($designations);
    }
}
