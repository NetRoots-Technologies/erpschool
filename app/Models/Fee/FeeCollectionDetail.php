<?php

namespace App\Models\Fee;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeCollectionDetail extends Model
{
    use HasFactory;

    protected $table = 'fee_collection_details';

    protected $fillable = [
        'fee_collection_id',
        'fee_category_id',
        'amount',
        'company_id',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function feeCollection()
    {
        return $this->belongsTo(FeeCollection::class, 'fee_collection_id');
    }

    public function feeCategory()
    {
        return $this->belongsTo(FeeCategory::class);
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePartial($query)
    {
        return $query->where('status', 'partial');
    }

    // Helper Methods
    public function getRemainingAmount()
    {
        return $this->amount - $this->collected_amount - $this->discount_amount;
    }

    public function isFullyPaid()
    {
        return $this->getRemainingAmount() <= 0;
    }
}
