<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyTreeTable extends Model
{
    use HasFactory;

    protected $table = 'family_tree';

    protected $fillable = [
        'cnic_number',
        'no_of_children',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
