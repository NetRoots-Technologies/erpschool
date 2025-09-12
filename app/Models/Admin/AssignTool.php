<?php

namespace App\Models\Admin;

use App\Models\Student\Students;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignTool extends Model
{
    use HasFactory;

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class, 'tools');
    }
}
