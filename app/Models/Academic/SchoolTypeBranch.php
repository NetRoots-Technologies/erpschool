<?php

namespace App\Models\Academic;

use App\Models\Admin\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolTypeBranch extends Model
{
    use HasFactory;

    protected $table = 'branches_schools';

    protected $fillable = ['branch_id', 'school_type_id'];

}
