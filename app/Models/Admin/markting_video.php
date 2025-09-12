<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class markting_video extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'video_id',
        'video_link',
        'status',
    ];
    protected $table = 'markting_videos';
}
