@extends('admin.layouts.main')

@section('title', 'Edit Account')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit Account</h4>
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
                <form action="{{ route('accounts.coa.update', $ledger->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Account Group <span class="text-danger">*</span></label>
                        <select name="account_group_id" class="form-select" required>
                            <option value="">Select Group</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ $ledger->account_group_id == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }} ({{ ucfirst($group->type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Account Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" value="{{ $ledger->code }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Account Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ $ledger->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ $ledger->description }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Current Balance</label>
                                <input type="text" class="form-control" value="{{ number_format($ledger->current_balance, 2) }}" readonly>
                                <small class="text-muted">Balance is updated through journal entries</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Balance Type</label>
                                <input type="text" class="form-control" value="{{ ucfirst($ledger->current_balance_type) }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ $ledger->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                    @if($ledger->is_system)
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> This is a system account and some fields cannot be modified.
                    </div>
                    @endif

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Account Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Opening Balance:</th>
                        <td class="text-end">{{ number_format($ledger->opening_balance, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Opening Type:</th>
                        <td class="text-end">{{ ucfirst($ledger->opening_balance_type) }}</td>
                    </tr>
                    <tr>
                        <th>Current Balance:</th>
                        <td class="text-end">
                            <strong>{{ number_format($ledger->current_balance, 2) }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td class="text-end">{{ $ledger->created_at->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated:</th>
                        <td class="text-end">{{ $ledger->updated_at->format('d M Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
