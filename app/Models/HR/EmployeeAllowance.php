<?php

namespace App\Models\HR;

use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAllowance extends Model
{
    use HasFactory;
    protected $table = 'employee_allowances';

    protected $fillable = ['employee_id', 'medicalAllowance'];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}
