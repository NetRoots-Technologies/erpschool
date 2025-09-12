<?php

namespace App\Models\Fee;

use App\Models\Admin\Course;
use App\Models\Admin\Session;
use App\Models\Student\Students;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaidStudentFee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'paid_student_fee';

    protected $fillable = [
        'id',
        'student_fee_id',
        'student_id',
        'installement_no',
        'installement_amount',
        'start_date',
        'due_date',
        'paid_date',
        'paid_status',
        'source',
    ];

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function student_fee()
    {
        return $this->belongsTo(StudentFee::class, 'student_fee_id');
    }

    public function sessions()
    {
        return $this->belongsTo(Session::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'student_id');
    }
}
