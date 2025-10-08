<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CostCenter extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'account_cost_centers';

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'parent_id',
        'is_active',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(CostCenter::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(CostCenter::class, 'parent_id');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Admin\Branches::class, 'branch_id');
    }

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class, 'cost_center_id');
    }

    public function journalLines()
    {
        return $this->hasMany(JournalEntryLine::class, 'cost_center_id');
    }
}
