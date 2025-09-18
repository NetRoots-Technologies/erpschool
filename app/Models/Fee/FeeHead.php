<?php

namespace App\Models\Fee;

use App\Models\User;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeHead extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fee_heads';

    const FEE_TYPE_FIXED = 'fixed';
    const FEE_TYPE_PERCENTAGE = 'percentage';
    const FEE_TYPE_PER_UNIT = 'per_unit';

    protected $fillable = [
        'name',
        'description',
        'fee_section_id',
        'fee_type',
        'amount',
        'percentage',
        'is_mandatory',
        'is_active',
        'company_id',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'percentage' => 'decimal:2',
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function feeSection()
    {
        return $this->belongsTo(FeeSection::class);
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
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    public function scopeOptional($query)
    {
        return $query->where('is_mandatory', false);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeForSection($query, $sectionId)
    {
        return $query->where('fee_section_id', $sectionId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('fee_type', $type);
    }

    // Helper methods
    public function getFeeTypes()
    {
        return [
            self::FEE_TYPE_FIXED => 'Fixed Amount',
            self::FEE_TYPE_PERCENTAGE => 'Percentage',
            self::FEE_TYPE_PER_UNIT => 'Per Unit',
        ];
    }

    public function isFixed()
    {
        return $this->fee_type === self::FEE_TYPE_FIXED;
    }

    public function isPercentage()
    {
        return $this->fee_type === self::FEE_TYPE_PERCENTAGE;
    }

    public function isPerUnit()
    {
        return $this->fee_type === self::FEE_TYPE_PER_UNIT;
    }
}