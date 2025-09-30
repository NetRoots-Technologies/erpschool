@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Transportation Assignment</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.transportation.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Transportation
                        </a>
                        <a href="{{ route('fleet.transportation.show', $student->id) }}" class="btn btn-info btn-sm">
                            <i class="fa fa-eye"></i> View
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('fleet.transportation.update', $student->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id">Student</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }})" 
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_id">Vehicle <span class="text-danger">*</span></label>
                                    <select class="form-control @error('vehicle_id') is-invalid @enderror" 
                                            id="vehicle_id" name="vehicle_id" required>
                                        <option value="">Select Vehicle</option>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" 
                                                    {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
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
                                    <label for="route_id">Route <span class="text-danger">*</span></label>
                                    <select class="form-control @error('route_id') is-invalid @enderror" 
                                            id="route_id" name="route_id" required>
                                        <option value="">Select Route</option>
                                        @foreach($routes as $route)
                                            <option value="{{ $route->id }}" 
                                                    {{ old('route_id') == $route->id ? 'selected' : '' }}>
                                                {{ $route->route_name }} ({{ $route->start_point }} - {{ $route->end_point }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('route_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pickup_point">Pickup Point <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('pickup_point') is-invalid @enderror" 
                                           id="pickup_point" name="pickup_point" 
                                           value="{{ old('pickup_point') }}" required>
                                    @error('pickup_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dropoff_point">Drop-off Point <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('dropoff_point') is-invalid @enderror" 
                                           id="dropoff_point" name="dropoff_point" 
                                           value="{{ old('dropoff_point') }}" required>
                                    @error('dropoff_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="monthly_charges">Monthly Charges <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('monthly_charges') is-invalid @enderror" 
                                           id="monthly_charges" name="monthly_charges" 
                                           value="{{ old('monthly_charges', 0) }}" min="0" step="0.01" required>
                                    @error('monthly_charges')
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
                                              id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Transportation
                            </button>
                            <a href="{{ route('fleet.transportation.index') }}" class="btn btn-secondary">
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
