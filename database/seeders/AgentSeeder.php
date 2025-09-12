<?php

namespace Database\Seeders;

use App\Models\HR\Agent;
use Illuminate\Database\Seeder;
use Database\Factories\HR\AgentFactory;
class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Agent::create([
            'name' => 'Executive Agent',
            'email' => 'executive_agent@gmail.com',
            'address' => 'Lahore',
            'mobile' => '12345678',
            'agent_type_id' => '1',
            'parent_id' => '2',
            'status' => '1',
        ]);



        Agent::create([
            'name' => 'Manager Agent',
            'email' => 'manager_agent@gmail.com',
            'address' => 'Lahore',
            'mobile' => '12345678',
            'agent_type_id' => '2',
            'status' => '1',
        ]);

        Agent::factory()->count(55)->create();



    }
}
