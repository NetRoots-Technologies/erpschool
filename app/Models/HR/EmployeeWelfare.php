<?php

namespace App\Models\HR;

use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeWelfare extends Model
{
    use HasFactory;
    protected $table = 'employee_welfare';

    protected $fillable = ['employee_id', 'welfare_amount', 'month', 'year'];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}
