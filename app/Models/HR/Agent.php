<?php

namespace App\Models\HR;

use App\Models\Student\Students;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Agent extends Model
{
    use HasFactory, SoftDeletes, HasFactory;

    public function agent_type()
    {

        return $this->belongsTo(AgentType::class, 'agent_type_id');
    }

}
