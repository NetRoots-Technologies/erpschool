<?php

namespace App\Models\Exam;

use App\Models\Admin\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillGroup extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'branch_id', 'skill_group', 'sort_skill', 'user_id', 'active'];

    protected $table = 'skill_groups';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
