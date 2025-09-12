<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentComissionPlan extends Model
{
    use HasFactory;

    public function agent_type()
    {

        return $this->belongsTo(AgentType::class, 'agent_type_id');
    }
}
