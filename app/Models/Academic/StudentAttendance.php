<?php

namespace App\Models\Academic;

use App\Models\Admin\Branch;
use App\Models\Student\Students;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;

    protected $table = 'students_attendance';

    protected $fillable = ['branch_id', 'class_id', 'section_id', 'attendance_date'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function AcademicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }


    public function AttendanceData()
    {
        return $this->hasMany(StudentAttendanceData::class, 'student_attendance_id');
    }
}
