<?php

namespace App\Models;

use App\Models\Admin\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Group; 

class Inventry extends Model
{
    use HasFactory;

    protected $table = 'inventories';

    protected $fillable = ['id', 'name', 'quantity', 'type', 'expiry_date'];

    public function scopeFood($query)
    {
        return $query->where('type', 'F');
    }

    public function scopeStationary($query)
    {
        return $query->where('type', 'S');
    }

    public function scopeCanteen($query)
    {
        return $query->where('type', 'F')->orWhere('type', 'P');
    }

    public function scopeExpiry($query)
    {
        return $query->where('expiry_date', '<', now());
    }
    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

}
