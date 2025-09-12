<?php

namespace App\Models;

use App\Models\HRM\Employees;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $fillable = [
        'name',
        'email',
        'active',
        'status',
        'password',
        'employee_id',
        'branch_id',
        'department_id',
        'company_id',
        'role_id',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function rules()
    {
        return [
            'email' => 'required|email|unique:users,email,' . $this->user->id,
        ];
    }

    public function employee(){
        return $this->belongsTo(Employees::class, 'employee_id');
    }

    public function approvalauthorities(){
        return $this->hasMany(ApprovalAuthority::class);
    }
}
