<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Location;

class Companies extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'ntn', 'gst', 'vat', 'phone', 'fax', 'logo', 'address', 'status', 'created_by', 'updated_by', 'deleted_by'];

    protected $table = 'companies';

    static public function pluckActiveOnly()
    {
        return self::where(['status' => 1])->OrderBy('name', 'asc')->pluck('name', 'id');
    }

    public function branch()
    {
        return $this->hasMany(Branches::class, 'company_id');
    }

}
