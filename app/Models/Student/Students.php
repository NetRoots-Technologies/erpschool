<?php

namespace App\Models\Student;

use App\Models\Academic\AcademicClass;
use App\Models\Academic\Section;
use App\Models\Admin\Branch;
use App\Models\Admin\Course;
use App\Models\Admin\CourseType;
use App\Models\Admin\Session;
use App\Models\HR\Agent;
use App\Models\Exam\MarkEntry;
use App\Models\Student\AcademicSession;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Admin\Company;
use App\Models\ProgressReportRemark;

class Students extends Model
{
    use HasFactory, SoftDeletes;


    protected $tables = ['students'];
    // protected $appends = ['name_with_id'];

    protected $fillable = [
        'leave_reason',
        'approved_by',
        'leave_date',
        'student_id',
        'second_language',
        'first_language',
        'native_language',
        'student_email',
        'landline',
        'cell_no',
        'country',
        'city',
        'student_permanent_address',
        'student_current_address',
        'student_dob',
        'gender',
        'guardian_name',
        'guardian_cnic',
        'father_name',
        'father_cnic',
        'last_name',
        'first_name',
        'special_child',
        'is_guardian',
        'special_needs',
        'admission_date',
        'campus',
        'admission_class',
        'class_id',
        'section_id',
        'branch_id',
        'session_id',
        'company_id',
        'meal_option',
        'easy_urdu',
        'status',
        'is_active',
        'transport_required'
    ];

    public function getfullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }


    public function AcademicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function student_schools()
    {
        return $this->hasOne(StudentPerviousSchool::class, 'student_id');
    }

    public function student_emergency_contacts()
    {
        return $this->hasMany(StudentEmergencyContact::class, 'student_id');
    }

    // Transportation removed - using simple checkbox approach
    public function studentPictures()
    {
        return $this->hasOne(StudentPictures::class, 'student_id');
    }

    public function attendanceData()
    {
        return $this->hasMany(Students::class, 'student_id');

    }
    public function student_siblings()
    {
        return $this->hasMany(StudentSibling::class, 'student_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function mark_entries()
    {
        return $this->hasMany(MarkEntry::class, 'student_id');
    }

    public function class(){ 
        return $this->belongsTo(AcademicClass::class, 'class_id'); 
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }

    public function transportations()
    {
        return $this->hasMany(\App\Models\Fleet\Transportation::class, 'student_id');
    }
   
     public function remarks()
    {
        return $this->hasOne(ProgressReportRemark::class, 'student_id');
    }

     public function fee_Structures()
    {
        return $this->belongsTo(\App\Models\Fee\FeeStructure::class, 'student_id');
    }
    public function approvedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by', 'id');
    }
}
