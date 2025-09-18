<?php

namespace App\Models\Fee;

use App\Models\User;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use App\Models\Student\Students;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeVoucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fee_vouchers';

    const STATUS_GENERATED = 'generated';
    const STATUS_ISSUED = 'issued';
    const STATUS_PAID = 'paid';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'voucher_number',
        'fee_collection_id',
        'student_id',
        'total_amount',
        'discount_amount',
        'net_amount',
        'issue_date',
        'due_date',
        'expiry_date',
        'status',
        'payment_method',
        'bank_name',
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
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'issue_date' => 'date',
        'due_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function feeCollection()
    {
        return $this->belongsTo(FeeCollection::class);
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

    public function feeVoucherDetails()
    {
        return $this->hasMany(FeeVoucherDetail::class);
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

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeGenerated($query)
    {
        return $query->where('status', self::STATUS_GENERATED);
    }

    public function scopeIssued($query)
    {
        return $query->where('status', self::STATUS_ISSUED);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED)
                    ->orWhere('expiry_date', '<', now()->toDateString());
    }

    public function scopeDueOn($query, $date)
    {
        return $query->where('due_date', $date);
    }

    public function scopeDueBefore($query, $date)
    {
        return $query->where('due_date', '<', $date);
    }

    public function scopeIssuedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('issue_date', [$startDate, $endDate]);
    }

    // Helper methods
    public function getStatusOptions()
    {
        return [
            self::STATUS_GENERATED => 'Generated',
            self::STATUS_ISSUED => 'Issued',
            self::STATUS_PAID => 'Paid',
            self::STATUS_EXPIRED => 'Expired',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function getPaymentMethods()
    {
        return [
            'cash' => 'Cash',
            'cheque' => 'Cheque',
            'bank_transfer' => 'Bank Transfer',
            'online' => 'Online Payment',
            'card' => 'Card Payment',
        ];
    }

    public function isGenerated()
    {
        return $this->status === self::STATUS_GENERATED;
    }

    public function isIssued()
    {
        return $this->status === self::STATUS_ISSUED;
    }

    public function isPaid()
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isExpired()
    {
        return $this->status === self::STATUS_EXPIRED || 
               ($this->expiry_date && $this->expiry_date < now()->toDateString());
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isOverdue()
    {
        return $this->due_date < now()->toDateString() && 
               !in_array($this->status, [self::STATUS_PAID, self::STATUS_CANCELLED, self::STATUS_EXPIRED]);
    }

    public function getDaysUntilDue()
    {
        return now()->diffInDays($this->due_date, false);
    }

    public function getDaysUntilExpiry()
    {
        if (!$this->expiry_date) {
            return null;
        }
        
        return now()->diffInDays($this->expiry_date, false);
    }

    public function generateVoucherNumber()
    {
        $prefix = 'FV';
        $year = date('Y');
        $month = date('m');
        
        $lastVoucher = self::where('voucher_number', 'like', $prefix . $year . $month . '%')
                          ->orderBy('voucher_number', 'desc')
                          ->first();
        
        if ($lastVoucher) {
            $lastNumber = intval(substr($lastVoucher->voucher_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function markAsPaid($paymentMethod = null, $transactionId = null, $remarks = null)
    {
        $this->status = self::STATUS_PAID;
        
        if ($paymentMethod) {
            $this->payment_method = $paymentMethod;
        }
        
        if ($transactionId) {
            $this->transaction_id = $transactionId;
        }
        
        if ($remarks) {
            $this->remarks = $remarks;
        }
        
        $this->save();
        
        // Update related fee collection
        if ($this->feeCollection) {
            $this->feeCollection->updateStatus();
        }
    }
}