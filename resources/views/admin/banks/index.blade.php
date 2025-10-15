@extends('admin.layouts.main')

@section('title', 'Bank List')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Bank List</h4>
            <a href="{{ route('admin.banks.create') }}" class="btn btn-primary btn-sm">+ Add New Bank</a>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Bank Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($banks as $bank)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $bank->name }}</td>
                            <td>
                                @if($bank->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-warning text-dark">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.banks.edit', $bank->id) }}" class="btn btn-sm btn-info">Edit</a>
                                <form action="{{ route('admin.banks.destroy', $bank->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this bank?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No banks found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
