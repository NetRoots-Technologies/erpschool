<?php

namespace App\Models\Exam;

use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Exam\TestType;
use App\Models\Academic\AcademicClass;
use App\Models\Exam\Component;
use App\Models\Exam\ClassSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Course;

class ExamSchedule extends Model
{
    use HasFactory;

    protected $table = 'exams_schedule';

    protected $fillable = [
        'company_id',
        'branch_id',
        'exam_term_id',
        'test_type_id',
        'class_id',
        // 'subject_id',
        'course_id',
        'component_id',
        'marks',
        'grade',
        'pass',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function testType()
    {
        return $this->belongsTo(ExamDetail::class,'test_type_id');
    }

    public function examTerm()
    {
        return $this->belongsTo(ExamTerm::class);
    }

    public function class()
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function subject()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}
