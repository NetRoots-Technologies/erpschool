<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\BankAccount;

class BankAccount extends Model
{
    use HasFactory;

    protected $table = 'banks_accounts';

    protected $fillable = ['bank_id', 'bank_branch_id', 'account_no', 'type'];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function bankBranch()
    {
        return $this->belongsTo(BankBranch::class, 'bank_branch_id');
    }
    public function ledger()
    {
        return $this->hasOne(Ledgers::class, 'parent_type_id', 'id')->where('parent_type', self::class);
    }
}
