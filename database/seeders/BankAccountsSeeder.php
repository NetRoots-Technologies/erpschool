<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('banks_accounts')->insert([
            [
                'bank_id' => 1, // HBL
                'bank_branch_id' => 1, // HBL Main Branch
                'account_no' => 'PK36HBL00000000000001',
                'type' => 'MOA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bank_id' => 1,
                'bank_branch_id' => 2,
                'account_no' => 'PK36HBL00000000000002',
                'type' => 'MCA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bank_id' => 2, // UBL
                'bank_branch_id' => 3,
                'account_no' => 'PK36UBL00000000000001',
                'type' => 'MOA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bank_id' => 3, // Meezan
                'bank_branch_id' => 4,
                'account_no' => 'PK36MEZ00000000000001',
                'type' => 'MCA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
