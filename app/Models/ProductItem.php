<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function item(){
        return $this->belongsTo(Item::class, 'item_id');
    }
    
    public function inventoryItems(){
        return $this->belongsTo(Inventry::class, 'inventory_id');
    }
}
