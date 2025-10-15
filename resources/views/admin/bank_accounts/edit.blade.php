@extends('admin.layouts.main')

@section('title', 'Edit Bank Account')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Edit Bank Account</h4>
            <a href="{{ route('admin.bank_accounts.index') }}" class="btn btn-secondary btn-sm">← Back to List</a>
        </div>

        <div class="card-body">
      
            {{-- Form --}}
            <form action="{{ route('admin.bank_accounts.update', $account->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="bank_id" class="form-label">Select Bank</label>
                    <select name="bank_id" id="bank_id" class="form-control" required>
                        <option value="">-- Select Bank --</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" {{ $bank->id == $account->bank_id ? 'selected' : '' }}>
                                {{ $bank->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="bank_branch_id" class="form-label">Select Branch</label>
                    <select name="bank_branch_id" id="bank_branch_id" class="form-control" required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branch->id == $account->bank_branch_id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="account_no" class="form-label">Account Number</label>
                    <input type="text" name="account_no" id="account_no" class="form-control"
                           value="{{ $account->account_no }}" required>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Account Type</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="MOA" {{ $account->type == 'MOA' ? 'selected' : '' }}>MOA</option>
                        <option value="MCA" {{ $account->type == 'MCA' ? 'selected' : '' }}>MCA</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.bank_accounts.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Update Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
