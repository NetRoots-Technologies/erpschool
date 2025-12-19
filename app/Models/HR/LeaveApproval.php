<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApproval extends Model {
    use HasFactory;

    protected $fillable = ['leave_request_id','approved_by','status','remarks'];

    public function leaveRequest() {
        return $this->belongsTo(LeaveRequest::class);
    }

    public function approver() {
        return $this->belongsTo(\App\Models\User::class,'approved_by');
    }
}
