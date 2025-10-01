<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Student\Students;
use App\Models\Admin\Course;
use App\Models\User;
class EffortLevel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'efforts_levels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'user_id',
        'subject_id',
        'effort',
        'level',
    ];

    /**
     * Get the student that owns the effort level.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    /**
     * Get the user that owns the effort level.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the course that owns the effort level.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'subject_id');
    }
}