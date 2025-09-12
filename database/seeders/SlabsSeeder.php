<?php

namespace Database\Seeders;

use App\Models\Admin\Currencies;
use App\Models\HR\AgentComissionPlan;
use Illuminate\Database\Seeder;

class SlabsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Sale Executives Slab (Comission)
        AgentComissionPlan::create([
            'slab_name' => '0 – 9',
            'agent_type_id' => '1',
            'min' => '0',
            'max' => '9',
            'comission' => '0%',
            'slab_type' => '1',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '10 – 19',
            'agent_type_id' => '1',
            'min' => '10',
            'max' => '19',
            'comission' => '3%',
            'slab_type' => '1',
        ]);

        AgentComissionPlan::create([
            'slab_name' => '20 – 29',
            'agent_type_id' => '1',
            'min' => '20',
            'max' => '29',
            'comission' => '5%',
            'slab_type' => '1',
        ]);

        AgentComissionPlan::create([
            'slab_name' => '30-39',
            'agent_type_id' => '1',
            'min' => '30',
            'max' => '39',
            'comission' => '7%',
            'slab_type' => '1',
        ]);

        AgentComissionPlan::create([
            'slab_name' => '40-49',
            'agent_type_id' => '1',
            'min' => '40',
            'max' => '49',
            'comission' => '10%',
            'slab_type' => '1',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '50-75',
            'agent_type_id' => '1',
            'min' => '50',
            'max' => '75',
            'comission' => '12%',
            'slab_type' => '1',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '76-99',
            'agent_type_id' => '1',
            'min' => '76',
            'max' => '99',
            'comission' => '13%',
            'slab_type' => '1',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '100+',
            'agent_type_id' => '1',
            'min' => '100',
            'max' => '100+',
            'comission' => '15%',
            'slab_type' => '1',
        ]);
        //Sale Executives Slab (Recovery)
        AgentComissionPlan::create([
            'slab_name' => '0-49%',
            'agent_type_id' => '1',
            'min' => '0',
            'max' => '49',
            'comission' => '0%',
            'slab_type' => '2',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '50-65%',
            'agent_type_id' => '1',
            'min' => '50',
            'max' => '65',
            'comission' => '2%',
            'slab_type' => '2',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '66-74%',
            'agent_type_id' => '1',
            'min' => '66',
            'max' => '74',
            'comission' => '3%',
            'slab_type' => '2',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '75-89%',
            'agent_type_id' => '1',
            'min' => '75',
            'max' => '89',
            'comission' => '5%',
            'slab_type' => '2',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '90-99 %',
            'agent_type_id' => '1',
            'min' => '90',
            'max' => '99',
            'comission' => '7%',
            'slab_type' => '2',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '100%',
            'agent_type_id' => '1',
            'min' => '100',
            'max' => '100',
            'comission' => '10%',
            'slab_type' => '2',
        ]);


        //Sale Manager Slab (Comission)
        AgentComissionPlan::create([
            'slab_name' => '0 – 9 No. of Agents',
            'agent_type_id' => '2',
            'min' => '0',
            'max' => '9',
            'comission' => '0%',
            'slab_type' => '1',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '10 – 19 No. of Agents',
            'agent_type_id' => '2',
            'min' => '10',
            'max' => '19',
            'comission' => '1%',
            'slab_type' => '1',
        ]);

        AgentComissionPlan::create([
            'slab_name' => '20 – 29 No. of Agents',
            'agent_type_id' => '2',
            'min' => '20',
            'max' => '29',
            'comission' => '2%',
            'slab_type' => '1',
        ]);

        AgentComissionPlan::create([
            'slab_name' => '30-39 No. of Agents',
            'agent_type_id' => '2',
            'min' => '30',
            'max' => '39',
            'comission' => '3%',
            'slab_type' => '1',
        ]);

        AgentComissionPlan::create([
            'slab_name' => '40-49 No. of Agents',
            'agent_type_id' => '2',
            'min' => '40',
            'max' => '49',
            'comission' => '4%',
            'slab_type' => '1',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '50-75 No. of Agents',
            'agent_type_id' => '2',
            'min' => '50',
            'max' => '75',
            'comission' => '5%',
            'slab_type' => '1',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '76-99 No. of Agents',
            'agent_type_id' => '2',
            'min' => '76',
            'max' => '99',
            'comission' => '7%',
            'slab_type' => '1',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '100+ No. of Agents',
            'agent_type_id' => '2',
            'min' => '100',
            'max' => '100+',
            'comission' => '10%',
            'slab_type' => '1',
        ]);

        //Sale Manager Slab (Recovery)
        AgentComissionPlan::create([
            'slab_name' => '0-49%',
            'agent_type_id' => '2',
            'min' => '0',
            'max' => '49',
            'comission' => '0%',
            'slab_type' => '2',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '50-75%',
            'agent_type_id' => '2',
            'min' => '50',
            'max' => '75',
            'comission' => '1%',
            'slab_type' => '2',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '76-89%',
            'agent_type_id' => '2',
            'min' => '76',
            'max' => '89',
            'comission' => '2%',
            'slab_type' => '2',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '90-99 %',
            'agent_type_id' => '2',
            'min' => '90',
            'max' => '99',
            'comission' => '3%',
            'slab_type' => '2',
        ]);
        AgentComissionPlan::create([
            'slab_name' => '100%',
            'agent_type_id' => '2',
            'min' => '100',
            'max' => '100',
            'comission' => '5%',
            'slab_type' => '2',
        ]);



    }
}
