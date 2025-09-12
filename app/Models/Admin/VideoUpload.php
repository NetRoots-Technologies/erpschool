<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoUpload extends Model
{
    use HasFactory;

    public function get_course()
    {

        return $this->belongsTo(Course::class, 'course_id');
    }

    public function session()
    {

        return $this->belongsTo(Session::class, 'session_id');
    }

    public function video_heading()
    {

        return $this->belongsTo(VideoCategory::class, 'video_categories_id');
    }
}
