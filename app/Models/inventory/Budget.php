<?php

namespace App\Models\inventory;

use App\Models\Admin\Department;
use App\Models\BCategory;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function category()
    {
        return $this->belongsTo(BCategory::class, 'b_category_id');
    }
}
