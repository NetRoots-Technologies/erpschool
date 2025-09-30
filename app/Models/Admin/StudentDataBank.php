<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Student\AcademicSession;

class StudentDataBank extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'student_databank';

    protected $fillable = [
        'reference_no',
        'student_name',
        'student_age',
        'student_email',
        'gender',
        'student_phone',
        'study_perviously',
        'admission_for',
        'father_name',
        'father_cnic',
        'mother_name',
        'mother_cnic',
        'reason_for_leaving',
        'present_address',
        'landline_number',
        'previous_school',
        'reason_of_switch',
        'academic_session_id',
        'status'
    ];



    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function old_courses()
    {
        return $this->hasOne(StudentDataBankCourse::class, 'student_data_bank_id');
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'academic_session_id');
    }
}
