@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fleet Maintenance</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.maintenance.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Add Maintenance Record
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
                                    <th>Maintenance Type</th>
                                    <th>Date</th>
                                    <th>Next Date</th>
                                    <th>Cost</th>
                                    <th>Service Provider</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($maintenances as $maintenance)
                                <tr>
                                    <td>{{ $maintenance->id }}</td>
                                    <td>
                                        @if($maintenance->vehicle)
                                            {{ $maintenance->vehicle->vehicle_number }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($maintenance->driver)
                                            {{ $maintenance->driver->driver_name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info" style="color: #000 !important;">
                                            {{ ucfirst(str_replace('_', ' ', $maintenance->maintenance_type)) }}
                                        </span>
                                    </td>
                                    <td>{{ $maintenance->maintenance_date ? $maintenance->maintenance_date->format('d M Y') : 'N/A' }}</td>
                                    <td>{{ $maintenance->next_maintenance_date ? $maintenance->next_maintenance_date->format('d M Y') : 'N/A' }}</td>
                                    <td>Rs. {{ number_format($maintenance->cost) }}</td>
                                    <td>{{ $maintenance->service_provider ?? 'N/A' }}</td>
                                    <td>
                                        @if($maintenance->status == 'completed')
                                            <span class="badge badge-success" style="color: #000 !important;">Completed</span>
                                        @elseif($maintenance->status == 'pending')
                                            <span class="badge badge-warning" style="color: #000 !important;">Pending</span>
                                        @else
                                            <span class="badge badge-secondary" style="color: #000 !important;">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('fleet.maintenance.show', $maintenance->id) }}" 
                                               class="btn btn-info btn-sm" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('fleet.maintenance.edit', $maintenance->id) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('fleet.maintenance.destroy', $maintenance->id) }}" 
                                                  method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        title="Delete" 
                                                        onclick="return confirm('Are you sure you want to delete this maintenance record?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">No maintenance records found</td>
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
