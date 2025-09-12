<?php

namespace App\Models\Academic;

use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Admin\Course;
use App\Models\Student\AcademicSession;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassTimeTable extends Model
{
    use HasFactory;

    protected $table = 'class_timetables';

    protected $fillable = ['session_id', 'company_id', 'class_id', 'branch_id', 'session_id', 'section_id', 'course_id', 'days', 'time_table_id'];


    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }

    public function class()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }


    public function Timetable()
    {
        return $this->belongsTo(TimeTable::class, 'time_table_id');
    }


}
