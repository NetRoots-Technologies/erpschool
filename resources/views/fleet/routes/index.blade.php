@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Fleet Routes</h3>
                        <div class="card-tools">
                            @if (Gate::allows('routes-create'))
                                <a href="{{ route('fleet.routes.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Add Route
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
                                        <th>Route Name</th>
                                        <th>Vehicle</th>
                                        <th>Start Point</th>
                                        <th>End Point</th>
                                        <th>Distance</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($routes as $route)
                                        <tr>
                                            <td>{{ $route->id }}</td>
                                            <td>{{ $route->route_name }}</td>
                                            <td>
                                                @if ($route->vehicle)
                                                    {{ $route->vehicle->vehicle_number }}
                                                @else
                                                    <span class="text-muted">Not Assigned</span>
                                                @endif
                                            </td>
                                            <td>{{ $route->start_point }}</td>
                                            <td>{{ $route->end_point }}</td>
                                            <td>{{ $route->total_distance }} km</td>
                                            <td>
                                                @if ($route->status == 'active')
                                                    <span class="badge badge-success"
                                                        style="color: #000 !important;">Active</span>
                                                @else
                                                    <span class="badge badge-secondary"
                                                        style="color: #000 !important;">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if (Gate::allows('routes-view'))
                                                        <a href="{{ route('fleet.routes.show', $route->id) }}"
                                                            class="btn btn-info btn-sm" title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    @if (Gate::allows('routes-edit'))
                                                        <a href="{{ route('fleet.routes.edit', $route->id) }}"
                                                            class="btn btn-warning btn-sm" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endif
                                                    @if (Gate::allows('routes-delete'))
                                                        <form action="{{ route('fleet.routes.destroy', $route->id) }}"
                                                            method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                title="Delete"
                                                                onclick="return confirm('Are you sure you want to delete this route?')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No routes found</td>
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
