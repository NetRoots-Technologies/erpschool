<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentDataBankCourse extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'course_id',
        'student_data_bank_id',
    ];
    protected $table = 'student_data_bank_courses';

    public function course_name()
    {
        return $this->hasOne(Course::class, 'id');
    }



}
