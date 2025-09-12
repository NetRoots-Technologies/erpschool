<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class LedgerCurrencies extends Model
{
    protected $fillable = ['currency_id', 'balance_type', 'amount', 'ledger_id'];

    protected $table = 'ledger_currencies';

    public function ledgers()
    {
        return $this->belongsTo('App\Models\Admin\Ledgers', 'ledger_id');
    }
}
