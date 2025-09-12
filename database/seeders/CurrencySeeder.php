<?php

namespace Database\Seeders;

use App\Models\Admin\Currencies;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currencies::create([
            'name' => 'PKR',
            'code' => 'RS',
            'decimal' => '0',
            'symbols' => 'RS',
            'rate' => '1',
            'status' => '1',
            'is_default' => '1',
        ]);

    }
}
