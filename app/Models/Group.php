<?php

namespace App\Models;

use App\Models\Accounts\AccountGroup;
use App\Models\Accounts\AccountLedger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'groups';
    protected $guarded=[];
    // protected $fillable = [
    //     'name',
    //     'number',
    //     'code',
    //     'level',
    //     'parent_id',
    //     'account_type_id',
    //     'status',
    //     'created_by',
    //     'updated_by',
    //     'deleted_by',
    //     'parent_type',
    //     'parent_type_id',
    // ];

    /**
     * Find associated Groups Count
     *
     * @param (int) $group_id
     * @return (int) $group_id
     */
    static function hasChildGroups($group_id)
    {
        return self::where('parent_id', '=', $group_id)->count();
    }

    /**
     * Find associated Ledgers Count
     *
     * @param (int) $group_id
     * @return (int) $group_id
     */


    // public function parent()
    // {
    //     return $this->belongsTo(Group::class, 'parent_id', 'id');
    // }

    public function children()
    {
        return $this->hasMany(Group::class, 'parent_id', 'id');
    }

    public function ledgers(){
        return $this->hasOne(ledgers::class,'group_id','id');
    }

    static function hasChildLedgers($group_id)
    {
        return Ledgers::where('group_id', '=', $group_id)->count();
    }

    public function parent()
    {
        // return $this->morphTo('parent', 'parent_type', 'account_type_id');
        return $this->belongsTo(Group::class,'account_type_id');
    }

    public function groupData()
    {
        return $this->belongsTo(Groups::class, 'parent_id', 'id')->OrderBy('code');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });

        static::deleting(function ($model) {
            if (auth()->check()) {
                $model->deleted_by = auth()->id();
                $model->save();
            }
        });
    }
}
