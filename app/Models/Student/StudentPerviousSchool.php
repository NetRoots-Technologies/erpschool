<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPerviousSchool extends Model
{
    use HasFactory;
    protected $table = 'student_pervious_schools';

    protected $fillable = ['school_name', 'school_origin', 'leaving_reason', 'local_school_name', 'local_school_address', 'student_id'];
}
