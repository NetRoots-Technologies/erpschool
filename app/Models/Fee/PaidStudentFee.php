<?php

namespace App\Models\Fee;

use App\Models\User;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use App\Models\Admin\Course;
use App\Models\Admin\Session;
use App\Models\Student\Students;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaidStudentFee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'paid_student_fee';

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';

    const TYPE_INSTALLMENT = 'installment';
    const TYPE_ADVANCE = 'advance';
    const TYPE_DISCOUNT = 'discount';
    const TYPE_FULL_PAYMENT = 'full_payment';

    const SOURCE_CASH = 'cash';
    const SOURCE_BANK = 'bank';
    const SOURCE_ONLINE = 'online';
    const SOURCE_CHEQUE = 'cheque';

    protected $fillable = [
        'student_fee_id',
        'student_id',
        'installement_amount',
        'paid_date',
        'start_date',
        'due_date',
        'paid_status',
        'source',
        'type',
        'status',
    ];

    protected $casts = [
        'installement_amount' => 'decimal:2',
        'paid_date' => 'date',
        'start_date' => 'date',
        'due_date' => 'date',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    // Note: StudentFee model has been removed, keeping student_fee_id for data integrity
    // public function studentFee()
    // {
    //     return $this->belongsTo(StudentFee::class, 'student_fee_id');
    // }

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function course()
    {
        return $this->hasOneThrough(
            Course::class,
            Students::class,
            'id', // Foreign key on Students table
            'id', // Foreign key on Course table
            'student_id', // Local key on PaidStudentFee table
            'course_id' // Local key on Students table
        );
    }

    public function sessions()
    {
        return $this->hasOneThrough(
            Session::class,
            Students::class,
            'id', // Foreign key on Students table
            'id', // Foreign key on Session table
            'student_id', // Local key on PaidStudentFee table
            'session_id' // Local key on Students table
        );
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('paid_status', self::STATUS_PAID);
    }

    public function scopePending($query)
    {
        return $query->where('paid_status', self::STATUS_PENDING);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeBySource($query, $source)
    {
        return $query->where('source', $source);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Accessors & Mutators
    public function getFormattedAmountAttribute()
    {
        return number_format($this->installement_amount, 2);
    }

    public function getStatusLabelAttribute()
    {
        switch ($this->paid_status) {
            case self::STATUS_PAID:
                return 'Paid';
            case self::STATUS_PENDING:
                return 'Pending';
            case self::STATUS_CANCELLED:
                return 'Cancelled';
            default:
                return 'Unknown';
        }
    }

    public function getTypeLabelAttribute()
    {
        switch ($this->type) {
            case self::TYPE_INSTALLMENT:
                return 'Installment';
            case self::TYPE_ADVANCE:
                return 'Advance';
            case self::TYPE_DISCOUNT:
                return 'Discount';
            case self::TYPE_FULL_PAYMENT:
                return 'Full Payment';
            default:
                return 'Unknown';
        }
    }

    public function getSourceLabelAttribute()
    {
        switch ($this->source) {
            case self::SOURCE_CASH:
                return 'Cash';
            case self::SOURCE_BANK:
                return 'Bank Transfer';
            case self::SOURCE_ONLINE:
                return 'Online Payment';
            case self::SOURCE_CHEQUE:
                return 'Cheque';
            default:
                return 'Unknown';
        }
    }

    // Helper methods
    public function isPaid()
    {
        return $this->paid_status === self::STATUS_PAID;
    }

    public function isPending()
    {
        return $this->paid_status === self::STATUS_PENDING;
    }

    public function isCancelled()
    {
        return $this->paid_status === self::STATUS_CANCELLED;
    }

    public function isInstallment()
    {
        return $this->type === self::TYPE_INSTALLMENT;
    }

    public function isAdvance()
    {
        return $this->type === self::TYPE_ADVANCE;
    }

    public function isDiscount()
    {
        return $this->type === self::TYPE_DISCOUNT;
    }

    public function markAsPaid()
    {
        $this->update([
            'paid_status' => self::STATUS_PAID,
            'paid_date' => now()->toDateString()
        ]);
    }

    public function markAsPending()
    {
        $this->update(['paid_status' => self::STATUS_PENDING]);
    }

    public function markAsCancelled()
    {
        $this->update(['paid_status' => self::STATUS_CANCELLED]);
    }
}