<?php

namespace App\Models\Admin;

use App\Models\Accounts\AccountLedger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\BankBranch;
class BankAccount extends Model
{
    use HasFactory;

    protected $table = 'banks_accounts';

    protected $fillable = ['bank_id', 'bank_branch_id', 'account_no', 'type'];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }



    public function branches()
    {
        return $this->belongsTo(BankBranch::class, 'bank_branch_id' , 'id');
    }
    public function ledger()
    {
        return $this->hasOne(AccountLedger::class, 'linked_id', 'id')->where('linked_module', 'bank_account');
    }
}
