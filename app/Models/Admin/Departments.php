<?php

namespace App\Models\Admin;

use App\Models\inventory\Budget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Departments extends Model
{
    use SoftDeletes;
    //
    protected $fillable = ['name', 'status', 'company_id'];

    protected $table = 'departments';

    static public function pluckActiveOnly()
    {
        return self::where(['status' => 1])->OrderBy('name', 'asc')->pluck('name', 'id');
    }
    static public function getActiveOnly()
    {
        return self::where(['status' => 1])->OrderBy('name', 'asc')->get();
    }
    public function memos()
    {
        return $this->belongsToMany('App\Models\HRM\Demo', 'memo_department', 'memo_id', 'department_id');
    }

    public function employees()
    {
        return $this->hasMany('App\Models\HRM\Employees', 'department_id');
    }
    static public function getNameByDepartmentId($dept_id)
    {
        return self::where('id', $dept_id)->get()->pluck('name')->first();
    }

    public function job_requisition()
    {
        return $this->belongsTo('App\Models\HRM\JobRequisition', 'department_id');
    }
    public function branchname()
    {
        return $this->belongsTo('App\Models\HRM\OrganizationLocations', 'branch');
    }
    public function TrainingAndProduction()
    {
        return $this->hasOne('App\Models\HRM\TrainingAndProduction');
    }

    public function budget()
    {
        return $this->hasMany(Budget::class,'department_id');
    }

}
