<?php

namespace App\Models\Student;

use App\Models\Admin\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSession extends Model
{
    use HasFactory;

    protected $table = 'acadmeic_sessions';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status',
        'branch_id'
    ];
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
