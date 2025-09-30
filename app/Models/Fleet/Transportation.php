<?php

namespace App\Models\Fleet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transportation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fleet_transportations';

    protected $fillable = [
        'student_id',
        'vehicle_id',
        'route_id',
        'route_point_id',
        'pickup_point',
        'dropoff_point',
        'monthly_charges',
        'status',
        'start_date',
        'end_date',
        'company_id',
        'branch_id',
        'notes'
    ];

    protected $casts = [
        'monthly_charges' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Models\Student\Students::class, 'student_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function routePoint()
    {
        return $this->belongsTo(RoutePoint::class, 'route_point_id');
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
