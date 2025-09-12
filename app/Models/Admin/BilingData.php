<?php

namespace App\Models\Admin;

use App\Models\Academic\AcademicClass;
use App\Models\Account\Ledger;
use App\Models\Student\Students;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BilingData extends Model
{
    use HasFactory;

    protected $table = 'bills_data';


    protected $fillable = ['fee_head_id', 'ledger_id', 'bills_id', 'bills_amount', 'bills_data_id', 'installment_allow'];

    public function billingData()
    {
        return $this->belongsTo(BilingData::class, 'bills_id');
    }

    public function feeHead()
    {
        return $this->belongsTo(FeeHead::class, 'fee_head_id');

    }
    public function AcademicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }
    public function ledger()
    {
        return $this->belongsTo(Ledger::class, 'ledger_id');
    }


}
