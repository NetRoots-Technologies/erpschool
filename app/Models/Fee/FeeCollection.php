<?php

namespace App\Models\Fee;

use App\Models\User;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use App\Models\Student\Students;
use App\Models\Academic\AcademicClass;
use App\Models\Student\AcademicSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeCollection extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fee_collections';

    protected $fillable = [
        'student_id',
        'academic_class_id',
        'academic_session_id',
        'fee_assignment_id',
        'collection_date',
        'paid_amount',
        'payment_method',
        'remarks',
        'status',
        'company_id',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'collection_date' => 'date',
        'paid_amount' => 'decimal:2',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Students::class);
    }

    public function studentFeeAssignment()
    {
        return $this->belongsTo(StudentFeeAssignment::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'academic_class_id');
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

    public function rollbackBy()
    {
        return $this->belongsTo(User::class, 'rollback_by');
    }

    public function feeCollectionDetails()
    {
        return $this->hasMany(FeeCollectionDetail::class, 'fee_collection_id');
    }

    public function details()
    {
        return $this->hasMany(FeeCollectionDetail::class, 'fee_collection_id');
    }

    public function feeAdjustments()
    {
        return $this->hasMany(FeeAdjustment::class);
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePartial($query)
    {
        return $query->where('status', 'partial');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
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

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('collection_date', [$startDate, $endDate]);
    }

    // Helper Methods
    public function isOverdue()
    {
        return $this->due_date < now() && $this->status !== 'paid';
    }

    public function calculateFine()
    {
        if ($this->isOverdue()) {
            return 1500; // Fixed fine amount
        }
        return 0;
    }

    public function getTotalDue()
    {
        return $this->total_amount + $this->fine_amount - $this->collected_amount - $this->discount_amount;
    }
}
