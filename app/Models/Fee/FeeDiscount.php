<?php

namespace App\Models\Fee;

use App\Models\User;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use App\Models\Student\Students;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeDiscount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fee_discounts';

    const DISCOUNT_TYPE_FIXED = 'fixed';
    const DISCOUNT_TYPE_PERCENTAGE = 'percentage';

    const DISCOUNT_SCOPE_GLOBAL = 'global';
    const DISCOUNT_SCOPE_CATEGORY = 'category';
    const DISCOUNT_SCOPE_SECTION = 'section';
    const DISCOUNT_SCOPE_HEAD = 'head';

    protected $fillable = [
        'name',
        'description',
        'discount_type',
        'discount_value',
        'discount_scope',
        'fee_category_id',
        'fee_section_id',
        'fee_head_id',
        'student_id',
        'valid_from',
        'valid_to',
        'is_active',
        'company_id',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function feeCategory()
    {
        return $this->belongsTo(FeeCategory::class);
    }

    public function feeSection()
    {
        return $this->belongsTo(FeeSection::class);
    }

    public function feeHead()
    {
        return $this->belongsTo(FeeHead::class);
    }

    public function student()
    {
        return $this->belongsTo(Students::class);
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

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeValidOn($query, $date)
    {
        return $query->where('valid_from', '<=', $date)
                    ->where(function($q) use ($date) {
                        $q->whereNull('valid_to')
                          ->orWhere('valid_to', '>=', $date);
                    });
    }

    public function scopeByType($query, $type)
    {
        return $query->where('discount_type', $type);
    }

    public function scopeByScope($query, $scope)
    {
        return $query->where('discount_scope', $scope);
    }

    public function scopeGlobal($query)
    {
        return $query->where('discount_scope', self::DISCOUNT_SCOPE_GLOBAL);
    }

    // Helper methods
    public function getDiscountTypes()
    {
        return [
            self::DISCOUNT_TYPE_FIXED => 'Fixed Amount',
            self::DISCOUNT_TYPE_PERCENTAGE => 'Percentage',
        ];
    }

    public function getDiscountScopes()
    {
        return [
            self::DISCOUNT_SCOPE_GLOBAL => 'Global (All Fees)',
            self::DISCOUNT_SCOPE_CATEGORY => 'Fee Category',
            self::DISCOUNT_SCOPE_SECTION => 'Fee Section',
            self::DISCOUNT_SCOPE_HEAD => 'Fee Head',
        ];
    }

    public function isFixed()
    {
        return $this->discount_type === self::DISCOUNT_TYPE_FIXED;
    }

    public function isPercentage()
    {
        return $this->discount_type === self::DISCOUNT_TYPE_PERCENTAGE;
    }

    public function isValidOn($date)
    {
        return $this->valid_from <= $date && 
               ($this->valid_to === null || $this->valid_to >= $date);
    }

    public function isCurrentlyValid()
    {
        return $this->isValidOn(now()->toDateString());
    }

    public function calculateDiscount($amount)
    {
        if (!$this->isCurrentlyValid() || !$this->is_active) {
            return 0;
        }

        if ($this->isFixed()) {
            return min($this->discount_value, $amount);
        }

        if ($this->isPercentage()) {
            return ($amount * $this->discount_value) / 100;
        }

        return 0;
    }
}