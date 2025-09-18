<?php

namespace App\Models\Fee;

use App\Models\User;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeCollectionDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fee_collection_details';

    protected $fillable = [
        'fee_collection_id',
        'fee_head_id',
        'amount',
        'discount_amount',
        'net_amount',
        'paid_amount',
        'balance_amount',
        'is_mandatory',
        'remarks',
        'company_id',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'is_mandatory' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function feeCollection()
    {
        return $this->belongsTo(FeeCollection::class);
    }

    public function feeHead()
    {
        return $this->belongsTo(FeeHead::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeForCollection($query, $collectionId)
    {
        return $query->where('fee_collection_id', $collectionId);
    }

    public function scopeForFeeHead($query, $feeHeadId)
    {
        return $query->where('fee_head_id', $feeHeadId);
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    public function scopeOptional($query)
    {
        return $query->where('is_mandatory', false);
    }

    public function scopePaid($query)
    {
        return $query->where('paid_amount', '>=', 'net_amount');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('paid_amount', '<', 'net_amount');
    }

    // Helper methods
    public function isPaid()
    {
        return $this->paid_amount >= $this->net_amount;
    }

    public function isPartiallyPaid()
    {
        return $this->paid_amount > 0 && $this->paid_amount < $this->net_amount;
    }

    public function isPending()
    {
        return $this->paid_amount == 0;
    }

    public function getPaymentPercentage()
    {
        if ($this->net_amount <= 0) {
            return 0;
        }
        
        return ($this->paid_amount / $this->net_amount) * 100;
    }

    public function calculateNetAmount()
    {
        $this->net_amount = $this->amount - $this->discount_amount;
        $this->balance_amount = $this->net_amount - $this->paid_amount;
        return $this->net_amount;
    }

    public function updateBalanceAmount()
    {
        $this->balance_amount = $this->net_amount - $this->paid_amount;
        $this->save();
    }
}