<?php

namespace App\Models\HR;

use App\Models\Admin\Branch;
use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherBranch extends Model
{
    use SoftDeletes;

    use HasFactory;

    protected $table = 'other_branches';

    protected $fillable = ['employee_id', 'branch_id', 'main_branch'];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');

    }
}
