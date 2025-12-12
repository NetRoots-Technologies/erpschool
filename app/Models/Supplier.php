<?php

namespace App\Models;

use App\Models\Admin\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "id",
        "name",
        "contact",
        "address",
        "email",
        "ntn_number",
        "status",
        "type"
    ];
    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_suppliers', 'supplier_id', 'branch_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'supplier_items', 'supplier_id', 'item_id');
    }

    public function scopeActive(){
        return $this->where('status', true);
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
      public function scopeGeneral($query)
    {
        return $query->where('type', 'G');
    }


}
