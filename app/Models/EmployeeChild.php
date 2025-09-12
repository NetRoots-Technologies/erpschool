<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student\Students;
class EmployeeChild extends Model
{
    use HasFactory;

    protected $table = 'hrm_employees_children';

    protected $fillable = [
        'employee_id',
        'student_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function student()
    {
        return $this->belongsTo(Students::class,'student_id');
    }
}
