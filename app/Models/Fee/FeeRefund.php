<?php

namespace App\Models\Fee;

use App\Models\User;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use App\Models\Student\Students;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeRefund extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fee_refunds';

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PROCESSED = 'processed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'refund_number',
        'fee_receipt_id',
        'student_id',
        'refund_amount',
        'refund_reason',
        'request_date',
        'approval_date',
        'processed_date',
        'status',
        'approved_by',
        'processed_by',
        'refund_method',
        'bank_name',
        'account_number',
        'cheque_number',
        'transaction_id',
        'remarks',
        'is_active',
        'company_id',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',
        'request_date' => 'date',
        'approval_date' => 'date',
        'processed_date' => 'date',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function feeReceipt()
    {
        return $this->belongsTo(FeeReceipt::class);
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

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function feeRefundDetails()
    {
        return $this->hasMany(FeeRefundDetail::class);
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

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', self::STATUS_PROCESSED);
    }

    public function scopeRequestedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('request_date', [$startDate, $endDate]);
    }

    public function scopeApprovedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('approval_date', [$startDate, $endDate]);
    }

    public function scopeProcessedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('processed_date', [$startDate, $endDate]);
    }

    // Helper methods
    public function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_PROCESSED => 'Processed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function getRefundMethods()
    {
        return [
            'cash' => 'Cash',
            'cheque' => 'Cheque',
            'bank_transfer' => 'Bank Transfer',
            'online' => 'Online Transfer',
            'adjustment' => 'Fee Adjustment',
        ];
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isProcessed()
    {
        return $this->status === self::STATUS_PROCESSED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function canBeApproved()
    {
        return $this->isPending();
    }

    public function canBeRejected()
    {
        return $this->isPending();
    }

    public function canBeProcessed()
    {
        return $this->isApproved();
    }

    public function canBeCancelled()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED]);
    }

    public function generateRefundNumber()
    {
        $prefix = 'REF';
        $year = date('Y');
        $month = date('m');
        
        $lastRefund = self::where('refund_number', 'like', $prefix . $year . $month . '%')
                         ->orderBy('refund_number', 'desc')
                         ->first();
        
        if ($lastRefund) {
            $lastNumber = intval(substr($lastRefund->refund_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function approve($approvedBy, $remarks = null)
    {
        $this->status = self::STATUS_APPROVED;
        $this->approved_by = $approvedBy;
        $this->approval_date = now()->toDateString();
        
        if ($remarks) {
            $this->remarks = $this->remarks ? $this->remarks . ' | ' . $remarks : $remarks;
        }
        
        $this->save();
    }

    public function reject($rejectedBy, $reason)
    {
        $this->status = self::STATUS_REJECTED;
        $this->approved_by = $rejectedBy;
        $this->approval_date = now()->toDateString();
        $this->remarks = $this->remarks ? $this->remarks . ' | Rejected: ' . $reason : 'Rejected: ' . $reason;
        
        $this->save();
    }

    public function process($processedBy, $refundMethod, $transactionDetails = [])
    {
        $this->status = self::STATUS_PROCESSED;
        $this->processed_by = $processedBy;
        $this->processed_date = now()->toDateString();
        $this->refund_method = $refundMethod;
        
        if (isset($transactionDetails['bank_name'])) {
            $this->bank_name = $transactionDetails['bank_name'];
        }
        
        if (isset($transactionDetails['account_number'])) {
            $this->account_number = $transactionDetails['account_number'];
        }
        
        if (isset($transactionDetails['cheque_number'])) {
            $this->cheque_number = $transactionDetails['cheque_number'];
        }
        
        if (isset($transactionDetails['transaction_id'])) {
            $this->transaction_id = $transactionDetails['transaction_id'];
        }
        
        $this->save();
    }

    public function getDaysFromRequest()
    {
        return now()->diffInDays($this->request_date);
    }

    public function getDaysFromApproval()
    {
        if (!$this->approval_date) {
            return null;
        }
        
        return now()->diffInDays($this->approval_date);
    }
}