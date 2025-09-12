<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quota = [
            [
                'leave_type' => 'Academic Leaves',
                'permitted_days' => 12,
                'compensatory_status' => 0,
                'created_at' => now(),
            ],
            [
                'leave_type' => 'Annual Leaves',
                'permitted_days' => 24,
                'compensatory_status' => 0,
                'created_at' => now(),
            ],
            [
                'leave_type' => 'Maternity Leaves',
                'permitted_days' => 30,
                'compensatory_status' => 0,
                'created_at' => now(),
            ],
            [
                'leave_type' => 'Compensatory Leaves',
                'permitted_days' => 0,
                'compensatory_status' => 1,
                'created_at' => now(),
            ],
        ];

        DB::table('hr_quota_settings')->insert($quota);
    }
}
