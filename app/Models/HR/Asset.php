<?php

namespace App\Models\HR;

use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_type_id',
        'name',
        'code',
        'working',
        'company_id',
        'branch_id',
        'purchase_date',
        'depreciation_type',
        'invoice_number',
        'manufacturer',
        'serial_number',
        'end_date',
        'image',
        'amount',
        'depreciation',
        'sale_tax',
        'income_tax',
        'narration',
        'note',
        'credit_type',
        'credit_ledger'
    ];

    protected $table = 'assets';

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

}
