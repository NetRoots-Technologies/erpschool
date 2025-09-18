<?php

namespace App\Models\Fee;

use App\Models\User;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use App\Models\Student\AcademicSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeTerm extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fee_terms';

    protected $fillable = [
        'name',
        'description',
        'academic_session_id',
        'start_date',
        'end_date',
        'due_date',
        'late_fee_amount',
        'late_fee_percentage',
        'is_active',
        'company_id',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'due_date' => 'date',
        'late_fee_amount' => 'decimal:2',
        'late_fee_percentage' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
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

    public function feeCollections()
    {
        return $this->hasMany(FeeCollection::class);
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

    public function scopeForSession($query, $sessionId)
    {
        return $query->where('academic_session_id', $sessionId);
    }

    public function scopeCurrent($query)
    {
        $today = now()->toDateString();
        return $query->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now()->toDateString());
    }

    public function scopePast($query)
    {
        return $query->where('end_date', '<', now()->toDateString());
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now()->toDateString());
    }

    // Helper methods
    public function isCurrent()
    {
        $today = now()->toDateString();
        return $this->start_date <= $today && $this->end_date >= $today;
    }

    public function isOverdue()
    {
        return $this->due_date < now()->toDateString();
    }

    public function isUpcoming()
    {
        return $this->start_date > now()->toDateString();
    }

    public function isPast()
    {
        return $this->end_date < now()->toDateString();
    }

    public function getDaysUntilDue()
    {
        return now()->diffInDays($this->due_date, false);
    }

    public function hasLateFee()
    {
        return $this->late_fee_amount > 0 || $this->late_fee_percentage > 0;
    }
}