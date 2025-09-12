<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentEmergencyContact extends Model
{
    use HasFactory;

    protected $table = 'student_emergency_contacts';
    protected $fillable = ['student_id', 'name', 'relation', 'parent_responsibility', 'home_address', 'city', 'landline', 'cell_no', 'email_address', 'work_address', 'work_landline', 'work_cell_no', 'work_email'];
}
