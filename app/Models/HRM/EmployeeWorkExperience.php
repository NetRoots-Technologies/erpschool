<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeWorkExperience extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hrm_employees_work_experince';

    protected $fillable = [
        'hrm_employee_id',
        's_no',
        'designation',
        'duration',
        'from',
        'name_of_institution',
        'till',
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'hrm_employee_id');
    }
}
