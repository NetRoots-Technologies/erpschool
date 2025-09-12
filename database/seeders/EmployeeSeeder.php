<?php

namespace Database\Seeders;

use App\Models\HR\Agent;
use App\Models\HRM\Employees;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Employees::create([
            'name' => 'Umar',
            'email' => 'umar1@gmail.com',
            'mobile_no' => '03214455677',
        ]);
    }
}
