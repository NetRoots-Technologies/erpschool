<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account\Ledger;

class LedgersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ledger::create([
            'name' => 'Company A',
            'parent_type' => 'App\Models\Vendor', // or null if not used yet
            'parent_id' => 1, // ID of the vendor/customer/etc.
            'number' => '0001-02-0001',
            'group_number' => '0001-02',
            'code' => 'ASSETS',
            'opening_balance' => 0,
            'closing_balance' => 0,
            'balance_type' => 'd', // debit
            'account_type_id' => 1, // Must exist in account_types table
            'status' => 1,
        ]);

        Ledger::create([
            'name' => 'Company B',
            'parent_type' => 'App\Models\Vendor',
            'parent_id' => 2,
            'number' => '0001-02-0002',
            'group_number' => '0001-02',
            'code' => 'LIABILITIES',
            'opening_balance' => 0,
            'closing_balance' => 0,
            'balance_type' => 'd',
            'account_type_id' => 2,
            'status' => 1,
        ]);

        Ledger::create([
            'name' => 'Customer A',
            'parent_type' => 'App\Models\Customer',
            'parent_id' => 3,
            'number' => '0001-02-0003',
            'group_number' => '0001-02',
            'code' => 'ASSETS',
            'opening_balance' => 0,
            'closing_balance' => 0,
            'balance_type' => 'd',
            'account_type_id' => 3,
            'status' => 1,
        ]);
    }
}
