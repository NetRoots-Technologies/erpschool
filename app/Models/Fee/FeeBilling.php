<?php

namespace App\Models\Fee;

use App\Models\User;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use App\Models\Academic\AcademicClass;
use App\Models\Student\AcademicSession;
use App\Models\Student\Students;
use App\Models\Fee\FeeDiscount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeBilling extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fee_billing';

    protected $fillable = [
        'customer_invoice_id',
        'student_id',
        'academic_session_id',
        'bill_date',
        'due_date',
        'total_amount',
        'paid_amount',
        'outstanding_amount',
        'challan_number',
        'billing_month',
        'status',
        'company_id',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'outstanding_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Students::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
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
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeGenerated($query)
    {
        return $query->where('status', 'generated');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeForSession($query, $sessionId)
    {
        return $query->where('academic_session_id', $sessionId);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('bill_date', [$startDate, $endDate]);
    }

    // Helper Methods
    public function generateBillingSeries($campusCode, $year, $month, $billNumber)
    {
        return $campusCode . $year . $month . str_pad($billNumber, 4, '0', STR_PAD_LEFT);
    }

    // Discount Integration Methods
    public function getApplicableDiscounts()
    {
        // Check if billing month falls within discount validity period
        if ($this->billing_month) {
            $billingMonth = $this->billing_month; // Format: YYYY-MM
            
            $discounts = FeeDiscount::where('student_id', $this->student_id)
                ->where(function($query) use ($billingMonth) {
                    // Extract year-month from valid_from and valid_to dates
                    $query->whereRaw("DATE_FORMAT(valid_from, '%Y-%m') <= ?", [$billingMonth])
                          ->whereRaw("DATE_FORMAT(valid_to, '%Y-%m') >= ?", [$billingMonth]);
                })
                ->get();
        } else {
            // Fallback to current date if no billing month
            $today = now()->toDateString();
            $discounts = FeeDiscount::where('student_id', $this->student_id)
                ->where('valid_from', '<=', $today)
                ->where('valid_to', '>=', $today)
                ->get();
        }
            
        // Log for debugging
        \Log::info('Applicable discounts for student ' . $this->student_id, [
            'billing_month' => $this->billing_month,
            'discounts_count' => $discounts->count(),
            'discounts' => $discounts->toArray()
        ]);
        
        return $discounts;
    }

    public function calculateTotalDiscount()
    {
        $discounts = $this->getApplicableDiscounts();
        $totalDiscount = 0;
        
        foreach($discounts as $discount) {
            $totalDiscount += $discount->calculateDiscount($this->total_amount);
        }
        
        return $totalDiscount;
    }

    public function getFinalAmount()
    {
        $discount = $this->calculateTotalDiscount();
        return $this->total_amount - $discount;
    }

    public function applyDiscounts()
    {
        $discount = $this->calculateTotalDiscount();
        $this->outstanding_amount = $this->getFinalAmount();
        $this->save();
        return $this;
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isGenerated()
    {
        return $this->status === 'generated';
    }

    public function isSent()
    {
        return $this->status === 'sent';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function feeStructures() {
    return $this->hasMany(FeeStructure::class, 'student_id', 'student_id');
}
}
