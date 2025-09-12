<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EntryItems extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'entry_type_id',
        'entry_id',
        'ledger_id',
        'parent_id',
        'parent_type',
        'voucher_date',
        'amount',
        'dc',
        'narration',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $table = 'entry_items';

    public function currenciesA()
    {
        return $this->belongsTo("App\Models\Admin\Currencies", 'currence_type')->select(['id', 'code']);
    }

    public function ledger()
    {
        return $this->belongsTo(Ledgers::class, 'ledger_id');
    }
}
