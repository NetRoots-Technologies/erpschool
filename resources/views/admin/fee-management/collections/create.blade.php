@extends('admin.layouts.main')

@section('title', 'Record Fee Collection')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Record Fee Collection</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.collections') }}">Collections</a></li>
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
                    <h3 class="card-title">Collection Information</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.fee-management.collections.store') }}" method="POST" id="collectionForm">
                        @csrf
                        
                        <!-- Student Selection -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
                                    <select class="form-control @error('student_id') is-invalid @enderror" 
                                            id="student_id" name="student_id" required>
                                        <option value="">Select Student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" 
                                                    data-class="{{ $student->academicClass->name ?? 'N/A' }}"
                                                    data-session="{{ $student->academicSession->name ?? 'N/A' }}"
                                                    {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                {{ $student->name }} ({{ $student->academicClass->name ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                                    <select class="form-control @error('class_id') is-invalid @enderror" 
                                            id="class_id" name="class_id" required>
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="session_id" class="form-label">Session <span class="text-danger">*</span></label>
                                    <select class="form-control @error('session_id') is-invalid @enderror" 
                                            id="session_id" name="session_id" required>
                                        <option value="">Select Session</option>
                                        @foreach($sessions as $session)
                                            <option value="{{ $session->id }}" {{ old('session_id') == $session->id ? 'selected' : '' }}>
                                                {{ $session->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('session_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Collection Details -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="collection_date" class="form-label">Collection Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('collection_date') is-invalid @enderror" 
                                           id="collection_date" name="collection_date" 
                                           value="{{ old('collection_date', date('Y-m-d')) }}" required>
                                    @error('collection_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-control @error('payment_method') is-invalid @enderror" 
                                            id="payment_method" name="payment_method" required>
                                        <option value="">Select Method</option>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <input type="text" class="form-control @error('remarks') is-invalid @enderror" 
                                           id="remarks" name="remarks" value="{{ old('remarks') }}" 
                                           placeholder="e.g., Online transfer, Cash deposit">
                                    @error('remarks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Fee Categories -->
                        <div class="row">
                            <div class="col-12">
                                <h5>Fee Categories</h5>
                                <div id="feeCategories">
                                    <!-- Fee categories will be loaded dynamically -->
                                </div>
                                <button type="button" class="btn btn-sm btn-success" id="addCategory">
                                    <i class="fa fa-plus"></i> Add Category
                                </button>
                            </div>
                        </div>

                        <!-- Total Amount Display -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <strong>Total Amount: <span id="totalAmount">Rs. 0</span></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Record Collection
                                    </button>
                                    <a href="{{ route('admin.fee-management.collections') }}" class="btn btn-secondary">
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
                    <h3 class="card-title">Collection Guidelines</h3>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item">
                            <strong>Cash:</strong> Physical cash payment
                        </div>
                        <div class="list-group-item">
                            <strong>Bank Transfer:</strong> Online/ATM transfer
                        </div>
                        <div class="list-group-item">
                            <strong>Cheque:</strong> Cheque payment
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6>Important Notes:</h6>
                        <ul class="list-unstyled">
                            <li>• Verify student details before recording</li>
                            <li>• Double-check amounts</li>
                            <li>• Add remarks for clarity</li>
                            <li>• Collection cannot be edited after saving</li>
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
        let categoryCount = 0;
        
        // Add category row
        $('#addCategory').click(function() {
            addCategoryRow();
        });
        
        function addCategoryRow() {
            categoryCount++;
            const categoryRow = `
                <div class="row mb-2" id="categoryRow${categoryCount}">
                    <div class="col-md-4">
                        <select class="form-control category-select" name="collections[${categoryCount}][category_id]" required>
                            <option value="">Select Category</option>
                            <option value="1">Monthly Tuition</option>
                            <option value="2">Admission Fee</option>
                            <option value="3">Security</option>
                            <option value="4">Books & Stationery</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" class="form-control amount-input" name="collections[${categoryCount}][amount]" 
                               placeholder="Amount" min="0" step="0.01" required>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-sm btn-danger remove-category" data-row="${categoryCount}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#feeCategories').append(categoryRow);
        }
        
        // Remove category row
        $(document).on('click', '.remove-category', function() {
            const rowId = $(this).data('row');
            $(`#categoryRow${rowId}`).remove();
            calculateTotal();
        });
        
        // Calculate total amount
        $(document).on('input', '.amount-input', function() {
            calculateTotal();
        });
        
        function calculateTotal() {
            let total = 0;
            $('.amount-input').each(function() {
                const amount = parseFloat($(this).val()) || 0;
                total += amount;
            });
            $('#totalAmount').text('Rs. ' + total.toLocaleString());
        }
        
        // Auto-fill class and session when student is selected
        $('#student_id').change(function() {
            const selectedOption = $(this).find('option:selected');
            const classId = selectedOption.data('class');
            const sessionId = selectedOption.data('session');
            
            // You can implement auto-fill logic here if needed
        });
        
        // Form validation
        $('#collectionForm').on('submit', function(e) {
            if ($('.category-select').length === 0) {
                e.preventDefault();
                toastr.error('Please add at least one fee category');
                return false;
            }
            
            let hasValidCategory = false;
            $('.category-select').each(function() {
                if ($(this).val() && $(this).siblings('.amount-input').val()) {
                    hasValidCategory = true;
                }
            });
            
            if (!hasValidCategory) {
                e.preventDefault();
                toastr.error('Please fill in at least one complete fee category');
                return false;
            }
        });
        
        // Add initial category row
        addCategoryRow();
    });
</script>
@endsection
