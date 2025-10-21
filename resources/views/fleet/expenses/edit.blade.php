@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Expense</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.expenses.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Expenses
                        </a>
                        <a href="{{ route('fleet.expenses.show', $expense->id) }}" class="btn btn-info btn-sm">
                            <i class="fa fa-eye"></i> View
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('fleet.expenses.update', $expense->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_id">Vehicle <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('vehicle_id') is-invalid @enderror" 
                                            id="vehicle_id" name="vehicle_id" required>
                                        <option value="">Select Vehicle</option>
                                        @foreach(\App\Models\Fleet\Vehicle::where('status', 'active')->get() as $vehicle)
                                            <option value="{{ $vehicle->id }}" 
                                                    {{ old('vehicle_id', $expense->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
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
                                    <select class="form-control select2 @error('driver_id') is-invalid @enderror" 
                                            id="driver_id" name="driver_id">
                                        <option value="">Select Driver</option>
                                        @foreach(\App\Models\Fleet\Driver::where('status', 'active')->get() as $driver)
                                            <option value="{{ $driver->id }}" 
                                                    {{ old('driver_id', $expense->driver_id) == $driver->id ? 'selected' : '' }}>
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
                                    <label for="expense_type">Expense Type <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('expense_type') is-invalid @enderror" 
                                            id="expense_type" name="expense_type" required>
                                        <option value="">Select Type</option>
                                        <option value="fuel" {{ old('expense_type', $expense->expense_type) == 'fuel' ? 'selected' : '' }}>Fuel</option>
                                        <option value="maintenance" {{ old('expense_type', $expense->expense_type) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="toll" {{ old('expense_type', $expense->expense_type) == 'toll' ? 'selected' : '' }}>Toll</option>
                                        <option value="parking" {{ old('expense_type', $expense->expense_type) == 'parking' ? 'selected' : '' }}>Parking</option>
                                        <option value="repair" {{ old('expense_type', $expense->expense_type) == 'repair' ? 'selected' : '' }}>Repair</option>
                                        <option value="other" {{ old('expense_type', $expense->expense_type) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('expense_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expense_date">Expense Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('expense_date') is-invalid @enderror" 
                                           id="expense_date" name="expense_date" 
                                           value="{{ old('expense_date', $expense->expense_date ? $expense->expense_date->format('Y-m-d') : '') }}" required>
                                    @error('expense_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" 
                                           value="{{ old('amount', $expense->amount) }}" min="0" step="0.01" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="receipt_number">Receipt Number</label>
                                    <input type="text" class="form-control @error('receipt_number') is-invalid @enderror" 
                                           id="receipt_number" name="receipt_number" 
                                           value="{{ old('receipt_number', $expense->receipt_number) }}">
                                    @error('receipt_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="pending" {{ old('status', $expense->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('status', $expense->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ old('status', $expense->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                                              id="description" name="description" rows="3">{{ old('description', $expense->description) }}</textarea>
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
                                              id="notes" name="notes" rows="3">{{ old('notes', $expense->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Expense
                            </button>
                            <a href="{{ route('fleet.expenses.index') }}" class="btn btn-secondary">
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