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


    public function employee_name()
    {

        return $this->belongsTo(Employees::class, 'employee_id');
    }
}
