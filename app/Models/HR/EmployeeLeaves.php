<?php

namespace App\Models\HR;

use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EmployeeLeaves extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
    'employee_id',   // ✔ correct
    'hr_quota_setting_id',
    'work_shift_id',
    'start_date',
    'end_date',
    'days',
    'duration',
    'start_time',
    'end_time',
    'responsible_employee',
    'status',
    'hr_approved',
    'team_lead_approved',
    'head_cord_approved',
    'hco_approved',
    'comments',
    'evidence',
    'leave_request_id',
];


    public function employee_name()
    {

        return $this->belongsTo(Employees::class, 'employee_id');
    }
}
