<?php

namespace App\Models\Academic;

use App\Models\Admin\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicClass extends Model
{
    use HasFactory;


    protected $table = 'classes';
    protected $fillable = ['name', 'school_type_id', 'session_id', 'branch_id', 'company_id'];

    public function school()
    {
        return $this->belongsTo(SchoolType::class, 'school_type_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
