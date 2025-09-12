<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculateComission extends Model
{
    use HasFactory;

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function agent_type()
    {
        return $this->belongsTo(AgentType::class, 'agent_type_id');
    }

    public function comission_type()
    {
        return $this->belongsTo(AgentComissionPlan::class, 'id');
    }


}
