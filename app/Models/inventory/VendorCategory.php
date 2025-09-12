<?php

namespace App\Models\inventory;

use App\Models\Admin\Vendor;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
    use HasFactory;
    protected $table = 'vendor_categorys';
    protected $guarded = [];

    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'vendor_category_id');
    }

    public function recursiveChildren()
    {
        return $this->hasMany(VendorCategory::class, 'parent_id')->with(['recursiveChildren', 'vendors']);
    }


    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }


}
