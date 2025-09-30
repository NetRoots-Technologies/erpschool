<?php

namespace App\Models\Fleet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maintenance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fleet_maintenance';

    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'maintenance_type',
        'maintenance_date',
        'next_maintenance_date',
        'description',
        'cost',
        'service_provider',
        'service_provider_phone',
        'status',
        'company_id',
        'branch_id',
        'notes'
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Admin\Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Admin\Branch::class, 'branch_id');
    }
}
