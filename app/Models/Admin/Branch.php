<?php

namespace App\Models\Admin;

use App\Models\Academic\AcademicClass;
use App\Models\Academic\SchoolType;
use App\Models\Academic\SchoolTypeBranch;
use App\Models\HR\OtherBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'company_id',
        'ip_config',
        'port',
        'address',
        'branch_code',
        'emp_branch_code'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function department()
    {
        return $this->hasMany(Department::class, 'branch_id');
    }

    public function schoolBranch()
    {
        return $this->hasMany(SchoolTypeBranch::class, 'branch_id');
    }

    public function Otherbranch()
    {
        return $this->hasMany(OtherBranch::class, 'branch_id');
    }

    public function classes()
    {
        return $this->hasMany(AcademicClass::class, 'branch_id');
    }

}
