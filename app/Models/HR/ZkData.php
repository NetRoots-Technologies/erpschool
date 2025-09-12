<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZkData extends Model
{
    use HasFactory;

    protected $table = 'zkdata';

    protected $fillable = ['uid', 'userid', 'name', 'role', 'password', 'cardno'];
}
