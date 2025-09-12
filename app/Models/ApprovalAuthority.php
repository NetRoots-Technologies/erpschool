<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ApprovalRole;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\ApprovalAuthority;
use App\Models\Student\ApprovalRequest;

class ApprovalAuthority extends Model
{
    use HasFactory;
    protected $fillable = [
        'module',
        'company_id',
        'branch_id',
        'user_id',
        'approval_role_id',
        'is_active',
    ];

    public function company() {
    return $this->belongsTo(Company::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function role() {
        return $this->belongsTo(ApprovalRole::class, 'approval_role_id');
    }

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }

    public function approval_requests() {
        return $this->belongsTo(ApprovalRequest::class);
    }


}
