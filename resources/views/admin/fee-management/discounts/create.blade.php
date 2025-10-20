@extends('admin.layouts.main')

@section('title', 'Create Fee Discount')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Create Fee Discount</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.discounts') }}">Discounts</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Discount Information</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.fee-management.discounts.store') }}" method="POST" id="discountForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
                                    <select class="form-control @error('student_id') is-invalid @enderror" 
                                            id="student_id" name="student_id" required>
                                        <option value="">Select Student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}">
                                                {{ $student->fullname }} ({{ $student->AcademicClass->name ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id" class="form-label">Fee Category <span class="text-danger">*</span></label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('discount_type') is-invalid @enderror" 
                                            id="discount_type" name="discount_type" required>
                                        <option value="">Select Type</option>
                                        <option value="percentage">Percentage</option>
                                        <option value="fixed">Fixed Amount</option>
                                    </select>
                                    @error('discount_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_value" class="form-label">Discount Value <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('discount_value') is-invalid @enderror" 
                                           id="discount_value" name="discount_value" 
                                           min="0" step="0.01" required>
                                    <small class="form-text text-muted" id="discount_help">
                                        Enter percentage (1-100) or amount in rupees
                                    </small>
                                    @error('discount_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="reason" class="form-label">Reason for Discount</label>
                                    <textarea class="form-control @error('reason') is-invalid @enderror" 
                                              id="reason" name="reason" rows="3" 
                                              placeholder="Enter reason for providing discount">{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="show_on_voucher" name="show_on_voucher" value="1" {{ old('show_on_voucher') ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_on_voucher">Show on Student Voucher</label>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="valid_from_month" class="form-label">Valid From Month <span class="text-danger">*</span></label>
                                    <input type="month" class="form-control @error('valid_from_month') is-invalid @enderror" 
                                           id="valid_from_month" name="valid_from_month" 
                                           value="{{ old('valid_from_month') }}" required>
                                    <small class="form-text text-muted">Select start month for discount validity</small>
                                    @error('valid_from_month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="valid_to_month" class="form-label">Valid To Month <span class="text-danger">*</span></label>
                                    <input type="month" class="form-control @error('valid_to_month') is-invalid @enderror" 
                                           id="valid_to_month" name="valid_to_month" 
                                           value="{{ old('valid_to_month') }}" required>
                                    <small class="form-text text-muted">Select end month for discount validity</small>
                                    @error('valid_to_month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Create Discount
                                    </button>
                                    <a href="{{ route('admin.fee-management.discounts') }}" class="btn btn-secondary">
                                        <i class="fa fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Discount Guidelines</h3>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item">
                            <strong>Percentage Discount:</strong> Enter value between 1-100
                        </div>
                        <div class="list-group-item">
                            <strong>Fixed Amount:</strong> Enter amount in rupees
                        </div>
                        <div class="list-group-item">
                            <strong>Individual Discount:</strong> Applied to specific student
                        </div>
                        <div class="list-group-item">
                            <strong>Bulk Discount:</strong> Applied to multiple students
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6>Important Notes:</h6>
                        <ul class="list-unstyled">
                            <li>• Discounts are applied during fee collection</li>
                            <li>• Percentage discounts are calculated on total amount</li>
                            <li>• Fixed amount discounts are deducted directly</li>
                            <li>• Inactive discounts are not applied</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Update help text based on discount type
        $('#discount_type').change(function() {
            const type = $(this).val();
            const helpText = $('#discount_help');
            
            if (type === 'percentage') {
                helpText.text('Enter percentage value (1-100)');
                $('#discount_value').attr('max', '100');
            } else if (type === 'fixed') {
                helpText.text('Enter amount in rupees');
                $('#discount_value').removeAttr('max');
            } else {
                helpText.text('Enter percentage (1-100) or amount in rupees');
            }
        });

        // Form validation
        $('#discountForm').on('submit', function(e) {
            const discountType = $('#discount_type').val();
            const discountValue = parseFloat($('#discount_value').val());
            
            if (discountType === 'percentage' && (discountValue < 1 || discountValue > 100)) {
                e.preventDefault();
                toastr.error('Percentage discount must be between 1 and 100');
                $('#discount_value').focus();
                return false;
            }
            
            if (discountType === 'fixed' && discountValue <= 0) {
                e.preventDefault();
                toastr.error('Fixed amount discount must be greater than 0');
                $('#discount_value').focus();
                return false;
            }
        });
    });
</script>
@endsection
