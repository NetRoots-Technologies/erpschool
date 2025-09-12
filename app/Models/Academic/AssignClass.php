<?php

namespace App\Models\Academic;

use App\Models\Admin\Branches;
use App\Models\Admin\Company;
use App\Models\Admin\Session;
use App\Models\Student\Students;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignClass extends Model
{
    use HasFactory;
    protected $table = 'assign_class';
    protected $fillable = ['company_id', 'session_id', 'branch_id', 'class_id', 'section_id', 'student_id'];


    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function Session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }
    public function class()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }
}
