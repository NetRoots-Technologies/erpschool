@extends('admin.layouts.main')

@section('title', 'Create Account Group')

@section('css')
<style>
    .type-selector {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .type-selector:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .type-selector.active {
        border-color: currentColor;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('accounts.dashboard') }}">Accounts</a></li>
                <li class="breadcrumb-item"><a href="{{ route('accounts.groups.index') }}">Account Groups</a></li>
                <li class="breadcrumb-item active">Create New</li>
            </ol>
        </nav>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fa fa-plus-circle"></i> Create New Account Group
                </h4>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong><i class="fa fa-exclamation-triangle"></i> Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('accounts.groups.store') }}" method="POST" id="groupForm">
                    @csrf
                    
                    <!-- Account Type Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            Account Type <span class="text-danger">*</span>
                        </label>
                        <p class="text-muted small mb-3">Select the fundamental category for this group</p>
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="card type-selector h-100 text-center p-3" data-type="asset">
                                    <div class="card-body">
                                        <i class="fa fa-coins fa-3x text-success mb-3"></i>
                                        <h5 class="text-success">Assets</h5>
                                        <p class="small text-muted mb-0">اثاثے</p>
                                        <p class="small">Things you own</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card type-selector h-100 text-center p-3" data-type="liability">
                                    <div class="card-body">
                                        <i class="fa fa-file-invoice-dollar fa-3x text-danger mb-3"></i>
                                        <h5 class="text-danger">Liabilities</h5>
                                        <p class="small text-muted mb-0">قرضے</p>
                                        <p class="small">Things you owe</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card type-selector h-100 text-center p-3" data-type="equity">
                                    <div class="card-body">
                                        <i class="fa fa-balance-scale fa-3x text-primary mb-3"></i>
                                        <h5 class="text-primary">Equity</h5>
                                        <p class="small text-muted mb-0">سرمایہ</p>
                                        <p class="small">Owner's stake</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card type-selector h-100 text-center p-3" data-type="revenue">
                                    <div class="card-body">
                                        <i class="fa fa-arrow-up fa-3x text-info mb-3"></i>
                                        <h5 class="text-info">Revenue</h5>
                                        <p class="small text-muted mb-0">آمدنی</p>
                                        <p class="small">Income earned</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card type-selector h-100 text-center p-3" data-type="expense">
                                    <div class="card-body">
                                        <i class="fa fa-arrow-down fa-3x text-warning mb-3"></i>
                                        <h5 class="text-warning">Expenses</h5>
                                        <p class="small text-muted mb-0">خرچے</p>
                                        <p class="small">Costs incurred</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="type" id="typeInput" value="{{ old('type') }}" required>
                        <div id="typeError" class="text-danger small mt-2" style="display: none;">
                            Please select an account type
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Group Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">
                                    Group Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       placeholder="e.g., Current Assets"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Descriptive name for the group</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label fw-bold">
                                    Group Code <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('code') is-invalid @enderror" 
                                       id="code" 
                                       name="code" 
                                       value="{{ old('code') }}"
                                       placeholder="e.g., AST-001"
                                       required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Unique alphanumeric identifier</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="parent_id" class="form-label fw-bold">
                            Parent Group <span class="badge bg-secondary">Optional</span>
                        </label>
                        <select class="form-select @error('parent_id') is-invalid @enderror" 
                                id="parent_id" 
                                name="parent_id">
                            <option value="">-- None (Create as Main Group) --</option>
                            @foreach($parentGroups as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }} ({{ $parent->code }}) - {{ ucfirst($parent->type) }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Select a parent to create this as a sub-group</small>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">
                            Description <span class="badge bg-secondary">Optional</span>
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3"
                                  placeholder="Brief description of what this group contains...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Examples Info -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="fa fa-lightbulb"></i> Common Examples:</h6>
                        <div class="row small">
                            <div class="col-md-6">
                                <strong>Assets:</strong> Current Assets, Fixed Assets, Cash Accounts<br>
                                <strong>Liabilities:</strong> Current Liabilities, Accounts Payable, Loans
                            </div>
                            <div class="col-md-6">
                                <strong>Revenue:</strong> Fee Income, Other Income, Sales<br>
                                <strong>Expenses:</strong> Salary Expense, Rent, Utilities
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('accounts.groups.index') }}" class="btn btn-secondary">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa fa-save"></i> Create Account Group
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Type selector functionality
    $('.type-selector').on('click', function() {
        $('.type-selector').removeClass('active');
        $(this).addClass('active');
        
        var type = $(this).data('type');
        $('#typeInput').val(type);
        $('#typeError').hide();
    });
    
    // Pre-select if old value exists
    var oldType = $('#typeInput').val();
    if (oldType) {
        $('.type-selector[data-type="' + oldType + '"]').addClass('active');
    }
    
    // Form validation
    $('#groupForm').on('submit', function(e) {
        var type = $('#typeInput').val();
        if (!type) {
            e.preventDefault();
            $('#typeError').show();
            $('html, body').animate({
                scrollTop: $('.type-selector').first().offset().top - 100
            }, 500);
            return false;
        }
    });
    
    // Auto-generate code suggestion
    $('#name').on('blur', function() {
        var name = $(this).val();
        var code = $('#code').val();
        
        if (name && !code) {
            var type = $('#typeInput').val();
            var prefix = '';
            
            switch(type) {
                case 'asset': prefix = 'AST'; break;
                case 'liability': prefix = 'LIA'; break;
                case 'equity': prefix = 'EQT'; break;
                case 'revenue': prefix = 'REV'; break;
                case 'expense': prefix = 'EXP'; break;
            }
            
            if (prefix) {
                var randomNum = Math.floor(Math.random() * 900) + 100;
                $('#code').val(prefix + '-' + randomNum);
            }
        }
    });
});
</script>
@endsection
