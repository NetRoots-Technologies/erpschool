@extends('admin.layouts.main')

@section('title', 'Create Bank Account')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Create New Bank Account</h4>
            <a href="{{ route('admin.bank_accounts.index') }}" class="btn btn-secondary btn-sm">← Back to List</a>
        </div>

        <div class="card-body">

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.bank_accounts.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="bank_id" class="form-label">Bank</label>
                    <select name="bank_id" class="form-control" required>
                        <option value="">-- Select Bank --</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                {{ $bank->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="bank_branch_id" class="form-label">Bank Branch</label>
                    <select name="bank_branch_id" class="form-control" required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('bank_branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="account_no" class="form-label">Account No</label>
                    <input type="text" name="account_no" class="form-control" value="{{ old('account_no') }}" required>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" class="form-control" required>
                        <option value="MOA" {{ old('type') == 'MOA' ? 'selected' : '' }}>MOA</option>
                        <option value="MCA" {{ old('type') == 'MCA' ? 'selected' : '' }}>MCA</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Save Account</button>
                <a href="{{ route('admin.bank_accounts.index') }}" class="btn btn-secondary">Cancel</a>
            </form>

        </div>
    </div>
</div>
@endsection
