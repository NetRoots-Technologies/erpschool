<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $guarded = [];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($item) {
            if ($item->item) {
                $item->item->delete();
            }

        });
    }
}
