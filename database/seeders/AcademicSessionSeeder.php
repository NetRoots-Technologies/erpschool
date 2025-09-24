<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AcademicSessionSeeder extends Seeder
{
    public function run()
    {
        DB::table('acadmeic_sessions')->insert([
            'company_id' => 1,       // Replace with your actual company_id
            'branch_id' => 1,        // Optional: replace or make it null
            'school_id' => 1,        // Replace with your actual school_id
            'name' => 'Academic 2025-26',
            'start_date' => Carbon::create(2025, 8, 1),
            'end_date' => Carbon::create(2026, 6, 30),
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
