<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountCurrency extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'symbol',
        'exchange_rate',
        'is_default',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'exchange_rate' => 'decimal:6',
    ];

    // Get default currency
    public static function getDefault()
    {
        return self::where('is_default', true)->where('is_active', true)->first();
    }

    // Convert amount to base currency
    public function convertToBase($amount)
    {
        return $amount * $this->exchange_rate;
    }

    // Convert amount from base currency
    public function convertFromBase($amount)
    {
        return $amount / $this->exchange_rate;
    }
}
