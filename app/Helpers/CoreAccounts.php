<?php

namespace App\Helpers;

class CoreAccounts
{
    /**
     * Calculate balance with debit/credit
     */
    public static function calculate_withdc($amount, $dc, $new_amount, $new_dc)
    {
        if ($dc == 'd') {
            $balance = $amount + ($new_dc == 'd' ? $new_amount : -$new_amount);
        } else {
            $balance = $amount + ($new_dc == 'c' ? $new_amount : -$new_amount);
        }
        
        return [
            'amount' => abs($balance),
            'dc' => $balance >= 0 ? $dc : ($dc == 'd' ? 'c' : 'd')
        ];
    }

    /**
     * Format currency
     */
    public static function toCurrency($type, $amount)
    {
        return number_format($amount, 2);
    }
}
