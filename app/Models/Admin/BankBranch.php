<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankBranch extends Model
{
    use HasFactory;

    protected $table = 'banks_branches';

    protected $fillable = ['bank_id', 'branch_code', 'status', 'branch_name'];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}
