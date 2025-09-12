<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTransport extends Model
{
    use HasFactory;
    protected $table = 'student_transports';

    protected $fillable = ['pickup_dropoff', 'student_id', 'transport_facility', 'transport_email', 'pick_address'];
}
