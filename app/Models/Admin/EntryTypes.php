<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntryTypes extends Model
{
    use SoftDeletes;
    //
    protected $fillable = ['name', 'status', 'created_by', 'updated_by' . 'deleted_by'];

    protected $table = 'entry_types';

    static public function pluckActiveOnly()
    {
        return self::where(['status' => 1])->OrderBy('name', 'asc')->pluck('name', 'id');
    }
}
