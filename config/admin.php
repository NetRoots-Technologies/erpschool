<?php
/**
 * Created by PhpStorm.
 * User: Ashar
 * Date: 5/10/2022
 * Time: 05:47 PM
 */



/**
 * Created by PhpStorm.
 * User: zahra
 * Date: 31/01/2017
 * Time: 03:27 PM
 */
return [
    //  'gems_type' => [
//       ''=>'Select Option',
//       '1'=>'diamond',
//       '2'=>'stone',
//       '3'=>'beads',
//    ],
    'late_arrival_margin' => '20',
    'duty_hour' => '8',
    'break_minutes' => '60',
    'office_start' => '9:00:00',
    'office_end' => '18:00:00',
    'jewelry_type' => [
        '' => 'Select Option',
        'gold' => 'Gold',
        'other-metal' => 'Other Metal',
    ],
    'shift' => [
        '' => 'Select Option',
        'tagging' => 'Tagging',
        'add_in_tag' => 'Add in Tag',
        'shift_to_mix' => 'Shift to Mix',
        'shift_to_worker' => 'Shift to Worker',
    ],
    'routs_shifting' => [
        '' => 'Select Option',
        'tagging' => 'Shift to Tagging',
        'add_in_tag' => 'Shift to Add in Tag',
        'shift_to_mix' => 'Shift to Mix',
        'shift_to_melting' => 'Shift to Melting',
        // 'shift_to_refine'=>'Shift to Refine',
        'shift_to_kariger' => 'Shift to Vendor',
        'shift_to_laker' => 'Shift to Laker',
        'shift_to_lds' => 'Shift to Lds',
    ],
    'routs_shifting_waste_gold' => [
        '' => 'Select Option',
        'tagging' => 'Shift to Tagging',
        'add_in_tag' => 'Shift to Add in Tag',
        'shift_to_mix' => 'Shift to Mix',
        //      'shift_to_melting'=>'Shift to Melting',
        'shift_to_impure' => 'Shift to Impure',
        // 'shift_to_refine'=>'Shift to Refine',
        'shift_to_kariger' => 'Shift to Vendor',
        'shift_to_laker' => 'Shift to Laker',
        'shift_to_lds' => 'Shift to Lds',
    ],
    'routs_shifting_routing_account' => [
        '' => 'Select Option',
        'tagging' => 'Shift to Tagging',
        'add_in_tag' => 'Shift to Add in Tag',
        'shift_to_mix' => 'Shift to Mix',
        'shift_to_impure' => 'Shift to Impure',
        // 'shift_to_refine'=>'Shift to Refine',
        'shift_to_kariger' => 'Shift to Vendor',
        'shift_to_laker' => 'Shift to Laker',
        'shift_to_aftr_stone' => 'Shift to After Stone',
    ],
    'quality' => [
        '' => 'Select Option',
        'Classic' => 'Classic',
        'Fine' => 'Fine',
        'High' => 'High',
        'Exclusive' => 'Exclusive',
    ],
    'purity' => [
        '' => 'Select Option',
        '18' => '18',
        '21' => '21',
        '22' => '22',
        '24' => '24',
    ],
    'grn_detail' => [
        '' => 'Select Option',
        '1' => 'purchase order no',
        '2' => 'direct'
    ],
    'grn_type' => [
        '0' => 'Select Option',
        '1' => 'Jewelry',
        '2' => 'Pure gold',
        '3' => 'Watches',
        '4' => 'Accessories',
        '5' => 'LDS',
        '6' => 'Packaging',
    ],
    'routing_grn_type' => [
        '1' => 'Jewelry',
        '5' => 'LDS',
    ],
    'supplier_type' => [
        '' => 'Select Option',
        '1' => 'Jewelry supplier',
        '2' => 'Pure gold supplier',
        '3' => 'Watches supplier/brand/vendor',
        '4' => 'Accessories supplier/brand/vendor',
        '5' => 'LDS supplier',
        '6' => 'Packaging supplier/vendor',
        '7' => 'Agent'
    ],
    'surgical_supplier_type' => [
        '' => 'Select Option',
        '1' => 'Steel supplier',
    ],
    'category' => [
        '' => 'Select Option',
        '1' => 'Diamond',
        '2' => 'Stone',
        '3' => 'Bead',
    ],
    'class' => [
        '' => 'Select Option',
        '1' => 'A Class',
        '2' => 'B Class',
        '3' => 'C Class',
        '4' => 'D Class',
    ],
    'category_edit' => [
        '1' => 'Diamond',
        '2' => 'Stone',
        '3' => 'Bead',
    ],
    'rate_for_gold_in_karigar' => [
        '' => 'Select Option',
        'ratti_waste' => 'Ratti Waste',
        'percentage' => 'Percentage',
        'qty_in_weight' => 'Qty in weight',
    ],
    'rate_for_gold_in_supplier' => [
        '' => 'Select Option',
        'p_level' => 'P Level',
        'ratti_kat' => 'Ratti kat',
        'p_level_plus_making' => 'P Level + Making',
    ],
    'making_charge' => [
        '' => 'Select Option',
        'per_gram' => 'Per Gram',
        'per_tola' => 'Per Tola',
        'quantity' => 'Quantity',
        'manual' => 'Manual',
    ],
    'male_rate_karigar' => [
        '' => 'Select Option',
        'p_level' => 'P Level',
        'inner_male' => 'Inner Male',
        'outer_male' => 'Outer Male',
    ],

    'account_level' => array(
        '' => 'Select a Level',
        '1' => 'Level 1',
        '2' => 'Level 2',
        '3' => 'Level 3',
        '4' => 'Level 4',
        '5' => 'Level 5',
        '6' => 'Level 6',
        '7' => 'Detail Report'
    ),
    'entry_type' => array(
        '' => 'Select entry type',
        '1' => 'Journal Voucher',
        '2' => 'Cash Receipt Voucher',
        '3' => 'Cash Payment Voucher',
        '4' => 'Bank Receipt Voucher',
        '5' => 'Bank Payment Voucher'
    ),

    'uom' => array(
        'Caret' => 'Caret',
        'Gram' => 'Gram',
    ),

    'currency' => array(
        '' => 'Select Currency Type',
        '1' => 'PKR',
        '2' => 'US($)',
        '3' => 'Gold'
    ),
    'discount' => array(
        'Percentage' => '%',
        'Amount' => 'In Amt'

    ),

];
