<?php

namespace App\Helpers;

class TaxHelper
{
    public static function calculateTax($employeeSalary, $taxSlabs)
    {

        $tax = 0;

        //dd($employeeSalary);
        $employeeSalary = floatval($employeeSalary) * 12;

        foreach ($taxSlabs as $slab) {
            //            $start_salry = 0;
//            $end_salry = 0;
//            $slab['start_range'];
//            if ($slab['start_range'] != 0) {
//                $start_salry = $slab['start_range'] / 12;
//
//            } if ($slab['end_range'] != 0) {
//                $end_salry = $slab['end_range'] / 12;
//
//            }


            if ($employeeSalary > $slab['start_range'] && $employeeSalary <= $slab['end_range']) {

                if ($slab['fix_amount'] !== null) {
                    $tax = $slab['fix_amount'] + ($employeeSalary - $slab['start_range']) * ($slab['tax_percent'] / 100);
                    $tax = round($tax / 12);

                } else {
                    $tax = ($employeeSalary - $slab['start_range']) * ($slab['tax_percent'] / 100);
                    $tax = round($tax / 12);
                }
                break;
            }
        }
        return $tax;
    }
}
