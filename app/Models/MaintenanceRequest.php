<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RequestApproval;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id',
        'unit_id',
        'issue_type',
        'maintainer_id',
        'status',
        'amount',
        'issue_attachment',
        'invoice',
        'notes',
        'user_id',
        'request_date',
        'fixed_date',
    ];

    public static $status = [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'reject' => 'Rejected',
        'cancelled' => 'Cancelled',
    ];

    public static $approval_status = [
        '' => '',
        0 => 'Pending',
        1 => 'Approved',
        2 => 'Rejected',
    ];

    public function buildings()
    {
        return $this->belongsTo(Building::class, 'building_id', 'id');
    }

    public function units()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function types()
    {
        return $this->belongsTo('App\Models\Type', 'issue_type', 'id');
    }

    public function maintainers()
    {
        return $this->belongsTo('App\Models\User', 'maintainer_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function manager_approvals()
    {
        return $this->hasOne(RequestApproval::class, 'request_id')->where('approval_level', 1);
    }

    public function owner_approvals()
    {
        return $this->hasOne(RequestApproval::class, 'request_id', 'id')->where('approval_level', 2);
    }

    public function approvals()
    {
        return $this->hasOne(RequestApproval::class, 'request_id', 'id');
    }
}
