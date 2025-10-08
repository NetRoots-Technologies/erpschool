<?php

namespace App\Models\Exam;

use App\Models\Admin\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exam\SkillEvaluationKey;
use App\Models\Student\Students;
use App\Models\Exam\SkillGroup;
class SkillEvaluation extends Model
{
    use HasFactory;

    protected $table = 'skill_evaluations';

    protected $fillable = [
        'student_id',
        'subject_id',
        'skill_group_id',
        'skill_id',
        'skill_evaluation_key_id',
        'user_id',
        'logs'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
        public function student()
    {
        return $this->belongsTo(Students::class);
    }
         public function subject()
    {
        return $this->belongsTo(Course::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skills::class, 'skill_id', 'id');
    }
    public function groupskill()
    {
        return $this->belongsTo(SkillGroup::class, 'skill_group_id');
    }
    public function skillEvaluationKeys()
    {
        return $this->belongsTo(SkillEvaluationKey::class, 'skill_evaluation_key_id', 'id');
    }

     public function key()
    {
        return $this->belongsTo(SkillEvaluationKey::class, 'skill_evaluation_key_id', 'id');
    }




}
