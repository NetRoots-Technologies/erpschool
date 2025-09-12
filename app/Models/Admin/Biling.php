<?php

namespace App\Models\Admin;

use App\Models\Academic\AcademicClass;
use App\Models\Academic\Section;
use App\Models\Student\AcademicSession;
use App\Models\Student\Students;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biling extends Model
{
    use HasFactory;

    protected $table = 'bills';

    protected $fillable = [
        'class_id',
        'year_month',
        'fees',
        'student_id',
        'fee_factor',
        'bill_date',
        'due_date',
        'valid_date',
        'session_id',
        'charge_from',
        'charge_to',
        'ledger_date',
        'message',
        'company_id',
        'branch_id',
        'bill_id',
        'installment_allow',
        'voucher_number',
        'status',
        'paid_amount',
        'paid_date',
        'amount_type',
        'diff_amount',
        'previous_amount',
        'remarks'
    ];



    public function billingData()
    {
        return $this->hasMany(BilingData::class, 'bills_id');
    }


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function AcademicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
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
        return $this->belongsTo(Students::class, 'student_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            static::created(function ($model) {
                $model = static::find($model->id);

                $newBillNumber = '0224' . str_pad($model->id, 5, '0', STR_PAD_LEFT);

                $model->bill_number = $newBillNumber;

                $model->save();
            });
        });
    }

}
