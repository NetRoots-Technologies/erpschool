<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Group; 
use App\Models\Category;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventorys'; // specify the table name

    protected $fillable = [
        'account_type_id',
        'detail_type_id',
        'category_id',
        'inventory_type',
        'item_name',
        'additional_description',
        'remarks',
        'asset_account_id',        // <-- Corrected: Matches your column name
        'sale_account_id',         // <-- Corrected: Matches your column name
        'cost_account_id',         // <-- Corrected: Matches your column name
        'commission_account',      // Matches your column name
        'payable_account',         // Matches your column name
        'sale_type',
        'sales_tax_percentage',
        'further_sale_tax',
        'hs_code',
        'hs_code_description',
        'packing_unit',
        'packing_unit_type',
        'base_sale_unit',
        'base_sale_unit_type',
        'qty_in_hand',
        'as_on_date',
        'as_of_date',
        'cost_price',
        'sale_price',
        'min_sale_price',
        'image',
        'reorder_level',
        'margin_percentage',
        'commission_percentage',
        'due_expiry_date',
    ];

    // Relationships
    public function accountType()
    {
        return $this->belongsTo(Group::class, 'account_type_id');
    }

    public function detailType()
    {
        return $this->belongsTo(Group::class, 'detail_type_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function inventoryCategorys() // Note the 's'
    {
        return $this->belongsTo(inventoryCategorys::class,'category_id'); // Note the lowercase 'i'
    }
}