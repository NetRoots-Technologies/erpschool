<?php

namespace App\Models\Exam;

use App\Models\Admin\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectMarks extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
        // if your "courses" model is named differently, change it here
    }

    // Also belongs to exam detail
    public function examDetail()
    {
        return $this->belongsTo(ExamDetail::class, 'exam_detail_id');
    }
}
