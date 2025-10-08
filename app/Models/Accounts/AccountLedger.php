<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountLedger extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'account_group_id',
        'opening_balance',
        'opening_balance_type',
        'current_balance',
        'current_balance_type',
        'currency_id',
        'is_active',
        'is_system',
        'linked_module',
        'linked_id',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    // Relationships
    public function accountGroup()
    {
        return $this->belongsTo(AccountGroup::class, 'account_group_id');
    }

    public function currency()
    {
        return $this->belongsTo(AccountCurrency::class, 'currency_id');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Admin\Branches::class, 'branch_id');
    }

    public function journalLines()
    {
        return $this->hasMany(JournalEntryLine::class, 'account_ledger_id');
    }

    // Helper methods
    public function updateBalance($debit, $credit)
    {
        $balance = $this->current_balance;
        
        if ($this->current_balance_type == 'debit') {
            $balance += $debit - $credit;
        } else {
            $balance += $credit - $debit;
        }
        
        $this->current_balance = abs($balance);
        $this->current_balance_type = $balance >= 0 ? 'debit' : 'credit';
        $this->save();
    }

    public function getBalanceAttribute()
    {
        $sign = $this->current_balance_type == 'debit' ? '' : '-';
        return $sign . number_format($this->current_balance, 2);
    }
}
