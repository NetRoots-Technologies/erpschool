<?php

namespace App\Models\Admin;

use App\Models\BCategory;
use App\Models\inventory\VendorCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function groups()
    {
        return $this->belongsTo(Groups::class);
    }
    public function states()
    {
        return $this->belongsTo(State::class);
    }
    public function cities()
    {
        return $this->belongsTo(City::class,'city_id');
    }
    public function categories()
    {
        return $this->belongsTo(BCategory::class,'b_category_id');
    }
    public function vendorCategorys()
    {
        return $this->belongsTo(VendorCategory::class,'vendor_category_id');
    }
    public function ledger()
    {
        return $this->morphOne(Ledgers::class, 'sourceable');
    }
}
