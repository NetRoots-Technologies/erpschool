<?php

namespace App\Models\inventory;

use App\Models\Admin\inventory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class InventoryCategory extends Model
{


     use HasFactory;
    protected $table = 'inventory_categorys';
       protected $fillable = [
        'name',
        'code',
        'level',
        'parent_id',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];


      public function category() // Use this one, it's cleaner
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id'); // Corrected class name
    }

        public function inventorys()
    {
        return $this->hasMany(inventory::class, 'category_id');
    }

      public function recursiveChildren()
    {
        return $this->hasMany(InventoryCategory::class, 'parent_id')->with('recursiveChildren','inventorys');
    }

    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

       // Parent category (one level up)
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // Child categories (one level down)
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
