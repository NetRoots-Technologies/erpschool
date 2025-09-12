<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentNewSaleRecovery extends Model
{
    use HasFactory;

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
}
