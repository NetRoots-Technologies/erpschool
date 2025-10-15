@extends('admin.layouts.main')

@section('title', 'Edit Bank')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Edit Bank</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.banks.update', $bank->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Bank Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $bank->name }}" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.banks.index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-success">Update Bank</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
