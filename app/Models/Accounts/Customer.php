<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'account_customers';

    protected $fillable = [
        'name',
        'code',
        'email',
        'phone',
        'contact_person',
        'address',
        'city',
        'state',
        'country',
        'tax_number',
        'payment_terms',
        'credit_limit',
        'account_ledger_id',
        'is_active',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credit_limit' => 'decimal:2',
    ];

    // Relationships
    public function accountLedger()
    {
        return $this->belongsTo(AccountLedger::class, 'account_ledger_id');
    }

    public function invoices()
    {
        return $this->hasMany(CustomerInvoice::class, 'customer_id');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Admin\Branches::class, 'branch_id');
    }

    // Helper methods
    public function getTotalOutstandingAttribute()
    {
        return $this->invoices()
            ->whereIn('status', ['sent', 'partially_paid', 'overdue'])
            ->sum('balance');
    }
}
