<?php

namespace Database\Factories\HR;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\HR\Agent;

class AgentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Agent::class;


    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => 'Lahore',
            'parent_id' => '2',
            'mobile' => '12345678',
            'agent_type_id' => '1',
            'status' => '1',
        ];
    }
}
