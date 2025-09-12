<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerAd extends Model
{
    use HasFactory;
    protected $fillable = [
        'banner_title',
        'banner_description',
    ];
    protected $table = 'bannerads';
}
