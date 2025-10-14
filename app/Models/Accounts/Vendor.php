<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'account_vendors';

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
        'account_ledger_id',
        'is_active',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function accountLedger()
    {
        return $this->belongsTo(AccountLedger::class, 'account_ledger_id');
    }

    public function bills()
    {
        return $this->hasMany(VendorBill::class, 'vendor_id');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Admin\Branches::class, 'branch_id');
    }

    // Helper methods
    public function getTotalOutstandingAttribute()
    {
        try {
            return $this->bills()
                ->whereIn('status', ['pending', 'partially_paid', 'overdue'])
                ->sum('balance') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
