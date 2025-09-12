<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Designation extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'designations';

    protected $fillable = ['name', 'department_id'];


}
