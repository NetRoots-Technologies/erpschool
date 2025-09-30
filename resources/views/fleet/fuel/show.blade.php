@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fuel Record Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.fuel.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Fuel Records
                        </a>
                        <a href="{{ route('fleet.fuel.edit', $fuel->id) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Fuel Type:</th>
                                    <td>
                                        <span class="badge badge-info" style="color: #000 !important;">
                                            {{ ucfirst($fuel->fuel_type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Vehicle:</th>
                                    <td>
                                        @if($fuel->vehicle)
                                            <strong>{{ $fuel->vehicle->vehicle_number }}</strong><br>
                                            <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $fuel->vehicle->vehicle_type)) }}</small>
                                        @else
                                            <span class="text-muted">Not Assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Driver:</th>
                                    <td>
                                        @if($fuel->driver)
                                            <strong>{{ $fuel->driver->driver_name }}</strong><br>
                                            <small class="text-muted">{{ $fuel->driver->driver_phone }}</small>
                                        @else
                                            <span class="text-muted">Not Assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fuel Date:</th>
                                    <td>{{ $fuel->fuel_date ? $fuel->fuel_date->format('d M Y') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Quantity:</th>
                                    <td><strong>{{ $fuel->quantity }} Liters</strong></td>
                                </tr>
                                <tr>
                                    <th>Rate per Liter:</th>
                                    <td><strong>Rs. {{ number_format($fuel->rate_per_liter, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Total Cost:</th>
                                    <td><strong>Rs. {{ number_format($fuel->total_cost) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Fuel Station:</th>
                                    <td>{{ $fuel->fuel_station ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Odometer Reading:</th>
                                    <td>{{ $fuel->odometer_reading ? number_format($fuel->odometer_reading) . ' km' : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $fuel->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($fuel->notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Notes:</h5>
                            <p class="text-muted">{{ $fuel->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
