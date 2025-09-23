<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankBranchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Make sure bank IDs (1, 2, etc.) exist in 'banks' table
        DB::table('banks_branches')->insert([
            [
                'bank_id' => 1, // HBL
                'branch_code' => 'HBL-001',
                'branch_name' => 'HBL Main Branch',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bank_id' => 1,
                'branch_code' => 'HBL-002',
                'branch_name' => 'HBL North Branch',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bank_id' => 2, // UBL
                'branch_code' => 'UBL-001',
                'branch_name' => 'UBL Central Branch',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bank_id' => 3, // Meezan
                'branch_code' => 'MZN-001',
                'branch_name' => 'Meezan Saddar Branch',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
