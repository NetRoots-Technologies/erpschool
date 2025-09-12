<?php

namespace App\Models\HR;

use App\Models\Admin\Branch;
use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitFund extends Model
{
    use HasFactory;
    protected $table = 'provident_funds';
    protected $fillable = ['providentFund', 'employee_id', 'month', 'year'];

    public function employee()
    {
        return $this->hasOne(Employees::class, 'employee_id');
    }

}
