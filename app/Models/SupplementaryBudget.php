<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplementaryBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'category_id',
        'sub_category_id',
        'month',
        'requested_amount',
        'approved_amount',
        'reason',
        'status',
        'requested_by',
        'approved_by'
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class, 'budget_id');
    }

    public function category()
    {
        return $this->belongsTo(BCategory::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(BCategory::class, 'sub_category_id');
    }

    public function requestedByUser(){
        return $this->belongsTo(User::class,'requested_by');
    }

     public function approvedByUser(){
        return $this->belongsTo(User::class,'approved_by');
    }

}
