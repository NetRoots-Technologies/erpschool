<?php

namespace App\Models\Exam;

use App\Models\User;
use App\Models\Exam\Skills;
use App\Models\Admin\Branch;
use App\Models\Admin\Course;
use App\Models\Academic\AcademicClass;
use App\Models\Student\AcademicSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkillType extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'session_id',
        'branch_id',
        'class_id',
        'subject_id',
        'group_id',
        'skill_name',
        'skill_id',
        'user_id'
    ];

    protected $table = 'skill_types';

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function AcademicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }
    public function subject()
    {
        return $this->belongsTo(Course::class, 'subject_id');
    }

    public function group()
    {
        return $this->belongsTo(SkillGroup::class, 'group_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function skill()
    {
        return $this->belongsTo(Skills::class, 'skill_id', 'id');
    }



}
