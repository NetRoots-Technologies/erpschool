<?php

namespace App\Models\HR;

use App\Models\Admin\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotta extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'leave_type',
        'permitted_days',
    ];

    protected $table = 'hr_quota_settings';

    public function department()
    {
        return $this->hasMany(QuotaDepartment::class, 'hr_quota_settings_id');
    }
}
