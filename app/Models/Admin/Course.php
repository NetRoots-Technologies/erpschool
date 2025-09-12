<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Exam\SkillEvaluation;

class Course extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'courses';

    protected $fillable = [
        'name',
        'course_type_id',
        'subject_code',
        'company_id',
        'branch_id',
        'status',
        'class_id',
        'session_id',
        'active_session_id'
    ];

    public function coursetype()
    {
        return $this->belongsTo(CourseType::class, 'course_type_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function EvolutionKeySkills(){
        return $this->hasMany(SkillEvaluation::class, 'subject_id');
    }



}
