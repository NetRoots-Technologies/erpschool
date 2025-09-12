<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\inventory\InventoryCategory;
class InventoryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $level1=\App\Models\inventory\InventoryCategory::create([
            'name' => 'INVENTORY ACCOUNTS',
            'code' => '1050',
            'level' => 1,
            'parent_id' => null,
        ]);
    }
}
