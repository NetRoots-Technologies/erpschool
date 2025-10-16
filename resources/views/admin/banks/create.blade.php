@extends('admin.layouts.main')

@section('title', 'Add New Bank')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Add New Bank</h4>
            <a href="{{ route('admin.banks.index') }}" class="btn btn-secondary btn-sm">← Back to List</a>
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

            <form action="{{ route('admin.banks.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Bank Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter bank name" value="{{ old('name') }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Save Bank</button>
                <a href="{{ route('admin.banks.index') }}" class="btn btn-secondary">Cancel</a>
            </form>

        </div>
    </div>
</div>
@endsection
