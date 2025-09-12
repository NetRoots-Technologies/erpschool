<?php

namespace App\Models\Exam;

use App\Models\Admin\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id', 'status', 'company_id', 'session_id', 'branch_id', 'subject_id', 'section_id', 'class_id'];

    protected $table = 'components';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function componentData()
    {
        return $this->hasMany(ComponentData::class, 'component_id');
    }

}
