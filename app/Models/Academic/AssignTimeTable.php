<?php

namespace App\Models\Academic;

use App\Models\Admin\Course;
use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignTimeTable extends Model
{
    use HasFactory;

    protected $table = 'assign_teachers';

    protected $fillable = ['class_id', 'section_id', 'course_id', 'timetable_id', 'teacher_id'];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'teacher_id');
    }
    public function class()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function classTimeTable()
    {
        return $this->belongsTo(ClassTimeTable::class, 'timetable_id');
    }
}
