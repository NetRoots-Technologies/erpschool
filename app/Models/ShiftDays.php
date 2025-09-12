<?php

namespace App\Models;

use App\Models\HR\WorkShift;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftDays extends Model
{
    use HasFactory;

    protected $table = 'shift_days';

    protected $fillable = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'status', 'work_shift_id'];

    public function workDay()
    {
        return $this->belongsTo(WorkShift::class, 'work_shift_id');
    }
}
