@extends('admin.layouts.main')

@section('title', 'Edit Fee Category')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Edit Fee Category</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.categories') }}">Categories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Fee Category Information</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.fee-management.categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $category->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="form-label">Category Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="admission" {{ old('type', $category->type) == 'admission' ? 'selected' : '' }}>Admission</option>
                                        <option value="monthly" {{ old('type', $category->type) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="annual" {{ old('type', $category->type) == 'annual' ? 'selected' : '' }}>Annual</option>
                                        <option value="one_time" {{ old('type', $category->type) == 'one_time' ? 'selected' : '' }}>One Time</option>
                                        <option value="allocation" {{ old('type', $category->type) == 'allocation' ? 'selected' : '' }}>Allocation</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_mandatory" 
                                               name="is_mandatory" value="1" {{ old('is_mandatory', $category->is_mandatory) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_mandatory">
                                            Mandatory Fee
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="affects_financials" 
                                               name="affects_financials" value="1" {{ old('affects_financials', $category->affects_financials) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="affects_financials">
                                            Affects Financials
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" 
                                               name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Update Category
                                    </button>
                                    <a href="{{ route('admin.fee-management.categories') }}" class="btn btn-secondary">
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
                    <h3 class="card-title">Category Types</h3>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item">
                            <strong>Admission:</strong> One-time fees charged during admission
                        </div>
                        <div class="list-group-item">
                            <strong>Monthly:</strong> Recurring monthly fees
                        </div>
                        <div class="list-group-item">
                            <strong>Annual:</strong> Yearly fees charged once per year
                        </div>
                        <div class="list-group-item">
                            <strong>One Time:</strong> Single payment fees
                        </div>
                        <div class="list-group-item">
                            <strong>Allocation:</strong> Optional fees like food, transport, etc.
                        </div>
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
        // Form validation
        $('form').on('submit', function(e) {
            var name = $('#name').val().trim();
            var type = $('#type').val();
            
            if (!name) {
                e.preventDefault();
                toastr.error('Please enter category name');
                $('#name').focus();
                return false;
            }
            
            if (!type) {
                e.preventDefault();
                toastr.error('Please select category type');
                $('#type').focus();
                return false;
            }
        });
    });
</script>
@endsection
