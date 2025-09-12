<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student\Students;
class MarkEntry extends Model
{
    use HasFactory;

     protected $table='marks_entries';
     protected $fillable = [
        'mark_input_id',
        'student_id',
        'max_marks',
        'allocated_marks',
    ];

    public function student(){
        return $this->belongsTo(Students::class, 'student_id');
    }
}
