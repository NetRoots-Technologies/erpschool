<?php

namespace App\Models\Academic;

use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolType extends Model
{
    use HasFactory;

    protected $table = 'school_types';
    //,'company_id','branch_id'
    protected $fillable = ['company_id','branch_id','name'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
