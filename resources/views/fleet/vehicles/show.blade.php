@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Vehicle Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.vehicles.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Vehicles
                        </a>
                        <a href="{{ route('fleet.vehicles.edit', $vehicle->id) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Vehicle Number:</th>
                                    <td>{{ $vehicle->vehicle_number }}</td>
                                </tr>
                                <tr>
                                    <th>Vehicle Type:</th>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ ucfirst(str_replace('_', ' ', $vehicle->vehicle_type)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Driver:</th>
                                    <td>
                                        @if($vehicle->driver)
                                            <strong>{{ $vehicle->driver->driver_name }}</strong><br>
                                            <small class="text-muted">{{ $vehicle->driver->driver_phone }}</small>
                                        @else
                                            <span class="text-muted">Not Assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Capacity:</th>
                                    <td>{{ $vehicle->capacity }} passengers</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Status:</th>
                                    <td>
                                        @if($vehicle->status == 'active')
                                            <span class="badge badge-success">Active</span>
                                        @elseif($vehicle->status == 'maintenance')
                                            <span class="badge badge-warning">Maintenance</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Company:</th>
                                    <td>{{ $vehicle->company ? $vehicle->company->name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Branch:</th>
                                    <td>{{ $vehicle->branch ? $vehicle->branch->name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $vehicle->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($vehicle->notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Notes:</h5>
                            <p class="text-muted">{{ $vehicle->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Routes Section -->
                    @if($vehicle->routes->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Assigned Routes:</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Route Name</th>
                                            <th>Start Point</th>
                                            <th>End Point</th>
                                            <th>Distance</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vehicle->routes as $route)
                                        <tr>
                                            <td>{{ $route->route_name }}</td>
                                            <td>{{ $route->start_point }}</td>
                                            <td>{{ $route->end_point }}</td>
                                            <td>{{ $route->total_distance }} km</td>
                                            <td>
                                                @if($route->status == 'active')
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
