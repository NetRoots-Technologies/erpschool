<?php

namespace App\Models;

use App\Models\Product;
use App\Models\MealBatch;
use App\Models\HRM\Employees;
use App\Models\Student\Students;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MealBatchDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        "batch_id",
        "parent_id",
        "parent_type",
        "product_id",
        "assigned",
    ];

    public function foodBatch()
    {
        return $this->belongsTo(MealBatch::class, 'batch_id');
    }

    public function student()
    {
        return $this->belongsTo(Students::class, 'parent_id');
    }

    public function product()
    {
        return $this->belongsTo(Inventry::class, 'product_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'parent_id');
    }
}
