<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BCategory extends Model
{
    protected $table = 'b_category';

    protected $fillable = [
        'title',
        'parent_id',
        'user_id',
        'description',
    ];

    public function parent()
{
    return $this->belongsTo(BCategory::class, 'parent_id');
}

}
