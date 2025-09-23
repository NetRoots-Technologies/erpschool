<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetExpense extends Model
{
    use HasFactory;

     protected $fillable = [
        'budget_id', 'category_id', 'subcategory_id',
        'expense_date', 'expense_amount', 'description'
    ];

    public function budget() {
        return $this->belongsTo(Budget::class);
    }

    public function category() {
        return $this->belongsTo(BCategory::class);
    }

    public function subcategory() {
        return $this->belongsTo(BCategory::class);
    }
}
