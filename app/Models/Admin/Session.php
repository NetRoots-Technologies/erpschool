<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'sessions';

    protected $fillable = [
        'course_id',
        'title',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'status'
    ];
}
