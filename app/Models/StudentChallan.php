<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\StudentDatabank;


class StudentChallan extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_databank_id',
        'challan_no',
        'reference_no',
        'amount',
        'status',
        'issue_date',
        'due_date',
        'paid_date',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date'   => 'date',
        'paid_date'  => 'date',
    ];

    // 🔗 Relationship
    public function student()
    {
        return $this->belongsTo(StudentDatabank::class, 'student_databank_id');
    }
}
