<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeCompensatoryLeaves extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'past_leaves',
        'current_leaves',
        'employee_id'
    ];

    protected $table = 'employee_compensatory_leaves';
}
