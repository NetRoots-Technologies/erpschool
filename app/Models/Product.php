<?php

namespace App\Models;

use App\Models\Admin\Branch;
use App\Models\Admin\Branches;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_name',
        'cost_amount',
        'sale_price',
        'item_id',
        'quantity',
        'measuring_unit',
        'type',
    ];

    public function ProductItems()
    {
        return $this->hasMany(ProductItem::class);
    }

    public function scopeFood($query)
    {
        return $query->where('type', 'F');
    }

    public function scopeStationary($query)
    {
        return $query->where('type', 'S');
    }

    public function scopeUniform($query)
    {
        return $query->where('type', 'U');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

}
