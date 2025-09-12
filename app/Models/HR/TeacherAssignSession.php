<?php

namespace App\Models\HR;

use App\Models\Admin\Session;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherAssignSession extends Model
{
    use HasFactory, SoftDeletes;


    public function sessions()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }


}
