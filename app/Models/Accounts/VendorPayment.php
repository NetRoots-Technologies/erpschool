<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPayment extends Model
{
    use HasFactory;

    protected $table = 'vendor_payments';

    protected $fillable = [
        'voucher_no',
        'payment_date',
        'vendor_id',
        'invoice_id',
        'total_invoice_amount',
        'pending_amount',
        'payment_amount',
        'payment_mode',
        'account_id',
        'cheque_no',
        'cheque_date',
        'narration',
        'attachment',
        'prepared_by',
        'approved_by',
    ];

    // // Vendor Relation
    // public function vendor()
    // {
    //     return $this->belongsTo(Vendor::class, 'vendor_id');
    // }

    // // Invoice Relation
    // public function invoice()
    // {
    //     return $this->belongsTo(PurchaseInvoice::class, 'invoice_id');
    // }

    // // Bank / Cash Account Relation
    // public function account()
    // {
    //     return $this->belongsTo(Account::class, 'account_id');
    // }

    // // Prepared By User
    // public function preparedByUser()
    // {
    //     return $this->belongsTo(User::class, 'prepared_by');
    // }

    // // Approved By User
    // public function approvedByUser()
    // {
    //     return $this->belongsTo(User::class, 'approved_by');
    // }
}
