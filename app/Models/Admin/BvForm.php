<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BvForm extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'father_name',
        'email',
        'phone',
        'cnic',
        'facebook_link',
        'shift',
        'message'
    ];
    protected $table = 'bv_forms';
}
