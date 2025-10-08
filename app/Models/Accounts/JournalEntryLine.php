<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JournalEntryLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_entry_id',
        'account_ledger_id',
        'description',
        'debit',
        'credit',
        'cost_center_id',
        'profit_center_id',
        'reference',
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
    ];

    // Relationships
    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    public function accountLedger()
    {
        return $this->belongsTo(AccountLedger::class, 'account_ledger_id');
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function profitCenter()
    {
        return $this->belongsTo(ProfitCenter::class, 'profit_center_id');
    }
}
