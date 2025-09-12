<?php

namespace App\Models\Fee;

use App\Models\Academic\AcademicClass;
use App\Models\Admin\AssignTool;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Admin\Course;
use App\Models\Admin\FeeStructureValue;
use App\Models\Admin\Session;
use App\Models\Admin\StudentDataBank;
use App\Models\Admin\StudentFeeData;
use App\Models\Student\Students;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentFee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'students_fee';

    protected $fillable = [
        'session_id',
        'company_id',
        'branch_id',
        'class_id',
        'student_id',
        'generated_month',
        'total_monthly_amount',
        'total_amount_after_discount',
        'fee_factor_id',
    ];


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function AcademicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function student_fee_data()
    {
        return $this->hasMany(StudentFeeData::class, 'students_fee_id');
    }


}
