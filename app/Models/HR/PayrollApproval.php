<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollApproval extends Model
{
    use HasFactory;
    protected $table = 'payroll_approval';

    protected $fillable = ['hrm_employee_id	', 'branch_id', 'generated_month', 'generated_month_year', 'department_id', 'approved', 'bank_account_ledger'];

    public function payroll()
    {
        return $this->hasMany(Payroll::class, 'payroll_approval_id');
    }



}
