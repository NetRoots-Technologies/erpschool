<?php

namespace App\Models\Exam;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicEvaluationKey extends Model
{
    use HasFactory;


    protected $table = 'academic_evaluations_key';

    protected $fillable = ['abbr', 'key', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
