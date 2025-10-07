<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestApproval extends Model
{
    use HasFactory;

    protected $table = 'request_apprvals';

    protected $fillable = [
        'request_id',
        'approver_id',
        'approval_status',
        'approval_level',
        'approval_date',
        'done_status',
        'remarks',
    ];

   
}
