<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentFeeData extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'students_fee_data';


    protected $fillable = [
        'discount_rupees',
        'claim2',
        'discount_percent',
        'claim1',
        'total_amount_after_discount',
        'students_fee_id',
        'fee_head_id',
        'monthly_amount',

    ];

    public function feeHead()
    {
        return $this->belongsTo(FeeHead::class, 'fee_head_id');
    }
}
