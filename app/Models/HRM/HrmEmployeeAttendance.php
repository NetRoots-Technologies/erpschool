<?php

namespace App\Models\HRM;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class HrmEmployeeAttendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'is_machine',
        'id',
        'user_id',
        'date_time',
        'checkin_time',
        'checkout_time',
        'type',
        'status',
        'manual_attendance'
    ];

    protected $table = 'hrm_employee_attendances';

    public function user_name()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



}
