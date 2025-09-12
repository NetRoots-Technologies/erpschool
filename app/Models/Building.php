<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Branches;
use App\Models\Admin\Company;

class Building extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'company_id' ,'name', 'code', 'area', 'parent_id', 'description' , 'image'];

    public function branch() {
        return $this->belongsTo(Branches::class);
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }


}
