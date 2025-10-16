@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Fleet Drivers</h3>
                        <div class="card-tools">
                            @if (Gate::allows('drivers-create'))
                                <a href="{{ route('fleet.drivers.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Add Driver
                                </a>
                            @endif
                        </div>
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
                                        <th>Driver Name</th>
                                        <th>Phone</th>
                                        <th>CNIC</th>
                                        <th>License Number</th>
                                        <th>License Expiry</th>
                                        <th>Salary</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($drivers as $driver)
                                        <tr>
                                            <td>{{ $driver->id }}</td>
                                            <td>{{ $driver->driver_name }}</td>
                                            <td>{{ $driver->driver_phone }}</td>
                                            <td>{{ $driver->driver_cnic }}</td>
                                            <td>{{ $driver->license_number }}</td>
                                            <td>{{ $driver->license_expiry ? $driver->license_expiry->format('d M Y') : 'N/A' }}
                                            </td>
                                            <td>Rs. {{ number_format($driver->salary) }}</td>
                                            <td>
                                                @if ($driver->status == 'active')
                                                    <span class="badge badge-success"
                                                        style="color: #000 !important;">Active</span>
                                                @else
                                                    <span class="badge badge-secondary"
                                                        style="color: #000 !important;">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if (Gate::allows('drivers-view'))
                                                        <a href="{{ route('fleet.drivers.show', $driver->id) }}"
                                                            class="btn btn-info btn-sm" title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    @if (Gate::allows('drivers-edit'))
                                                        <a href="{{ route('fleet.drivers.edit', $driver->id) }}"
                                                            class="btn btn-warning btn-sm" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if (Gate::allows('drivers-delete'))
                                                        <form action="{{ route('fleet.drivers.destroy', $driver->id) }}"
                                                            method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                title="Delete"
                                                                onclick="return confirm('Are you sure you want to delete this driver?')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No drivers found</td>
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
