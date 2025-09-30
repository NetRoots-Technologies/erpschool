@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Maintenance Record Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.maintenance.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Maintenance
                        </a>
                        <a href="{{ route('fleet.maintenance.edit', $maintenance->id) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Maintenance Type:</th>
                                    <td>
                                        <span class="badge badge-info" style="color: #000 !important;">
                                            {{ ucfirst(str_replace('_', ' ', $maintenance->maintenance_type)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Vehicle:</th>
                                    <td>
                                        @if($maintenance->vehicle)
                                            <strong>{{ $maintenance->vehicle->vehicle_number }}</strong><br>
                                            <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $maintenance->vehicle->vehicle_type)) }}</small>
                                        @else
                                            <span class="text-muted">Not Assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Driver:</th>
                                    <td>
                                        @if($maintenance->driver)
                                            <strong>{{ $maintenance->driver->driver_name }}</strong><br>
                                            <small class="text-muted">{{ $maintenance->driver->driver_phone }}</small>
                                        @else
                                            <span class="text-muted">Not Assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Maintenance Date:</th>
                                    <td>{{ $maintenance->maintenance_date ? $maintenance->maintenance_date->format('d M Y') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Next Maintenance:</th>
                                    <td>{{ $maintenance->next_maintenance_date ? $maintenance->next_maintenance_date->format('d M Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Cost:</th>
                                    <td><strong>Rs. {{ number_format($maintenance->cost) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($maintenance->status == 'completed')
                                            <span class="badge badge-success" style="color: #000 !important;">Completed</span>
                                        @elseif($maintenance->status == 'pending')
                                            <span class="badge badge-warning" style="color: #000 !important;">Pending</span>
                                        @else
                                            <span class="badge badge-secondary" style="color: #000 !important;">Cancelled</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Service Provider:</th>
                                    <td>{{ $maintenance->service_provider ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Service Provider Phone:</th>
                                    <td>{{ $maintenance->service_provider_phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $maintenance->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($maintenance->description)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Description:</h5>
                            <p class="text-muted">{{ $maintenance->description }}</p>
                        </div>
                    </div>
                    @endif

                    @if($maintenance->notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Notes:</h5>
                            <p class="text-muted">{{ $maintenance->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
