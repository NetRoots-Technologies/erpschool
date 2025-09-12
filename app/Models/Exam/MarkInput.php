<?php

namespace App\Models\Exam;

use App\Models\Academic\AcademicClass;
use App\Models\Academic\Section;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Admin\Course;
use App\Models\Student\AcademicSession;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exam\MarkEntry;

class MarkInput extends Model
{
    use HasFactory;
    protected $table = 'mark_inputs';
    protected $fillable = [
        'company_id',
        'branch_id',
        'acadmeic_sessions_id',
        'class_id',
        'section_id',
        'course_id',
        'component_id',
        'sub_component_id',
    ];

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'acadmeic_sessions_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function fetchClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function component()
    {
        return $this->belongsTo(Component::class, 'component_id');
    }

    public function subComponent()
    {
        return $this->belongsTo(SubComponent::class, 'sub_component_id');
    }

    public function mark_entries(){
        return $this->hasMany(MarkEntry::class,'mark_input_id');
    }
}
