<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WorkShiftSeeder extends Seeder
{
    public function run()
    {
        DB::table('work_shifts')->insert([
            [
                'name' => 'Morning Shift',
                'start_date' => Carbon::today()->format('Y-m-d'),  // or a fixed date
                'end_date' => Carbon::today()->addYears(1)->format('Y-m-d'),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Evening Shift',
                'start_date' => Carbon::today()->format('Y-m-d'),
                'end_date' => Carbon::today()->addYears(1)->format('Y-m-d'),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
