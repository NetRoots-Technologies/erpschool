@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Maintenance Record</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.maintenance.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Maintenance
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('fleet.maintenance.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_id">Vehicle <span class="text-danger">*</span></label>
                                    <select class="form-control @error('vehicle_id') is-invalid @enderror" 
                                            id="vehicle_id" name="vehicle_id" required>
                                        <option value="">Select Vehicle</option>
                                        @foreach(\App\Models\Fleet\Vehicle::where('status', 'active')->get() as $vehicle)
                                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                                {{ $vehicle->vehicle_number }} ({{ ucfirst(str_replace('_', ' ', $vehicle->vehicle_type)) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="driver_id">Driver</label>
                                    <select class="form-control @error('driver_id') is-invalid @enderror" 
                                            id="driver_id" name="driver_id">
                                        <option value="">Select Driver</option>
                                        @foreach(\App\Models\Fleet\Driver::where('status', 'active')->get() as $driver)
                                            <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                                {{ $driver->driver_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('driver_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="maintenance_type">Maintenance Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('maintenance_type') is-invalid @enderror" 
                                            id="maintenance_type" name="maintenance_type" required>
                                        <option value="">Select Type</option>
                                        <option value="regular" {{ old('maintenance_type') == 'regular' ? 'selected' : '' }}>Regular</option>
                                        <option value="scheduled" {{ old('maintenance_type') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="emergency" {{ old('maintenance_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                        <option value="repair" {{ old('maintenance_type') == 'repair' ? 'selected' : '' }}>Repair</option>
                                    </select>
                                    @error('maintenance_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="maintenance_date">Maintenance Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('maintenance_date') is-invalid @enderror" 
                                           id="maintenance_date" name="maintenance_date" 
                                           value="{{ old('maintenance_date') }}" required>
                                    @error('maintenance_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="next_maintenance_date">Next Maintenance Date</label>
                                    <input type="date" class="form-control @error('next_maintenance_date') is-invalid @enderror" 
                                           id="next_maintenance_date" name="next_maintenance_date" 
                                           value="{{ old('next_maintenance_date') }}">
                                    @error('next_maintenance_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cost">Cost <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('cost') is-invalid @enderror" 
                                           id="cost" name="cost" 
                                           value="{{ old('cost', 0) }}" min="0" step="0.01" required>
                                    @error('cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="service_provider">Service Provider</label>
                                    <input type="text" class="form-control @error('service_provider') is-invalid @enderror" 
                                           id="service_provider" name="service_provider" 
                                           value="{{ old('service_provider') }}">
                                    @error('service_provider')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="service_provider_phone">Service Provider Phone</label>
                                    <input type="text" class="form-control @error('service_provider_phone') is-invalid @enderror" 
                                           id="service_provider_phone" name="service_provider_phone" 
                                           value="{{ old('service_provider_phone') }}">
                                    @error('service_provider_phone')
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
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
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
                                <i class="fa fa-save"></i> Save Maintenance Record
                            </button>
                            <a href="{{ route('fleet.maintenance.index') }}" class="btn btn-secondary">
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
