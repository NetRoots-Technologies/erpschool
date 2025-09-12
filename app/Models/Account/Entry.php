<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'entries';

    protected $fillable = [
        'number',
        'voucher_date',
        'dr_total',
        'cr_total',
        'narration',
        'entry_type_id',
        'property_id'
    ];

    public static $types = [
        1 => 'Journal Voucher',
        2 => 'Cash Receive Voucher',
        3 => 'Cash Payment Voucher',
        4 => 'Bank Receipt Voucher',
        5 => 'Bank Payment Voucher'
    ];
}
