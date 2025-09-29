<?php

namespace App\Models;

use App\Models\Admin\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

   protected $guarded = [];

    protected $table = "sub_budgets";
    
    public function details() {
        return $this->hasMany(BudgetDetail::class);
    }

    
    public function departmentBudgets()
    {
        return $this->hasMany(DepartmentBudget::class, 'budget_id', 'id');
    }


}
