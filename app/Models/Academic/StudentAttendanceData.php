<?php

namespace App\Models\Academic;

use App\Models\Student\Students;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendanceData extends Model
{
    use HasFactory;

    protected $table = 'student_attendance_data';

    protected $fillable = ['student_id', 'attendance', 'student_attendance_id'];

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

}
