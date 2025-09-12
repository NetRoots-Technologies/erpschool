<?php

namespace App\Models\Admin;

use App\Models\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountHead extends Model
{
    use HasFactory;

    protected $table = 'account_heads';

    protected $fillable = ['name'];

    public function groups()
    {
        return $this->hasMany(Group::class, 'account_type_id', 'id')->where('parent_type', self::class);
    }
}
