<?php

namespace App\Models\HR;

use App\Models\HRM\Employees;
use App\Models\ShiftDays;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'start_time', 'end_time'];

    protected $table = 'work_shifts';

    public function workdays()
    {
        return $this->hasOne(ShiftDays::class, 'work_shift_id', 'id');
    }
    public function employee()
    {
        return $this->hasMany(Employees::class, 'work_shift_id');

    }

}
