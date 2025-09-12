<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeFamily extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hrm_employees_family_information';

    protected $fillable = [
        'hrm_employee_id',
        'sr_no',
        'name',
        'relation',
        'gender',
        'dob',
        'cnic',
        'workstation',
    ];
    public function employee()
    {
        return $this->belongsTo(Employees::class, 'hrm_employee_id');
    }
}
