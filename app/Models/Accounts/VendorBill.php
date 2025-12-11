<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorBill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bill_number',
        'vendor_id',
        'bill_date',
        'due_date',
        'vendor_invoice_number',
        'subtotal',
        'tax_amount',
        'discount',
        'total_amount',
        'paid_amount',
        'balance',
        'status',
        'notes',
        'journal_entry_id',
        'branch_id',
        'created_by',
        'updated_by',
        'source_module',
        'source_id',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Admin\Branches::class, 'branch_id');
    }

    // Helper methods
    public static function generateNumber()
    {
        $lastBill = self::orderBy('id', 'desc')->first();
        $number = $lastBill ? intval(substr($lastBill->bill_number, 5)) + 1 : 1;
        return 'BILL-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    public function updateStatus()
    {
        if ($this->paid_amount >= $this->total_amount) {
            $this->status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partially_paid';
        } elseif ($this->due_date < now() && $this->status != 'paid') {
            $this->status = 'overdue';
        }
        $this->save();
    }
}
