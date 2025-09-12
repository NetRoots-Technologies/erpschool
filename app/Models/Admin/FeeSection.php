<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeSection extends Model
{
    use HasFactory;

    protected $table = 'fee_sections';

    protected $fillable = ['branch_id', 'print_section', 'name', 'status'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
