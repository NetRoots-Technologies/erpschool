<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Departments;

class DepartmentBudget extends Model
{
    use HasFactory;

       protected $guarded = [];

       public function subcategory()
        {
            return $this->belongsTo(BCategory::class, 'sub_category_id');
        }


       public function category(){
        return $this->belongsTo(BCategory::class);
       }

       public function budget(){
        return $this->hasMany(Budget::class , 'id');
       }

       public function budgetDeparmnetWise()
        {
            return $this->belongsTo(Budget::class, 'budget_id', 'id');
        }


}
