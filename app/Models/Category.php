<?php

namespace App\Models;

use App\Models\inventory\Budget;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['name'];

    public function budget()
    {
        return $this->hasMany(Budget::class,'categories_id');
    }
}
