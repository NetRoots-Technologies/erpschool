@extends('admin.layouts.main')

@section('title', 'Create Bank Branch')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Create New Bank Branch</h4>
            <a href="{{ route('admin.banks_branches.index') }}" class="btn btn-secondary btn-sm">← Back to List</a>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.banks_branches.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="branch_name" class="form-label">Branch Name</label>
                    <input type="text" class="form-control" name="branch_name" id="branch_name" value="{{ old('branch_name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="bank_id" class="form-label">Select Bank</label>
                    <select name="bank_id" id="bank_id" class="form-control" required>
                        <option value="">-- Select Bank --</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                {{ $bank->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="branch_code" class="form-label">Branch Code</label>
                    <input type="text" class="form-control" name="branch_code" id="branch_code" value="{{ old('branch_code') }}" required>
                </div>

                <button type="submit" class="btn btn-success">Save Branch</button>
                <a href="{{ route('admin.banks_branches.index') }}" class="btn btn-secondary">Cancel</a>
            </form>

        </div>
    </div>
</div>
@endsection
