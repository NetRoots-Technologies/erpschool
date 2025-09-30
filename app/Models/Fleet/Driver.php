<?php

namespace App\Models\Fleet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fleet_drivers';

    protected $fillable = [
        'driver_name',
        'driver_phone',
        'driver_cnic',
        'license_number',
        'license_expiry',
        'address',
        'salary',
        'status',
        'company_id',
        'branch_id',
        'notes'
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'salary' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(\App\Models\Admin\Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Admin\Branch::class, 'branch_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'driver_id');
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(Maintenance::class, 'driver_id');
    }
}
