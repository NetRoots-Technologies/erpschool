<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
            ['name' => 'Assets', 'number' => '1', 'code' => 'ASSETS', 'level' => 1, 'parent_id' => 0, 'account_type_id' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Liabilities', 'number' => '2', 'code' => 'LIABILITIES', 'level' => 1, 'parent_id' => 0, 'account_type_id' => 2, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Income', 'number' => '3', 'code' => 'INCOME', 'level' => 1, 'parent_id' => 0, 'account_type_id' => 3, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Expenses', 'number' => '4', 'code' => 'EXPENSES', 'level' => 1, 'parent_id' => 0, 'account_type_id' => 4, 'status' => 1, 'created_at' => now(), 'updated_at' => now()]
        ];

        // Insert into the database
        DB::table('groups')->insert($groups);
    }
}
