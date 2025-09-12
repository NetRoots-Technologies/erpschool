<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;
    protected $table = 'hr_holidays';

    protected $fillable = ['name', 'holiday_date', 'holiday_date_to', 'is_recurring', 'branch_id', 'department_id', 'employee_id'];
}
