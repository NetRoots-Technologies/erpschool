<?php

// app/Models/ProgressReportRemark.php
namespace App\Models;
use App\Models\Student\Students;

use Illuminate\Database\Eloquent\Model;

class ProgressReportRemark extends Model
{
    protected $fillable = [
        'student_id',
        'remarks',
        'created_by',
    ];

    public function student()
    {
        return $this->belongsTo(Students::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

