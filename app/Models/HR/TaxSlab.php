<?php

namespace App\Models\HR;

use App\Models\Financial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxSlab extends Model
{
    use HasFactory;

    protected $table = 'tax_slabs';

    protected $fillable = ['financial_year_id', 'fix_amount', 'tax_percent', 'start_range', 'end_range', 'tax_type'];

    public function financial()
    {
        return $this->belongsTo(Financial::class, 'financial_year_id');
    }
}
