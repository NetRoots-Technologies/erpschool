<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPictures extends Model
{
    use HasFactory;

    protected $table = 'student_pictures';
    protected $fillable = ['student_id', 'passport_photos', 'birth_certificate', 'school_leaving_certificate', 'guardian_document', 'picture_permission'];

    public function studentPictures()
    {
        return $this->belongsTo(StudentPictures::class, 'student_id');
    }
}
