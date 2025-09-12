<?php

namespace App\Models\Exam;

use App\Models\Academic\AcademicClass;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Admin\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSubject extends Model
{
    use HasFactory;

    protected $table = 'class_subjects';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'company_id',
        'session_id',
        'branch_id',
        'class_id',
        'subject_id',
        'compulsory',
        'acd',
        'acd_sort',
        'skill',
        'skill_sort',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function AcademicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function Subject()
    {
        return $this->belongsTo(Course::class, 'subject_id');
    }

}
