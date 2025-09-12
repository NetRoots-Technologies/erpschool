<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LedgerCurrency extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'ledger_currencies';
}
