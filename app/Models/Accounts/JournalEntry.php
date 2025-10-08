<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JournalEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'entry_number',
        'entry_date',
        'reference',
        'description',
        'status',
        'entry_type',
        'currency_id',
        'exchange_rate',
        'branch_id',
        'cost_center_id',
        'profit_center_id',
        'source_module',
        'source_id',
        'posted_at',
        'posted_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'posted_at' => 'datetime',
        'exchange_rate' => 'decimal:6',
    ];

    // Relationships
    public function lines()
    {
        return $this->hasMany(JournalEntryLine::class, 'journal_entry_id');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Admin\Branches::class, 'branch_id');
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function profitCenter()
    {
        return $this->belongsTo(ProfitCenter::class, 'profit_center_id');
    }

    // Helper methods
    public function getTotalDebitAttribute()
    {
        return $this->lines->sum('debit');
    }

    public function getTotalCreditAttribute()
    {
        return $this->lines->sum('credit');
    }

    public function isBalanced()
    {
        return abs($this->total_debit - $this->total_credit) < 0.01;
    }

    public function post()
    {
        if (!$this->isBalanced()) {
            throw new \Exception('Journal entry is not balanced');
        }

        $this->status = 'posted';
        $this->posted_at = now();
        $this->posted_by = auth()->id();
        $this->save();

        // Update ledger balances
        foreach ($this->lines as $line) {
            $line->accountLedger->updateBalance($line->debit, $line->credit);
        }
    }

    public static function generateNumber()
    {
        $lastEntry = self::orderBy('id', 'desc')->first();
        $number = $lastEntry ? intval(substr($lastEntry->entry_number, 3)) + 1 : 1;
        return 'JE-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
