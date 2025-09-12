<?php

namespace App\Models\HR;

use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalarySlip extends Model
{
    use HasFactory;

    protected $table = 'salary_slips';
    protected $casts = [
        'fund_values' => 'array'
    ];
    protected $fillable = ['total_late', 'medicalAllowance', 'total_present', 'total_absent', 'total_leave', 'fund_values', 'employee_id', 'salary_per_minute', 'total_working_hours', 'advance', 'loan', 'total_fund_amount', 'total_salary', 'net_salary', 'cash_in_hand', 'cash_in_bank', 'generated_month_year', 'generated_month', 'payroll_approval_id', 'committedTime'];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}
