<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HR\LeaveRequest;
class ApprovalRequest extends Model
{
    use HasFactory;
    protected $fillable=['leave_request_id','approval_authority_id','status','remarks'];

    public function leaveRequest(){
        return $this->belongsTo(LeaveRequest::class);
    }

    public function approvalAuthority()
    {
        return $this->belongsTo(ApprovalAuthority::class, 'approval_authority_id');
    }

    public function approvalAuthorityWithUser()
    {
        return $this->belongsTo(ApprovalAuthority::class, 'approval_authority_id')->with('user');
    }


}
