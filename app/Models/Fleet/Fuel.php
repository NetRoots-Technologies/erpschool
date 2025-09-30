<?php

namespace App\Models\Fleet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fuel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fleet_fuel';

    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'fuel_date',
        'fuel_type',
        'quantity',
        'rate_per_liter',
        'total_cost',
        'fuel_station',
        'odometer_reading',
        'company_id',
        'branch_id',
        'notes'
    ];

    protected $casts = [
        'fuel_date' => 'date',
        'quantity' => 'decimal:2',
        'rate_per_liter' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'odometer_reading' => 'integer',
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
