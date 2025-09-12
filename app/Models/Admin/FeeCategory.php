<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeCategory extends Model
{
    use HasFactory;
    protected $table = 'fee_categories';

    protected $fillable = ['branch_id', 'session_id', 'company_id', 'category', 'fa_percent', 'full_fee', 'active', 'FA'];

}
