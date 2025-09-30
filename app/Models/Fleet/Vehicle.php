<?php

namespace App\Models\Fleet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fleet_vehicles';

    protected $fillable = [
        'vehicle_number',
        'vehicle_type',
        'driver_id',
        'capacity',
        'status',
        'company_id',
        'branch_id',
        'notes'
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(\App\Models\Admin\Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Admin\Branch::class, 'branch_id');
    }

    public function routes()
    {
        return $this->hasMany(Route::class, 'vehicle_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function transportations()
    {
        return $this->hasMany(\App\Models\Student\StudentTransport::class, 'vehicle_id');
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(Maintenance::class, 'vehicle_id');
    }

    public function fuelRecords()
    {
        return $this->hasMany(Fuel::class, 'vehicle_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'vehicle_id');
    }
}
