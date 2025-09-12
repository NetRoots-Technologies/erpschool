<?php

namespace App\Models\Exam;

use App\Models\Academic\AcademicClass;
use App\Models\Student\AcademicSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GradingPolicies extends Model
{
    use HasFactory;

    protected $table = "grading_policies";

    protected $fillable = [
        'acadmeic_session_id',
        'class_id',
        'grade',
        'marks_range',
        'marks_from',
        'marks_to',
        'description',
        'status',
        'logs'
    ];

    public function academic_session()
    {
        return $this->belongsTo(AcademicSession::class, 'acadmeic_session_id', 'id');
    }

    public function academic_class()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id', 'id');
    }
}
