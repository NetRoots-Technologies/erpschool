@extends('admin.layouts.main')

@section('title', 'Edit Fee Discount')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Edit Fee Discount</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.discounts') }}">Discounts</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Fee Discount</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.fee-management.discounts.update', $discount->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id">Student <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="student_id" name="student_id" required>
                                        <option value="">Select Student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ $discount->student_id == $student->id ? 'selected' : '' }}>
                                                {{ $student->fullname }} ({{ $student->AcademicClass->name ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Fee Category <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $discount->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_type">Discount Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="discount_type" name="discount_type" required>
                                        <option value="">Select Type</option>
                                        <option value="percentage" {{ $discount->discount_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        <option value="fixed" {{ $discount->discount_type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                    </select>
                                    @error('discount_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_value">Discount Value <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="discount_value" name="discount_value" 
                                           value="{{ $discount->discount_value }}" step="0.01" min="0" required>
                                    @error('discount_value')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="reason">Reason <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="reason" name="reason" rows="3" required>{{ $discount->reason }}</textarea>
                                    @error('reason')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="show_on_voucher">Show on Student Voucher</label><br>
                                        <input type="checkbox" id="show_on_voucher" name="show_on_voucher" value="1"
                                            {{ $discount->show_on_voucher ? 'checked' : '' }}>
                                        <label for="show_on_voucher">Yes</label>
                                        @error('show_on_voucher')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="valid_from_month">Valid From Month <span class="text-danger">*</span></label>
                                    <input type="month" class="form-control" id="valid_from_month" name="valid_from_month" 
                                           value="{{ $discount->valid_from ? $discount->valid_from->format('Y-m') : '' }}" required>
                                    @error('valid_from_month')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="valid_to_month">Valid To Month <span class="text-danger">*</span></label>
                                    <input type="month" class="form-control" id="valid_to_month" name="valid_to_month" 
                                           value="{{ $discount->valid_to ? $discount->valid_to->format('Y-m') : '' }}" required>
                                    @error('valid_to_month')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Update Discount</button>
                                <a href="{{ route('admin.fee-management.discounts') }}" class="btn btn-secondary">Cancel</a>
                            </div>
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
    $(document).ready(function() {
        // Update discount value label based on type
        $('#discount_type').change(function() {
            const type = $(this).val();
            const label = type === 'percentage' ? 'Percentage (%)' : 'Amount (Rs.)';
            $('#discount_value').attr('placeholder', label);
        });
        
        // Trigger change on page load
        $('#discount_type').trigger('change');
    });
</script>
@endsection
