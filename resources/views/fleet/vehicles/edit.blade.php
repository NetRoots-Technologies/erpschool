@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Vehicle</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.vehicles.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Vehicles
                        </a>
                        <a href="{{ route('fleet.vehicles.show', $vehicle->id) }}" class="btn btn-info btn-sm">
                            <i class="fa fa-eye"></i> View
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('fleet.vehicles.update', $vehicle->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number">Vehicle Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror" 
                                           id="vehicle_number" name="vehicle_number" 
                                           value="{{ old('vehicle_number', $vehicle->vehicle_number) }}" required>
                                    @error('vehicle_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_type">Vehicle Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('vehicle_type') is-invalid @enderror" 
                                            id="vehicle_type" name="vehicle_type" required>
                                        <option value="">Select Type</option>
                                        <option value="bus" {{ old('vehicle_type', $vehicle->vehicle_type) == 'bus' ? 'selected' : '' }}>Bus</option>
                                        <option value="van" {{ old('vehicle_type', $vehicle->vehicle_type) == 'van' ? 'selected' : '' }}>Van</option>
                                        <option value="car" {{ old('vehicle_type', $vehicle->vehicle_type) == 'car' ? 'selected' : '' }}>Car</option>
                                        <option value="mini_bus" {{ old('vehicle_type', $vehicle->vehicle_type) == 'mini_bus' ? 'selected' : '' }}>Mini Bus</option>
                                    </select>
                                    @error('vehicle_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="driver_id">Driver</label>
                                    <select class="form-control @error('driver_id') is-invalid @enderror" 
                                            id="driver_id" name="driver_id">
                                        <option value="">Select Driver</option>
                                        @foreach(\App\Models\Fleet\Driver::where('status', 'active')->get() as $driver)
                                            <option value="{{ $driver->id }}" 
                                                    {{ old('driver_id', $vehicle->driver_id) == $driver->id ? 'selected' : '' }}>
                                                {{ $driver->driver_name }} ({{ $driver->driver_phone }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('driver_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="capacity">Capacity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                           id="capacity" name="capacity" 
                                           value="{{ old('capacity', $vehicle->capacity) }}" min="0" required>
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status', $vehicle->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $vehicle->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="maintenance" {{ old('status', $vehicle->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
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
                                              id="notes" name="notes" rows="3">{{ old('notes', $vehicle->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Vehicle
                            </button>
                            <a href="{{ route('fleet.vehicles.index') }}" class="btn btn-secondary">
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
