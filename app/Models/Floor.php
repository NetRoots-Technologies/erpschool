<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;
     protected $fillable = [
        'name', 'building_id', 'floor_type_id', 'area'
    ];

    public function floor_type()
    {
        return $this->belongsTo('App\Models\Type', 'floor_type_id', 'id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id', 'id');
    }

    // public function unit()
    // {
    //     return $this->hasMany('App\Models\PropertyUnit', 'floor_id', 'id');
    // }
}
