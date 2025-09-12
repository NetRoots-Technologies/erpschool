<?php

namespace App\Models\Exam;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exam\SkillEvaluationKey;

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
        return $this->belongsTo(User::class, 'user_id');
    }

    public function skillEvaluationKeys()
    {
        return $this->belongsTo(SkillEvaluationKey::class, 'skill_evaluation_key_id', 'id');
    }


}
