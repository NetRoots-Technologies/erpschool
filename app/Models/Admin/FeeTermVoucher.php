<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeTermVoucher extends Model
{
    use HasFactory;

    protected $table = 'fee_terms_voucher';
    protected $fillable = ['voucher_date', 'starting_date', 'ending_date', 'fee_terms_id'];

    public function feeTermVoucher()
    {
        return $this->belongsTo(FeeTermVoucher::class, 'fee_terms_id');
    }
}
