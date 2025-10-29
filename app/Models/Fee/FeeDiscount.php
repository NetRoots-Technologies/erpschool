<?php

namespace App\Models\Fee;

use App\Models\User;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Illuminate\Support\Facades\Auth;
use App\Models\Fee\FeeDiscountHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeDiscount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fee_discounts';

    protected $fillable = [
        'student_id',
        'category_id',
        'discount_type',
        'discount_value',
        'reason',
        'show_on_voucher',
        'valid_from',
        'valid_to',
        'company_id',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'show_on_voucher' => 'boolean',
    ];


    // Auto create and upade the histories
 protected static function boot()
{
    parent::boot();

    // ✅ Correct event: created (fires after record is saved)
    static::created(function ($discount) {
        \App\Models\Fee\FeeDiscountHistory::create([
            'fee_discount_id' => $discount->id,
            'updated_by' => Auth::id(),
            'old_data' => null,
            'new_data' => $discount->toArray(),
        ]);
    });

    // ✅ updating (fires before update)
    static::updating(function ($discount) {
        $oldData = $discount->getOriginal();
        $newData = $discount->getDirty();

        \App\Models\Fee\FeeDiscountHistory::create([
            'fee_discount_id' => $discount->id,
            'updated_by' => Auth::id(),
            'old_data' => $oldData,
            'new_data' => $newData,
        ]);
    });
}



    // Relationships
    public function student()
    {
        return $this->belongsTo(\App\Models\Student\Students::class);
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Fee\FeeCategory::class, 'category_id');
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

    public function scopeValid($query)
    {
        $today = now()->toDateString();
        return $query->where('valid_from', '<=', $today)
                    ->where('valid_to', '>=', $today);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByScope($query, $scope)
    {
        return $query->where('scope', $scope);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    // Helper Methods
    public function calculateDiscount($amount)
    {
        if ($this->discount_type === 'percentage') {
            return ($amount * $this->discount_value) / 100;
        }
        return $this->discount_value;
    }

    public function isCurrentlyValid()
    {
        if (!$this->is_active) return false;
        
        $today = now()->toDateString();
        
        // Check if discount is within validity period
        if ($this->valid_from && $this->valid_from > $today) return false;
        if ($this->valid_to && $this->valid_to < $today) return false;
        
        return true;
    }
}
