<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Admin\Branch;
use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Requisition extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'requester_id',
        'item_id',
        'branch_id',
        'requisition_to',
        'type',
        'quantity',
        'priority',
        'justification',
        'status',
        'is_approved',
        'approved_by',
        'comments',
        'approved_date',
        'requested_date',
        'fulfilled_date',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'requester_id', "id");
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', "id");
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function scopeFood($query)
    {
        return $query->where('type', 'F');
    }

    public function scopeStationary($query)
    {
        return $query->where('type', 'S');
    }
    
    public function scopeUniform($query)
    {
        return $query->where('type', 'U');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($item)
        {
            if ($item->item)
            {
                $item->item->delete();
            }
        });
    }

}
