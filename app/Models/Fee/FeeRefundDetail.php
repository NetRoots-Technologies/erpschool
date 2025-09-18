<?php

namespace App\Models\Fee;

use App\Models\User;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeRefundDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fee_refund_details';

    protected $fillable = [
        'fee_refund_id',
        'fee_head_id',
        'original_amount',
        'refund_amount',
        'refund_reason',
        'remarks',
        'company_id',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function feeRefund()
    {
        return $this->belongsTo(FeeRefund::class);
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

    public function scopeForRefund($query, $refundId)
    {
        return $query->where('fee_refund_id', $refundId);
    }

    public function scopeForFeeHead($query, $feeHeadId)
    {
        return $query->where('fee_head_id', $feeHeadId);
    }

    // Helper methods
    public function getRefundPercentage()
    {
        if ($this->original_amount <= 0) {
            return 0;
        }
        
        return ($this->refund_amount / $this->original_amount) * 100;
    }

    public function isFullRefund()
    {
        return $this->refund_amount >= $this->original_amount;
    }

    public function isPartialRefund()
    {
        return $this->refund_amount > 0 && $this->refund_amount < $this->original_amount;
    }

    public function getRemainingAmount()
    {
        return $this->original_amount - $this->refund_amount;
    }
}