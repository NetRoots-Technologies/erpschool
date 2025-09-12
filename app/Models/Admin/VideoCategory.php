<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'course_id',
        'session_id',
    ];

    public function course()
    {

        return $this->belongsTo(Course::class, 'course_id');
    }
    public function session()
    {

        return $this->belongsTo(Session::class, 'session_id');
    }
}
