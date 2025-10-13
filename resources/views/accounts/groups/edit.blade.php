@extends('admin.layouts.main')

@section('title', 'Edit Account Group')

@section('css')
<style>
    .info-badge {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 8px;
        margin: 5px;
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
                <li class="breadcrumb-item active">Edit: {{ $group->name }}</li>
            </ol>
        </nav>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fa fa-edit"></i> Edit Account Group
                    </h4>
                    <div>
                        <span class="badge bg-light text-dark">{{ $group->code }}</span>
                    </div>
                </div>
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

                <!-- Current Information -->
                <div class="alert alert-light border">
                    <h6 class="mb-3"><i class="fa fa-info-circle"></i> Current Information:</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-badge bg-{{ 
                                $group->type == 'asset' ? 'success' : 
                                ($group->type == 'liability' ? 'danger' : 
                                ($group->type == 'equity' ? 'primary' : 
                                ($group->type == 'revenue' ? 'info' : 'warning'))) 
                            }} text-white">
                                <strong>Type:</strong><br>
                                {{ ucfirst($group->type) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-badge bg-secondary text-white">
                                <strong>Level:</strong><br>
                                {{ $group->level }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-badge bg-info text-white">
                                <strong>Sub-groups:</strong><br>
                                {{ $group->children->count() }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-badge bg-dark text-white">
                                <strong>Ledgers:</strong><br>
                                {{ $group->ledgers->count() }}
                            </div>
                        </div>
                    </div>
                </div>

                @if($group->ledgers->count() > 0 || $group->children->count() > 0)
                    <div class="alert alert-warning">
                        <strong><i class="fa fa-exclamation-triangle"></i> Important:</strong>
                        <ul class="mb-0 mt-2">
                            @if($group->ledgers->count() > 0)
                                <li>This group has <strong>{{ $group->ledgers->count() }} ledger(s)</strong> associated with it.</li>
                            @endif
                            @if($group->children->count() > 0)
                                <li>This group has <strong>{{ $group->children->count() }} sub-group(s)</strong>.</li>
                            @endif
                            <li>Changing the type may affect financial reports and related records.</li>
                        </ul>
                    </div>
                @endif

                <form action="{{ route('accounts.groups.update', $group->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
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
                                       value="{{ old('name', $group->name) }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                       value="{{ old('code', $group->code) }}"
                                       required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Must be unique</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label fw-bold">
                                    Account Type <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" 
                                        name="type" 
                                        required>
                                    <option value="">-- Select Type --</option>
                                    <option value="asset" {{ old('type', $group->type) == 'asset' ? 'selected' : '' }}>
                                        Asset (اثاثے)
                                    </option>
                                    <option value="liability" {{ old('type', $group->type) == 'liability' ? 'selected' : '' }}>
                                        Liability (قرضے)
                                    </option>
                                    <option value="equity" {{ old('type', $group->type) == 'equity' ? 'selected' : '' }}>
                                        Equity (سرمایہ)
                                    </option>
                                    <option value="revenue" {{ old('type', $group->type) == 'revenue' ? 'selected' : '' }}>
                                        Revenue (آمدنی)
                                    </option>
                                    <option value="expense" {{ old('type', $group->type) == 'expense' ? 'selected' : '' }}>
                                        Expense (خرچہ)
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($group->ledgers->count() > 0)
                                    <small class="text-warning">
                                        <i class="fa fa-exclamation-triangle"></i> 
                                        Changing type affects {{ $group->ledgers->count() }} ledger(s)
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="parent_id" class="form-label fw-bold">
                                    Parent Group <span class="badge bg-secondary">Optional</span>
                                </label>
                                <select class="form-select @error('parent_id') is-invalid @enderror" 
                                        id="parent_id" 
                                        name="parent_id">
                                    <option value="">-- None (Main Group) --</option>
                                    @foreach($parentGroups as $parent)
                                        <option value="{{ $parent->id }}" 
                                                {{ old('parent_id', $group->parent_id) == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->name }} ({{ $parent->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">
                            Description <span class="badge bg-secondary">Optional</span>
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description', $group->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Related Items Display -->
                    @if($group->children->count() > 0)
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">
                                    <i class="fa fa-sitemap"></i> Sub-groups ({{ $group->children->count() }})
                                </h6>
                                <div class="row">
                                    @foreach($group->children as $child)
                                        <div class="col-md-6 mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-chevron-right text-muted me-2"></i>
                                                <span>{{ $child->name }}</span>
                                                <small class="text-muted ms-2">({{ $child->code }})</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('accounts.groups.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                        <div>
                            @if($group->ledgers->count() == 0 && $group->children->count() == 0)
                                <button type="button" 
                                        class="btn btn-outline-danger me-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            @endif
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa fa-save"></i> Update Group
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if($group->ledgers->count() == 0 && $group->children->count() == 0)
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this account group?</p>
                <div class="alert alert-warning">
                    <strong>Group Name:</strong> {{ $group->name }}<br>
                    <strong>Code:</strong> {{ $group->code }}<br>
                    <strong>Type:</strong> {{ ucfirst($group->type) }}
                </div>
                <p class="text-danger mb-0">
                    <i class="fa fa-exclamation-circle"></i> This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('accounts.groups.destroy', $group->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash"></i> Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
