<?php

namespace Database\Seeders;

use App\Models\HR\AgentType;
use App\Models\HRM\EmployeeTypes;
use Illuminate\Database\Seeder;

class AgentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AgentType::create([
            'name' => 'Executive Agent',
        ]);

        AgentType::create([
            'name' => 'Manager Agent',
        ]);


    }
}
