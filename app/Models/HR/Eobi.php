<?php

namespace App\Models\HR;

use App\Models\Admin\Branch;
use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eobi extends Model
{
    use HasFactory;
    protected $table = 'eobis';

    protected $fillable = ['employee_percent', 'total', 'employee_id', 'company', 'branch_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }

}
