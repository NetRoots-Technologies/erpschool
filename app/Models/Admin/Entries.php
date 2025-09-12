<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Entries extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'number',
        'voucher_date',
        'cheque_no',
        'cheque_date',
        'invoice_no',
        'grnID',
        'invoice_date',
        'cdr_no',
        'cdr_date',
        'bdr_no',
        'bdr_date',
        'bank_name',
        'bank_branch',
        'drawn_date',
        'entry_type_id',
        'employee_id',
        'branch_id',
        'department_id',
        'suppliers_id',
        'currence_type',
        'rate',
        'narration',
        'remarks',
        'dr_total',
        'cr_total',
        'other_dr_total',
        'other_cr_total',
        'other_currency_type',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $table = 'entries';

    public function currenciesA()
    {
        return $this->belongsTo("App\Models\Admin\Currencies", 'currence_type')->select(['id', 'code']);
    }
    public function currenciesB()
    {
        return $this->belongsTo("App\Models\Admin\Currencies", 'other_currency_type')->select(['id', 'code']);
    }

    public function entry_items()
    {
        return $this->belongsTo("App\Models\Admin\EntryItems", 'entry_id');
    }

}
