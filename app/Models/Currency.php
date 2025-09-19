<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Currency extends Model
{
    use HasFactory;

    protected $table = 'currencies';

    protected $fillable = [
        'name',
        'code',
        'decimal',
        'decimal_fixed_point',
        'symbols',
        'rate',
        'status',
        'is_default',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'status' => 'integer',
        'is_default' => 'integer',
    ];

    /**
     * Get the default currency
     */
    public static function getDefault()
    {
        return static::where('is_default', 1)->first();
    }

    /**
     * Format currency amount
     */
    public function formatAmount($amount)
    {
        $decimals = $this->decimal_fixed_point ?: 2;
        return number_format($amount, $decimals);
    }

    /**
     * Get currency symbol
     */
    public function getSymbol()
    {
        return $this->symbols ?: $this->code;
    }
}
