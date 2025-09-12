<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VendorCategory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $level1=\App\Models\inventory\VendorCategory::create([
            'name' => 'VENDOR ACCOUNTS',
            'code' => '850',
            'level' => 1,
            'parent_id' => null,
        ]);
    }
}
