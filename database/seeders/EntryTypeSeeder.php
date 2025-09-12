<?php

namespace Database\Seeders;

use App\Models\Account\Ledger;
use App\Models\Admin\EntryTypes;
use Illuminate\Database\Seeder;

class EntryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EntryTypes::create([
            'name' => 'Journal Voucher',
            'code' => 'JV',
        ]);
        EntryTypes::create([
            'name' => 'Fee Receive Voucher',
            'code' => 'FRV',
        ]);
        EntryTypes::create([
            'name' => 'Tools Fee Receive Voucher',
            'code' => 'TFRV',
        ]);
        EntryTypes::create([
            'name' => 'Cash Receipt Voucher',
            'code' => 'CRV',
        ]);
        EntryTypes::create([
            'name' => 'Cash Payment Voucher',
            'code' => 'CPV',
        ]);
        EntryTypes::create([
            'name' => 'Bank Receipt Voucher',
            'code' => 'BRV',
        ]);
        EntryTypes::create([
            'name' => 'Bank Payment Voucher',
            'code' => 'BPV',
        ]);

    }
}
