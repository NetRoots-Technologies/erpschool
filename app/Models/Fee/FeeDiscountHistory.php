<?php

namespace App\Models\Fee;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FeeDiscountHistory extends Model
{
    protected $fillable = [
        'fee_discount_id',
        'updated_by',
        'old_data',
        'new_data',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

        // for history
       public function histories()
    {
        return $this->hasMany(FeeDiscount::class , 'id', 'fee_discount_id');
    }

      public function updateUser()
    {
        return $this->belongsTo(User::class , 'updated_by');
    }


}