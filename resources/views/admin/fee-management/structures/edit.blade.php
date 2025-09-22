@extends('admin.layouts.main')

@section('title', 'Edit Fee Structure')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Edit Fee Structure</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.structures') }}">Structures</a></li>
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
                    <h3 class="card-title">Edit Fee Structure</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('admin.fee-management.structures.update', $structure->id) }}" method="POST" id="structureForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Structure Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $structure->name }}" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="class_id">Class <span class="text-danger">*</span></label>
                                    <select class="form-control" id="academic_class_id" name="academic_class_id" required>
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ $structure->academic_class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('academic_class_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="session_id">Academic Session <span class="text-danger">*</span></label>
                                    <select class="form-control" id="academic_session_id" name="academic_session_id" required>
                                        <option value="">Select Session</option>
                                        @foreach($sessions as $session)
                                            <option value="{{ $session->id }}" {{ $structure->academic_session_id == $session->id ? 'selected' : '' }}>{{ $session->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('academic_session_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="factor_id">Fee Factor <span class="text-danger">*</span></label>
                                    <select class="form-control" id="fee_factor_id" name="fee_factor_id" required>
                                        <option value="">Select Factor</option>
                                        @foreach($factors as $factor)
                                            <option value="{{ $factor->id }}" {{ $structure->fee_factor_id == $factor->id ? 'selected' : '' }}>{{ $factor->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('fee_factor_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ $structure->description }}</textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Fee Categories Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Fee Categories</h5>
                                    <button type="button" class="btn btn-outline-success btn-sm" id="addCategory">
                                        <i class="fa fa-plus"></i> Add Another Category
                                    </button>
                                </div>
                                <div id="feeCategories">
                                    @if($structure->feeStructureDetails && count($structure->feeStructureDetails) > 0)
                                        @foreach($structure->feeStructureDetails as $index => $detail)
                                            <div class="row fee-category-row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Category</label>
                                                        <select class="form-control category-select" name="categories[{{ $index }}][category_id]" required>
                                                            <option value="">Select Category</option>
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category->id }}" {{ $detail->fee_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Amount</label>
                                                        <input type="number" class="form-control amount-input" name="categories[{{ $index }}][amount]" value="{{ $detail->amount }}" step="0.01" min="0" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Notes</label>
                                                        <input type="text" class="form-control" name="categories[{{ $index }}][notes]" value="{{ $detail->notes ?? '' }}" placeholder="Optional notes">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-danger btn-sm remove-category" style="margin-top: 30px;" {{ count($structure->feeStructureDetails) <= 1 ? 'style=display:none;margin-top:30px;' : '' }}>
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row fee-category-row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Category</label>
                                                    <select class="form-control category-select" name="categories[0][category_id]" required>
                                                        <option value="">Select Category</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Amount</label>
                                                    <input type="number" class="form-control amount-input" name="categories[0][amount]" step="0.01" min="0" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Notes</label>
                                                    <input type="text" class="form-control" name="categories[0][notes]" placeholder="Optional notes">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-danger btn-sm remove-category" style="display: none; margin-top: 30px;">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Update Structure</button>
                                <a href="{{ route('admin.fee-management.structures') }}" class="btn btn-secondary">Cancel</a>
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
        let categoryIndex = {{ $structure->feeStructureDetails ? count($structure->feeStructureDetails) : 1 }};


        // Add new category row when "Add Another Category" button is clicked
        $('#addCategory').click(function() {
            const newRow = `
                <div class="row fee-category-row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control category-select" name="categories[${categoryIndex}][category_id]" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" class="form-control amount-input" name="categories[${categoryIndex}][amount]" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Notes</label>
                            <input type="text" class="form-control" name="categories[${categoryIndex}][notes]" placeholder="Optional notes">
                        </div>
                    </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-danger btn-sm remove-category" style="margin-top: 30px;">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                </div>
            `;
            
            $('#feeCategories').append(newRow);
            categoryIndex++;
            updateRemoveButtons();
        });

        // Remove category row
        $(document).on('click', '.remove-category', function() {
            $(this).closest('.fee-category-row').remove();
            updateRemoveButtons();
        });

        // Update remove buttons visibility
        function updateRemoveButtons() {
            const rows = $('.fee-category-row');
            if (rows.length > 1) {
                $('.remove-category').show();
            } else {
                $('.remove-category').hide();
            }
        }

        // Calculate total amount
        $(document).on('input', '.amount-input', function() {
            let total = 0;
            $('.amount-input').each(function() {
                const value = parseFloat($(this).val()) || 0;
                total += value;
            });
            // You can display the total somewhere if needed
        });

        // Form validation
        $('#structureForm').on('submit', function(e) {
            const categoryRows = $('.fee-category-row');
            if (categoryRows.length === 0) {
                e.preventDefault();
                alert('Please add at least one fee category.');
                return false;
            }
        });

    });
</script>
@endsection
