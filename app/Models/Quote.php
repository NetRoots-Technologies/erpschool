<?php

namespace App\Models;

use App\Models\Admin\Branches;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quote extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['id', 'supplier_id', 'branch_id', 'quote_date', 'due_date', 'comments', 'is_approved', 'approved_date', 'approved_by'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }

    public function quoteItems()
    {
        return $this->hasMany(QuoteItem::class, 'quote_id');
    }
    public function items()
    {
        return $this->belongsToMany(Item::class, 'quote_items', 'quote_id', 'item_id');
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

    public function scopeGeneral($query)
    {
        return $query->where('type', 'G');
    }

}
