<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotaDesignation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'quota_designation';

    protected $fillable = ['designation_id', 'hr_quota_settings_id'];

    public function quota()
    {
        return $this->belongsTo(Quotta::class, 'hr_quota_settings_id');
    }
}
