@extends('admin.layouts.main')

@section('title', 'Edit Bank Branch')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Edit Bank Branch</h4>
            <a href="{{ route('admin.banks_branches.index') }}" class="btn btn-secondary btn-sm">← Back to List</a>
        </div>

        <div class="card-body">

            {{-- Display Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.banks_branches.update', $branch->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="branch_name" class="form-label">Branch Name</label>
                    <input type="text" name="branch_name" id="branch_name" class="form-control"
                        value="{{ old('branch_name', $branch->branch_name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="bank_id" class="form-label">Select Bank</label>
                    <select name="bank_id" id="bank_id" class="form-control" required>
                        <option value="">-- Select Bank --</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" {{ $branch->bank_id == $bank->id ? 'selected' : '' }}>
                                {{ $bank->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="branch_code" class="form-label">Branch Code</label>
                    <input type="text" name="branch_code" id="branch_code" class="form-control"
                        value="{{ old('branch_code', $branch->branch_code) }}" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="1" {{ $branch->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $branch->status == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Branch</button>
                <a href="{{ route('admin.banks_branches.index') }}" class="btn btn-secondary">Cancel</a>
            </form>

        </div>
    </div>
</div>
@endsection
