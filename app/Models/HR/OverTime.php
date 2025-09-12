<?php

namespace App\Models\HR;

use App\Models\Admin\Branch;
use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OverTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'employee_id',
        'total_time',
        'total',
        'start_date',
        'end_date',
        'action',
    ];

    protected $table = 'overtimes';

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }

}
