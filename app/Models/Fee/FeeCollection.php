<?php

namespace App\Models\Fee;

use App\Models\User;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use App\Models\Student\Students;
use App\Models\Academic\Classes;
use App\Models\Academic\Section;
use App\Models\Academic\AcademicSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeCollection extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fee_collections';

    const STATUS_PENDING = 'pending';
    const STATUS_PARTIAL = 'partial';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'collection_number',
        'student_id',
        'class_id',
        'section_id',
        'academic_session_id',
        'fee_term_id',
        'total_amount',
        'paid_amount',
        'discount_amount',
        'balance_amount',
        'due_date',
        'status',
        'remarks',
        'is_active',
        'company_id',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'due_date' => 'date',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Students::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function feeTerm()
    {
        return $this->belongsTo(FeeTerm::class);
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

    public function feeCollectionDetails()
    {
        return $this->hasMany(FeeCollectionDetail::class);
    }

    public function feeVouchers()
    {
        return $this->hasMany(FeeVoucher::class);
    }

    public function feeReceipts()
    {
        return $this->hasMany(FeeReceipt::class);
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

    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeForSection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    public function scopeForSession($query, $sessionId)
    {
        return $query->where('academic_session_id', $sessionId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE)
                    ->orWhere(function($q) {
                        $q->where('due_date', '<', now()->toDateString())
                          ->whereIn('status', [self::STATUS_PENDING, self::STATUS_PARTIAL]);
                    });
    }

    public function scopeDueOn($query, $date)
    {
        return $query->where('due_date', $date);
    }

    public function scopeDueBefore($query, $date)
    {
        return $query->where('due_date', '<', $date);
    }

    // Helper methods
    public function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PARTIAL => 'Partially Paid',
            self::STATUS_PAID => 'Paid',
            self::STATUS_OVERDUE => 'Overdue',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPartiallyPaid()
    {
        return $this->status === self::STATUS_PARTIAL;
    }

    public function isPaid()
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isOverdue()
    {
        return $this->status === self::STATUS_OVERDUE || 
               ($this->due_date < now()->toDateString() && 
                in_array($this->status, [self::STATUS_PENDING, self::STATUS_PARTIAL]));
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function getPaymentPercentage()
    {
        if ($this->total_amount <= 0) {
            return 0;
        }
        
        return ($this->paid_amount / $this->total_amount) * 100;
    }

    public function updateStatus()
    {
        if ($this->paid_amount >= $this->total_amount) {
            $this->status = self::STATUS_PAID;
            $this->balance_amount = 0;
        } elseif ($this->paid_amount > 0) {
            $this->status = self::STATUS_PARTIAL;
            $this->balance_amount = $this->total_amount - $this->paid_amount;
        } else {
            $this->status = self::STATUS_PENDING;
            $this->balance_amount = $this->total_amount;
        }

        // Check if overdue
        if ($this->due_date < now()->toDateString() && 
            in_array($this->status, [self::STATUS_PENDING, self::STATUS_PARTIAL])) {
            $this->status = self::STATUS_OVERDUE;
        }

        $this->save();
    }
}