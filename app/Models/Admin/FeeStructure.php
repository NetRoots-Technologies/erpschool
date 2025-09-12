<?php

namespace App\Models\Admin;

use App\Models\Academic\AcademicClass;
use App\Models\Student\Students;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;

    protected $table = 'fee_structures';

    protected $fillable = ['branch_id', 'session_id', 'company_id', 'class_id', 'total_month_amount', 'total_annual_amount'];

    //    public function feeHead(){
//        return $this->belongsTo(FeeHead::class,'fee_head_id');
//    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function feeStructureValue()
    {
        return $this->hasMany(FeeStructureValue::class, 'fee_structure_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function AcademicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function student()
    {
        return $this->belongsTo(Students::class);
    }


}
