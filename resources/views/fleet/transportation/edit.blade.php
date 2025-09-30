@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Transportation Fee</h3>
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
                                    <label for="transport_fee">Transportation Fee (Monthly) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('transport_fee') is-invalid @enderror" 
                                           id="transport_fee" name="transport_fee" 
                                           value="{{ old('transport_fee', $student->transport_fee ?? 0) }}" min="0" step="0.01" required>
                                    @error('transport_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Transportation Fee
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