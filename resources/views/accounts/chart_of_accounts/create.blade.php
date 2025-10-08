@extends('admin.layouts.main')

@section('title', 'Create Account')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Create New Account</h4>
            <div class="page-title-right">
                <a href="{{ route('accounts.coa.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('accounts.coa.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Account Group <span class="text-danger">*</span></label>
                        <select name="account_group_id" class="form-select" required>
                            <option value="">Select Group</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ old('account_group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }} ({{ ucfirst($group->type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Account Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                        <small class="text-muted">Unique code for this account (e.g., 1001, CASH-01)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Account Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Opening Balance</label>
                                <input type="number" step="0.01" name="opening_balance" class="form-control" value="{{ old('opening_balance', 0) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Balance Type</label>
                                <select name="opening_balance_type" class="form-select">
                                    <option value="debit" {{ old('opening_balance_type') == 'debit' ? 'selected' : '' }}>Debit</option>
                                    <option value="credit" {{ old('opening_balance_type') == 'credit' ? 'selected' : '' }}>Credit</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
