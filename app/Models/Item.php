<?php

namespace App\Models;

use App\Models\Inventry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'status', 'type', 'measuring_unit'];

    public function scopeActive()
    {
        return $this->where('status', 1);
    }
    public function scopeFood($query)
    {
        return $query->where('type', 'F');
    }

        public function scopeUniform($query)
    {
        return $query->where('type', 'U');
    }

    public function scopeStationary($query)
    {
        return $query->where('type', 'S');
    }

    public function quoteItem()
    {
        return $this->hasOne(QuoteItem::class, 'item_id', 'id');
    }

    public function supplierItems()
    {
        return $this->hasMany(SupplierItem::class, 'item_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($item) {
            if ($item->quoteItem) {
                $item->quoteItem->delete();
            }

            $item->supplierItems()->each(function ($supplierItem) {
                $supplierItem->delete();
            });
        });
    }


            public function inventory(){
                return $this->hasOne(Inventry::class  ,'item_id', 'id');
            }
}
