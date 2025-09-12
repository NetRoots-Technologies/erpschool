<?php

namespace Database\Seeders;

use App\Models\Account\Ledger;
use Illuminate\Database\Seeder;

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
            'name' => 'Compny',
            'parent_type' => null,
            'number' => '0001-02 -0001',
            'group_number' => '0001-02',
            'code' => 'ASSETS ',
            'opening_balance' => 0,
            'closing_balance' => 0,
            'balance_type' => 'd',
            'account_type_id' => 1,
            'status' => ' 1',
        ]);
        Ledger::create([
            'name' => 'Compny',
            'parent_type' => null,
            'number' => '0001-02 -0002',
            'group_number' => '0001-02',
            'code' => 'ASSETS ',
            'opening_balance' => 0,
            'closing_balance' => 0,
            'balance_type' => 'd',
            'account_type_id' => 1,
            'status' => ' 1',
        ]);
        Ledger::create([
            'name' => 'Personal',
            'parent_type' => null,
            'number' => '0001-02 -0003',
            'group_number' => '0001-02',
            'code' => 'ASSETS ',
            'opening_balance' => 0,
            'closing_balance' => 0,
            'balance_type' => 'd',
            'account_type_id' => 1,
            'status' => ' 1',
        ]);


    }
}
