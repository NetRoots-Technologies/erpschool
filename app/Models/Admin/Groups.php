<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Svg\Tag\Group;

class Groups extends Model
{
    // use SoftDeletes;

    protected $fillable = ['number', 'name', 'code', 'level', 'account_type_id', 'parent_id', 'parent_type', 'created_by', 'updated_by', 'deleted_by', 'parent_type_id', 'parent_type'];

    protected $table = 'groups';

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
    static function hasChildLedgers($group_id)
    {
        return Ledgers::where('group_id', '=', $group_id)->count();
    }
    public function groupData()
    {
        return $this->belongsTo('App\Models\Admin\Groups', 'parent_id', 'id')->OrderBy('code');
    }
}
