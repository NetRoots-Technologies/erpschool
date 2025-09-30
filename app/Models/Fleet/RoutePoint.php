<?php

namespace App\Models\Fleet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoutePoint extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fleet_route_points';

    protected $fillable = [
        'route_id',
        'point_name',
        'point_address',
        'latitude',
        'longitude',
        'sequence_order',
        'distance_from_previous',
        'charges',
        'status',
        'company_id',
        'branch_id',
        'notes'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'distance_from_previous' => 'decimal:2',
        'charges' => 'decimal:2',
        'sequence_order' => 'integer',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Admin\Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Admin\Branch::class, 'branch_id');
    }

    public function transportations()
    {
        return $this->hasMany(\App\Models\Student\StudentTransport::class, 'route_point_id');
    }
}
