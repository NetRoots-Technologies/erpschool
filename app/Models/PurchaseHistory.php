<?php
// App\Models\PurchaseHistory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseHistory extends Model
{
    protected $fillable = [
        'customer_name','voucher_id','card_number','purchase_date',
        'total_sum','total_price','item_lists','transaction_id',
        'payment_method','status','discount_applied','created_by','notes'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'item_lists'    => 'array',   
        'total_sum'     => 'decimal:2',
        'total_price'   => 'decimal:2',
        'discount_applied' => 'decimal:2',
    ];
}
