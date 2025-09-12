<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeEducation extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'hrm_employees_education';

    protected $fillable = [
        'hrm_employee_id',
        'institution',
        'year',
        'certification',
        'cgpa',
        'specialization',
        'education_images',
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'hrm_employee_id');
    }

}
