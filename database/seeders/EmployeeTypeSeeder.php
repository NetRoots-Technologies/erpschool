<?php

namespace Database\Seeders;

use App\Models\HRM\EmployeeTypes;
use Illuminate\Database\Seeder;

class EmployeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmployeeTypes::create([
            'name' => 'Teacher',
            'working_hours' => '176',
        ]);

        EmployeeTypes::create([
            'name' => 'Office Boy',
            'working_hours' => '176',
        ]);
    }
}
