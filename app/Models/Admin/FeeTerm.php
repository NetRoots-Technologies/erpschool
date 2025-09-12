<?php

namespace App\Models\Admin;

use App\Models\Academic\AcademicClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeTerm extends Model
{
    use HasFactory;
    protected $table = 'fee_terms';

    protected $fillable = ['branch_id', 'session_id', 'company_id', 'class_id', 'term', 'voucher_date', 'starting_date', 'ending_date', 'installment'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function feeTermVoucher()
    {
        return $this->hasMany(FeeTermVoucher::class, 'fee_terms_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function AcademicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }
}
