<?php

namespace App\Models\HR;

use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ApprovalRequest;
use App\Models\HR\Quotta;
use App\Models\HR\WorkShift;
use App\Models\HR\LeaveApproval;


class LeaveRequest extends Model
{
    use SoftDeletes;

    use HasFactory;

    protected $table = 'leave_requests';

    protected $fillable = ['hrm_employee_id', 'hr_quota_setting_id', 'work_shift_id', 'responsible_employee', 'start_date', 'days', 'end_date', 'duration', 'start_time', 'end_time', 'evidence', 'comments'];


    public function employee()
    {
        return $this->belongsTo(Employees::class, 'hrm_employee_id');
    }

    public function quota()
    {
        return $this->belongsTo(Quotta::class, 'hr_quota_setting_id');
    }

    public function workShift()
    {
        return $this->belongsTo(WorkShift::class, 'work_shift_id');

    }

   public function approvalRequests()
    {
        return $this->hasMany(ApprovalRequest::class);
    }

    // public function approvals() {
    //     return $this->hasMany(LeaveApproval::class);
    // }
    // 🔥 THIS IS REQUIRED
    public function approval()
    {
        return $this->hasOne(
            \App\Models\HR\LeaveApproval::class,
            'leave_request_id',
            'id'
        );
    }

    public function latestApproval() {
        return $this->hasOne(LeaveApproval::class)->latestOfMany();
    }

   
}
