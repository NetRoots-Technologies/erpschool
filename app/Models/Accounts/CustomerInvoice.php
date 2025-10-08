<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerInvoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'invoice_date',
        'due_date',
        'reference',
        'subtotal',
        'tax_amount',
        'discount',
        'total_amount',
        'received_amount',
        'balance',
        'status',
        'notes',
        'journal_entry_id',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'received_amount' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
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
        $lastInvoice = self::orderBy('id', 'desc')->first();
        $number = $lastInvoice ? intval(substr($lastInvoice->invoice_number, 4)) + 1 : 1;
        return 'INV-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    public function updateStatus()
    {
        if ($this->received_amount >= $this->total_amount) {
            $this->status = 'paid';
        } elseif ($this->received_amount > 0) {
            $this->status = 'partially_paid';
        } elseif ($this->due_date < now() && $this->status != 'paid') {
            $this->status = 'overdue';
        }
        $this->save();
    }
}
