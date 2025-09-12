<?php

namespace App\Models\Exam;

use App\Models\Academic\AcademicClass;
use App\Models\Admin\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamDetail extends Model
{
    use HasFactory;

    protected $table = 'exam_details';

    protected $fillable = ['test_name', 'initial', 'test_type_id', 'status', 'exam_term_id', 'class_id', 'user_id'];

    public function testType()
    {
        return $this->belongsTo(TestType::class, 'test_type_id');
    }
    public function examType()
    {
        return $this->belongsTo(ExamTerm::class, 'exam_term_id');
    }
    public function subjectMarks()
    {
        return $this->hasMany(SubjectMarks::class, 'exam_detail_id');
    }
    public function class()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subjects()
    {
        return $this->hasManyThrough(
            Course::class,        // 👈 change this from Subject to Course
            SubjectMarks::class,
            'exam_detail_id',     // Foreign key on subject_marks
            'id',                 // Foreign key on courses
            'id',                 // Local key on exam_details
            'course_id'           // Local key on subject_marks
        );
    }

}
