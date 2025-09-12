<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentDataBank extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'student_databank';

    protected $fillable = [
        'reference_no',
        'first_name',
        'last_name',
        'student_age',
        'student_email',
        'gender',
        'student_phone',
        'study_perviously',
        'admission_for',
        'pick_address',
        'father_name',
        'father_cnic',
        'mother_name',
        'mother_cnic',
        'b_form_no',
        'reason_for_leaving',
        'present_address',
        'landline_number',
        'previous_school',
        'reason_of_switch'
    ];



    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function old_courses()
    {
        return $this->hasOne(StudentDataBankCourse::class, 'student_data_bank_id');
    }
}
