<?php

namespace App\Models;

use App\Models\Admin\Branches;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'branch_id',
        'quote_date',
        'order_date',
        'delivery_date',
        'total_amount',
        'delivery_status',
        'type',
        'id',
    ];

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'purchase_order_items', 'purchase_order_id', 'item_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }

    public function scopeFood($query)
    {
        return $query->where('type', 'F');
    }

    public function scopeStationary($query)
    {
        return $query->where('type', 'S');
    }
}
