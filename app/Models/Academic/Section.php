<?php

namespace App\Models\Academic;

use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Student\AcademicSession;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';

    protected $fillable = ['name', 'session_id', 'class_id', 'status', 'branch_id', 'company_id', 'active_session_id'];

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }


    public function session()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
