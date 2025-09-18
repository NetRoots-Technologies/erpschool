<?php

namespace App\Models\Fee;

use App\Models\User;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use App\Models\Student\Students;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeReceipt extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fee_receipts';

    const STATUS_GENERATED = 'generated';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'receipt_number',
        'fee_collection_id',
        'fee_voucher_id',
        'student_id',
        'payment_date',
        'total_amount',
        'discount_amount',
        'net_amount',
        'payment_method',
        'bank_name',
        'cheque_number',
        'cheque_date',
        'transaction_id',
        'status',
        'remarks',
        'is_active',
        'company_id',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'cheque_date' => 'date',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
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

    public function feeVoucher()
    {
        return $this->belongsTo(FeeVoucher::class);
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

    public function feeReceiptDetails()
    {
        return $this->hasMany(FeeReceiptDetail::class);
    }

    public function feeRefunds()
    {
        return $this->hasMany(FeeRefund::class);
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

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopePaidBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    public function scopePaidOn($query, $date)
    {
        return $query->where('payment_date', $date);
    }

    // Helper methods
    public function getStatusOptions()
    {
        return [
            self::STATUS_GENERATED => 'Generated',
            self::STATUS_CONFIRMED => 'Confirmed',
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
            'upi' => 'UPI Payment',
        ];
    }

    public function isGenerated()
    {
        return $this->status === self::STATUS_GENERATED;
    }

    public function isConfirmed()
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isCashPayment()
    {
        return $this->payment_method === 'cash';
    }

    public function isChequePayment()
    {
        return $this->payment_method === 'cheque';
    }

    public function isOnlinePayment()
    {
        return in_array($this->payment_method, ['online', 'card', 'upi', 'bank_transfer']);
    }

    public function generateReceiptNumber()
    {
        $prefix = 'FR';
        $year = date('Y');
        $month = date('m');
        
        $lastReceipt = self::where('receipt_number', 'like', $prefix . $year . $month . '%')
                          ->orderBy('receipt_number', 'desc')
                          ->first();
        
        if ($lastReceipt) {
            $lastNumber = intval(substr($lastReceipt->receipt_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function confirm()
    {
        $this->status = self::STATUS_CONFIRMED;
        $this->save();
        
        // Update related fee collection and voucher
        if ($this->feeCollection) {
            $this->feeCollection->paid_amount += $this->net_amount;
            $this->feeCollection->updateStatus();
        }
        
        if ($this->feeVoucher) {
            $this->feeVoucher->markAsPaid($this->payment_method, $this->transaction_id);
        }
    }

    public function cancel($reason = null)
    {
        $this->status = self::STATUS_CANCELLED;
        
        if ($reason) {
            $this->remarks = $this->remarks ? $this->remarks . ' | Cancelled: ' . $reason : 'Cancelled: ' . $reason;
        }
        
        $this->save();
        
        // Update related fee collection
        if ($this->feeCollection && $this->isConfirmed()) {
            $this->feeCollection->paid_amount -= $this->net_amount;
            $this->feeCollection->updateStatus();
        }
    }

    public function getTotalRefundedAmount()
    {
        return $this->feeRefunds()->where('status', 'approved')->sum('refund_amount');
    }

    public function getRefundableAmount()
    {
        return $this->net_amount - $this->getTotalRefundedAmount();
    }

    public function canBeRefunded()
    {
        return $this->isConfirmed() && $this->getRefundableAmount() > 0;
    }
}