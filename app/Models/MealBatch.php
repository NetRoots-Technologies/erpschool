<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\Admin\Branch;
use App\Models\HRM\Employees;
use App\Models\Admin\Department;
use App\Models\StudentBatchProduct;
use App\Models\Academic\AcademicClass;
use App\Models\Academic\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MealBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        "creator_id",
        "branch_id",
        "parent_id",
        "parent_type",
        "section_id",
        "date",
        "product_id",
        "batch_type",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employees::class, 'parent_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function class()
    {
        return $this->belongsTo(AcademicClass::class, 'parent_id');
    }

    public function product()
    {
        return $this->belongsTo(Inventry::class, 'product_id');
    }

    public function mealBatchDetails()
    {
        return $this->hasMany(MealBatchDetail::class, 'batch_id');
    }
}
