@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Fuel Record</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.fuel.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Fuel Records
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('fleet.fuel.store') }}" method="POST">
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
                                    <label for="fuel_date">Fuel Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('fuel_date') is-invalid @enderror" 
                                           id="fuel_date" name="fuel_date" 
                                           value="{{ old('fuel_date') }}" required>
                                    @error('fuel_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fuel_type">Fuel Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('fuel_type') is-invalid @enderror" 
                                            id="fuel_type" name="fuel_type" required>
                                        <option value="">Select Fuel Type</option>
                                        <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                        <option value="petrol" {{ old('fuel_type') == 'petrol' ? 'selected' : '' }}>Petrol</option>
                                        <option value="cng" {{ old('fuel_type') == 'cng' ? 'selected' : '' }}>CNG</option>
                                        <option value="lpg" {{ old('fuel_type') == 'lpg' ? 'selected' : '' }}>LPG</option>
                                    </select>
                                    @error('fuel_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">Quantity (Liters) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                           id="quantity" name="quantity" 
                                           value="{{ old('quantity', 0) }}" min="0" step="0.01" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rate_per_liter">Rate per Liter <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('rate_per_liter') is-invalid @enderror" 
                                           id="rate_per_liter" name="rate_per_liter" 
                                           value="{{ old('rate_per_liter', 0) }}" min="0" step="0.01" required>
                                    @error('rate_per_liter')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_cost">Total Cost <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('total_cost') is-invalid @enderror" 
                                           id="total_cost"
                                           value="{{ old('total_cost', 0) }}" min="0" step="0.01" required>
                                    @error('total_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <input type="hidden" class="total_cost" name="total_cost">
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fuel_station">Fuel Station</label>
                                    <input type="text" class="form-control @error('fuel_station') is-invalid @enderror" 
                                           id="fuel_station" name="fuel_station" 
                                           value="{{ old('fuel_station') }}">
                                    @error('fuel_station')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="odometer_reading">Odometer Reading</label>
                                    <input type="number" class="form-control @error('odometer_reading') is-invalid @enderror" 
                                           id="odometer_reading" name="odometer_reading" 
                                           value="{{ old('odometer_reading', 0) }}" min="0">
                                    @error('odometer_reading')
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
                                <i class="fa fa-save"></i> Save Fuel Record
                            </button>
                            <a href="{{ route('fleet.fuel.index') }}" class="btn btn-secondary">
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

@section('js')
<script>
$(document).ready(function () {

    $(document).on('keyup change', '#quantity, #rate_per_liter', function () {
        const qty = parseFloat($('#quantity').val()) || 0;
        const rate = parseFloat($('#rate_per_liter').val()) || 0;
        const cost = qty * rate;
        $('#total_cost').val(cost.toFixed(2)).prop('disabled', true);
        $('.total_cost').val(cost.toFixed(2));
        
    });

});
</script>
@endsection

