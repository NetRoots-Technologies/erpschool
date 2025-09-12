<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    use HasFactory;

    // protected $appends = ['item_name'];

    protected $with = [];

    protected $fillable = [
        'quote_id', 'item_id', 'quantity', 'unit_price', 'total_price'
    ];

    public function item(){
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function quote(){
        return $this->belongsTo(Quote::class, 'quote_id', 'id');
    }

    public function getItemNameAttribute(){
        return $this->item->name;
    }
}
