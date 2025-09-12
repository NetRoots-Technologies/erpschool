<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Behaviours extends Model
{
    use HasFactory;

    protected $table = "behaviours";

    protected $fillable = [
        "abbrev",
        "key",
        "status",
        "logs"
    ];
}
