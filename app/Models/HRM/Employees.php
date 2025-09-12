<?php

namespace App\Models\HRM;

use App\Models\Admin\Company;
use App\Models\Admin\Departments;
use App\Models\HR\Attendance;
use App\Models\HR\Designation;
use App\Models\HR\EmployeeAllowance;
use App\Models\HR\EmployeeWelfare;
use App\Models\HR\Holiday;
use App\Models\HR\OtherBranch;
use App\Models\HR\ProfitFund;
use App\Models\HR\WorkShift;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\Admin\Branches;
use App\Models\EmployeeChild;

class Employees extends Model
{
    use SoftDeletes;
    protected $table = 'hrm_employees';
    
    protected $fillable = [
        'emp_id',
        'areas',
        'company_id',
        'branch_id',
        'full_time',
        'part_time',
        'temporary',
        'start_date',
        'salary',
        'applied',
        'applied_yes',
        'employed',
        'when_employed_yes',
        'engaged_business',
        'when_business_yes',
        'nationality',
        'religion',
        'blood_group',
        'marital_status',
        'skills',
        'name',
        'email_address',
        'father_name',
        'cnic_card',
        'tell_no',
        'mobile_no',
        'present_address',
        'permanent_address',
        'dob',
        'job_seeking',
        'designation_id',
        // 'other_branch',
        'department_id',
        'work_shift_id',
        'working_hour',
        'hour_salary',
        'visitingLecturer',
        'employee_id',
        'grossSalary',
        'gender',
        'machine_status',
        'specialization_subject',
        'account_number',
        'bank_name',
        'employee_profile',
        'personal_email_address',
        'leaving_date',
        'reason_leaving',
        'provident_fund'
    ];

    public function employee_type()
    {
        return $this->belongsTo(EmployeeTypes::class, 'type');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'employee_id');

    }

    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }


    public function Otherbranch()
    {
        return $this->hasMany(OtherBranch::class, 'employee_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function educations()
    {
        return $this->hasMany(EmployeeEducation::class, 'hrm_employee_id');
    }

    public function workExperince()
    {
        return $this->hasMany(EmployeeWorkExperience::class, 'hrm_employee_id');
    }

    public function employeeFamily()
    {
        return $this->hasMany(EmployeeFamily::class, 'hrm_employee_id');
    }

    public function workShifts()
    {
        return $this->belongsTo(WorkShift::class, 'work_shift_id', 'id');
    }

    public function employeeAllowance()
    {
        return $this->hasMany(EmployeeAllowance::class, 'employee_id');
    }

    public function employeeWelfare()
    {
        return $this->hasOne(EmployeeWelfare::class, 'employee_id');
    }

    public function providentFund()
    {
        return $this->belongsTo(ProfitFund::class, 'employee_id');
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class, 'employee_id');
    }

    public function scopeActive($query){
        return $query->where('leaving_date', null);
    }

    public function childrens(){
        return $this->hasMany(EmployeeChild::class,'employee_id');
    }
    
}
