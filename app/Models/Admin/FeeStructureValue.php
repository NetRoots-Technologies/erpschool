<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructureValue extends Model
{
    use HasFactory;

    protected $table = 'fee_structure_value';

    protected $fillable = ['fee_head_id', 'discount_percent', 'monthly_amount', 'fee_structure_id', 'discount_rupees', 'claim1', 'claim2', 'total_amount_after_discount'];

    public function feeHead()
    {
        return $this->belongsTo(FeeHead::class, 'fee_head_id');
    }

    public function feeStructureValue()
    {
        return $this->belongsTo(FeeStructureValue::class, 'fee_structure_id');
    }
}
