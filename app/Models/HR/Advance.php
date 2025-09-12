<?php

namespace App\Models\HR;

use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    use HasFactory;
    protected $table = 'advances';

    protected $fillable = ['employee_id', 'name', 'amount', 'duration', 'effective_from', 'installmentAmount', 'amount_to_pay', 'image', 'remaining_amount'];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}
