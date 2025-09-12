<?php

namespace App\Models\Exam;

use App\Models\Admin\Branch;
use App\Models\Student\AcademicSession;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamTerm extends Model
{
    use HasFactory;
    protected $table = 'exam_terms';

    protected $fillable = [
        'session_id',
        'branch_id',
        'term_id',
        'progress_heading',
        'start_date',
        'end_date',
        'issue_date',
        'term_desc',
        'total_month',
        'coordinator_1',
        'staff_id_1',
        'coordinator_2',
        'staff_id_2',
        'coordinator_3',
        'staff_id_3',
        'coordinator_4',
        'staff_id_4'
    ];

    public function AcademicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');

    }
}
