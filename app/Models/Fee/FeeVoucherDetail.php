<?php

namespace App\Models\Fee;

use App\Models\User;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeVoucherDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fee_voucher_details';

    protected $fillable = [
        'fee_voucher_id',
        'fee_head_id',
        'amount',
        'discount_amount',
        'net_amount',
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function feeVoucher()
    {
        return $this->belongsTo(FeeVoucher::class);
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

    public function scopeForVoucher($query, $voucherId)
    {
        return $query->where('fee_voucher_id', $voucherId);
    }

    public function scopeForFeeHead($query, $feeHeadId)
    {
        return $query->where('fee_head_id', $feeHeadId);
    }

    // Helper methods
    public function calculateNetAmount()
    {
        $this->net_amount = $this->amount - $this->discount_amount;
        return $this->net_amount;
    }

    public function hasDiscount()
    {
        return $this->discount_amount > 0;
    }

    public function getDiscountPercentage()
    {
        if ($this->amount <= 0) {
            return 0;
        }
        
        return ($this->discount_amount / $this->amount) * 100;
    }
}