<?php

namespace App\Models\Admin;

use App\Models\Category;
use App\Models\HRM\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'category_id' => 'array',
    ];


    protected $table = 'departments';
    protected $fillable = ['category_id','parent_id','name','branch_id','company_id','updated_by',];



    public function branch()
    {

        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function company()
    {

        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employee()
    {
        return $this->hasMany(Employees::class, 'department_id');
    }

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
   
    }

}
