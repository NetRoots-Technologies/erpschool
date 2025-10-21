@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Route</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.routes.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Routes
                        </a>
                        <a href="{{ route('fleet.routes.show', $route->id) }}" class="btn btn-info btn-sm">
                            <i class="fa fa-eye"></i> View
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('fleet.routes.update', $route->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="route_name">Route Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('route_name') is-invalid @enderror" 
                                           id="route_name" name="route_name" 
                                           value="{{ old('route_name', $route->route_name) }}" required>
                                    @error('route_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_id">Vehicle <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('vehicle_id') is-invalid @enderror" 
                                            id="vehicle_id" name="vehicle_id" required>
                                        <option value="">Select Vehicle</option>
                                        @foreach(\App\Models\Fleet\Vehicle::where('status', 'active')->get() as $vehicle)
                                            <option value="{{ $vehicle->id }}" 
                                                    {{ old('vehicle_id', $route->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                                {{ $vehicle->vehicle_number }} ({{ ucfirst(str_replace('_', ' ', $vehicle->vehicle_type)) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_point">Start Point <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('start_point') is-invalid @enderror" 
                                           id="start_point" name="start_point" 
                                           value="{{ old('start_point', $route->start_point) }}" required>
                                    @error('start_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_point">End Point <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('end_point') is-invalid @enderror" 
                                           id="end_point" name="end_point" 
                                           value="{{ old('end_point', $route->end_point) }}" required>
                                    @error('end_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_distance">Total Distance (km) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('total_distance') is-invalid @enderror" 
                                           id="total_distance" name="total_distance" 
                                           value="{{ old('total_distance', $route->total_distance) }}" min="0" step="0.1" required>
                                    @error('total_distance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status', $route->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $route->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3">{{ old('notes', $route->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Route
                            </button>
                            <a href="{{ route('fleet.routes.index') }}" class="btn btn-secondary">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
