<?php

namespace App\Models\Academic;

use App\Models\Admin\Branches;
use App\Models\Admin\Company;
use App\Models\HR\OtherBranch;
use App\Models\Student\AcademicSession;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeTable extends Model
{
    use HasFactory;

    //use SoftDeletes;

    protected $table = 'timetables';

    protected $fillable = ['session_id', 'start_time', 'end_time', 'company_id', 'branch_id', 'school_id', 'name'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }
    public function school()
    {
        return $this->belongsTo(SchoolType::class, 'school_id');
    }

    public function session()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }



    public function Otherbranch()
    {
        return $this->hasMany(OtherBranch::class, 'employee_id');
    }
}
