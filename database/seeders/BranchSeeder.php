<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    public function run()
    {
        DB::table('branches')->insert([
            'company_id' => 1, // Ensure this matches an existing company
            'name' => 'Global Campus',
            'address' => 'Lahore, Pakistan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

         DB::table('branches')->insert([
            'company_id' => 1, // Ensure this matches an existing company
            'name' => 'PTCHS Campus',
            'address' => 'Lahore, Pakistan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
