<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'parent_id',
        'level',
        'is_active',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer',
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(AccountGroup::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AccountGroup::class, 'parent_id');
    }

    public function ledgers()
    {
        return $this->hasMany(AccountLedger::class, 'account_group_id');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Admin\Branches::class, 'branch_id');
    }

    // Helper methods
    public function getFullPathAttribute()
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' > ', $path);
    }

    public static function getTree($parentId = null)
    {
        return self::where('parent_id', $parentId)
            ->where('is_active', true)
            ->with('children')
            ->orderBy('code')
            ->get();
    }
}
