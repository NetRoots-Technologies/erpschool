<?php

namespace App\Models\HR;

use App\Models\Admin\Branch;
use App\Models\Admin\Branches;
use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialSecurity extends Model
{
    use HasFactory;
    protected $table = 'social_securities';

    protected $fillable = ['percentage', 'branch_id', 'employee_id'];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
