<?php

namespace App\Models\HR;

use App\Models\Admin\Branch;
use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'hr_attendances';

    protected $fillable = ['branch_id', 'employee_id', 'attendance_date', 'status', 'timeIn', 'timeOut', 'remarks', 'machine_status'];



    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');

    }
    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }


}
