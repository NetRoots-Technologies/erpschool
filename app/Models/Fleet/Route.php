<?php

namespace App\Models\Fleet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fleet_routes';

    protected $fillable = [
        'route_name',
        'vehicle_id',
        'start_point',
        'end_point',
        'total_distance',
        'status',
        'company_id',
        'branch_id',
        'notes'
    ];

    protected $casts = [
        'total_distance' => 'decimal:2',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Admin\Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Admin\Branch::class, 'branch_id');
    }

    public function routePoints()
    {
        return $this->hasMany(RoutePoint::class, 'route_id');
    }

    public function transportations()
    {
        return $this->hasMany(\App\Models\Student\StudentTransport::class, 'route_id');
    }
}
