<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalAllowance extends Model
{
    use HasFactory;

    protected $table = "medical_allowances";

    protected $fillable = ['employee_id', 'year', 'month', 'medicalAllowance'];
}
