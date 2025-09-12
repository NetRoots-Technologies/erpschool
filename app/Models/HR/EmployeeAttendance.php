<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EmployeeAttendance extends Model
{
    use SoftDeletes;

    use HasFactory;
    protected $table = 'employee_attendance';

    protected $fillable = ['employee_id', 'attendance_id', 'status', 'timeIn', 'timeOut', 'remarks'];

}
