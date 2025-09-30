@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fleet Fuel Records</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.fuel.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Add Fuel Record
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Fuel Date</th>
                                    <th>Fuel Type</th>
                                    <th>Quantity</th>
                                    <th>Rate/Liter</th>
                                    <th>Total Cost</th>
                                    <th>Fuel Station</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fuels as $fuel)
                                <tr>
                                    <td>{{ $fuel->id }}</td>
                                    <td>
                                        @if($fuel->vehicle)
                                            {{ $fuel->vehicle->vehicle_number }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($fuel->driver)
                                            {{ $fuel->driver->driver_name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $fuel->fuel_date ? $fuel->fuel_date->format('d M Y') : 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-info" style="color: #000 !important;">
                                            {{ ucfirst($fuel->fuel_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $fuel->quantity }} L</td>
                                    <td>Rs. {{ number_format($fuel->rate_per_liter, 2) }}</td>
                                    <td><strong>Rs. {{ number_format($fuel->total_cost) }}</strong></td>
                                    <td>{{ $fuel->fuel_station ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('fleet.fuel.show', $fuel->id) }}" 
                                               class="btn btn-info btn-sm" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('fleet.fuel.edit', $fuel->id) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('fleet.fuel.destroy', $fuel->id) }}" 
                                                  method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        title="Delete" 
                                                        onclick="return confirm('Are you sure you want to delete this fuel record?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">No fuel records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
