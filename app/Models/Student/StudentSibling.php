<?php

namespace App\Models\Student;

use App\Models\Academic\AcademicClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSibling extends Model
{
    use HasFactory;
    protected $table = 'student_siblings';
    protected $fillable = ['studied', 'sibling_name', 'sibling_dob', 'sibling_gender', 'student_id', 'class_id'];

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id', 'id');
    }
    public function AcademicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }
}
