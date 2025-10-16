@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Fleet Vehicles</h3>
                        @if (Gate::allows('vahicals-create'))
                            <div class="card-tools">
                                <a href="{{ route('fleet.vehicles.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Add Vehicle
                                </a>
                            </div>
                        @endif

                    </div>
                    <div class="card-body">
                        @if (session('success'))
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
                                        <th>Vehicle Number</th>
                                        <th>Type</th>
                                        <th>Driver</th>
                                        <th>Capacity</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($vehicles as $vehicle)
                                        <tr>
                                            <td>{{ $vehicle->id }}</td>
                                            <td>{{ $vehicle->vehicle_number }}</td>
                                            <td>
                                                <span class="badge badge-info" style="color: #000 !important;">
                                                    {{ ucfirst(str_replace('_', ' ', $vehicle->vehicle_type)) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($vehicle->driver)
                                                    {{ $vehicle->driver->driver_name }}
                                                @else
                                                    <span class="text-muted">Not Assigned</span>
                                                @endif
                                            </td>
                                            <td>{{ $vehicle->capacity }}</td>
                                            <td>
                                                @if ($vehicle->status == 'active')
                                                    <span class="badge badge-success"
                                                        style="color: #000 !important;">Active</span>
                                                @elseif($vehicle->status == 'maintenance')
                                                    <span class="badge badge-warning"
                                                        style="color: #000 !important;">Maintenance</span>
                                                @else
                                                    <span class="badge badge-secondary"
                                                        style="color: #000 !important;">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if (Gate::allows('vahicals-edit'))
                                                        <a href="{{ route('fleet.vehicles.show', $vehicle->id) }}"
                                                            class="btn btn-info btn-sm" title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    @if (Gate::allows('vahicals-view'))
                                                        <a href="{{ route('fleet.vehicles.edit', $vehicle->id) }}"
                                                            class="btn btn-warning btn-sm" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if (Gate::allows('vahicals-delete'))
                                                        <form action="{{ route('fleet.vehicles.destroy', $vehicle->id) }}"
                                                            method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                title="Delete"
                                                                onclick="return confirm('Are you sure you want to delete this vehicle?')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No vehicles found</td>
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
