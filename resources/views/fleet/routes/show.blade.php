@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Route Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.routes.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Routes
                        </a>
                        <a href="{{ route('fleet.routes.edit', $route->id) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Route Name:</th>
                                    <td>{{ $route->route_name }}</td>
                                </tr>
                                <tr>
                                    <th>Vehicle:</th>
                                    <td>
                                        @if($route->vehicle)
                                            <strong>{{ $route->vehicle->vehicle_number }}</strong><br>
                                            <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $route->vehicle->vehicle_type)) }}</small>
                                        @else
                                            <span class="text-muted">Not Assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Start Point:</th>
                                    <td>{{ $route->start_point }}</td>
                                </tr>
                                <tr>
                                    <th>End Point:</th>
                                    <td>{{ $route->end_point }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Total Distance:</th>
                                    <td>{{ $route->total_distance }} km</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($route->status == 'active')
                                            <span class="badge badge-success" style="color: #000 !important;">Active</span>
                                        @else
                                            <span class="badge badge-secondary" style="color: #000 !important;">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Company:</th>
                                    <td>{{ $route->company ? $route->company->name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Branch:</th>
                                    <td>{{ $route->branch ? $route->branch->name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $route->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($route->notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Notes:</h5>
                            <p class="text-muted">{{ $route->notes }}</p>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
