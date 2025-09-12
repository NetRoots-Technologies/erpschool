<?php

namespace App\Models\HR;

use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $table = 'payrolls';

    protected $casts = [
        'fund_values' => 'array'
    ];

    protected $fillable = ['total_late', 'total_present', 'total_absent', 'medicalAllowance', 'payroll_approval_id', 'employee_id', 'salary_per_minute', 'fund_values', 'total_working_hours', 'advance', 'loan', 'total_fund_amount', 'total_salary', 'net_salary', 'cash_in_hand', 'cash_in_bank'];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }

}
