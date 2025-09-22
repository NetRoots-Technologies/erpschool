@extends('admin.layouts.main')

@section('title', 'Create Fee Structure')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Create Fee Structure</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.structures') }}">Structures</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Fee Structure</h3>
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

                    <form action="{{ route('admin.fee-management.structures.store') }}" method="POST" id="structureForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Structure Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="class_id">Class <span class="text-danger">*</span></label>
                                    <select class="form-control" id="class_id" name="class_id" required>
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="session_id">Academic Session <span class="text-danger">*</span></label>
                                    <select class="form-control" id="session_id" name="session_id" required>
                                        <option value="">Select Session</option>
                                        @foreach($sessions as $session)
                                            <option value="{{ $session->id }}">{{ $session->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('session_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="factor_id">Fee Factor <span class="text-danger">*</span></label>
                                    <select class="form-control" id="factor_id" name="factor_id" required>
                                        <option value="">Select Factor</option>
                                        @foreach($factors as $factor)
                                            <option value="{{ $factor->id }}">{{ $factor->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('factor_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Fee Categories Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Fee Categories</h5>
                                <div id="feeCategories">
                                    <div class="row fee-category-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Category</label>
                                                <div class="input-group">
                                                    <select class="form-control category-select" name="categories[0][category_id]" required>
                                                        <option value="">Select Category</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#categoryModal">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
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
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-sm remove-category" style="display: none;">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Create Structure</button>
                                <a href="{{ route('admin.fee-management.structures') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Creation Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Create New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="category_name">Category Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="category_name" required>
                </div>
                <div class="form-group">
                    <label for="category_description">Description</label>
                    <textarea class="form-control" id="category_description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="category_type">Type <span class="text-danger">*</span></label>
                    <select class="form-control" id="category_type" required>
                        <option value="">Select Type</option>
                        <option value="admission">Admission</option>
                        <option value="monthly">Monthly</option>
                        <option value="annual">Annual</option>
                        <option value="one_time">One Time</option>
                        <option value="allocation">Allocation</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_mandatory">
                        <label class="form-check-label" for="is_mandatory">Mandatory</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="affects_financials" checked>
                        <label class="form-check-label" for="affects_financials">Affects Financials</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveCategory">Save Category</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        let categoryIndex = 1;

        // Add new category row when + button is clicked
        $(document).on('click', '[data-toggle="modal"][data-target="#categoryModal"]', function() {
            // Store reference to the button that opened the modal
            window.currentCategoryButton = $(this);
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

        // Handle category creation
        $('#saveCategory').click(function() {
            const formData = {
                name: $('#category_name').val(),
                description: $('#category_description').val(),
                type: $('#category_type').val(),
                is_mandatory: $('#is_mandatory').is(':checked'),
                affects_financials: $('#affects_financials').is(':checked'),
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            if (!formData.name || !formData.type) {
                alert('Please fill in all required fields.');
                return;
            }

            $.ajax({
                url: '{{ route("admin.fee-management.categories.store") }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Add new option to all category selects
                    const newOption = `<option value="${response.category.id}">${response.category.name}</option>`;
                    $('.category-select').each(function() {
                        $(this).append(newOption);
                    });
                    
                    // If this was opened from a + button, add a new row
                    if (window.currentCategoryButton && window.currentCategoryButton.length) {
                        const newRow = `
                            <div class="row fee-category-row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <div class="input-group">
                                            <select class="form-control category-select" name="categories[${categoryIndex}][category_id]" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                                <option value="${response.category.id}">${response.category.name}</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#categoryModal">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
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
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-sm remove-category">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        $('#feeCategories').append(newRow);
                        // Select the newly created category in the new row
                        $('#feeCategories .fee-category-row').last().find('.category-select').val(response.category.id);
                        categoryIndex++;
                        updateRemoveButtons();
                    } else {
                        // Select the newly created category in the current row
                        const currentRow = $('.fee-category-row').last();
                        currentRow.find('.category-select').val(response.category.id);
                    }
                    
                    // Close modal and reset form
                    $('#categoryModal').modal('hide');
                    $('#category_name, #category_description, #category_type').val('');
                    $('#is_mandatory, #affects_financials').prop('checked', false);
                    $('#affects_financials').prop('checked', true);
                    
                    // Clear the reference
                    window.currentCategoryButton = null;
                    
                    toastr.success('Category created successfully!');
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errorMessage = 'Please fix the following errors:\n';
                        Object.values(xhr.responseJSON.errors).forEach(errors => {
                            errors.forEach(error => {
                                errorMessage += '- ' + error + '\n';
                            });
                        });
                        alert(errorMessage);
                    } else {
                        alert('Error creating category. Please try again.');
                    }
                }
            });
        });

        // Reset modal form when closed
        $('#categoryModal').on('hidden.bs.modal', function() {
            $('#category_name, #category_description, #category_type').val('');
            $('#is_mandatory, #affects_financials').prop('checked', false);
            $('#affects_financials').prop('checked', true);
        });
    });
</script>
@endsection
