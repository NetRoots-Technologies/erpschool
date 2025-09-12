<?php

namespace App\Models;

use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeBenefit extends Model
{
    use HasFactory;

    protected $table = 'employee_benefits';
    protected $appends = ['month_name'];

    protected $fillable = [
        'employee_id', 'company_amount', 'employee_amount', 'type', 'year', 'month'
    ];
    
    public function employee(){
        return $this->belongsTo(Employees::class, 'employee_id');
    }

    public function scopeEOBI($query){
        return $query->where('type', 'EOBI');
    }

    public function scopePF($query){
        return $query->where('type', 'PF');
    }

    public function scopeSS($query){
        return $query->where('type', 'SS');
    }


    //add sttribute getMonthName()
    public function getMonthNameAttribute()
    {
        return date('F', strtotime($this->month));
    }
}
