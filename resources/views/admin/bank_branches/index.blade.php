@extends('admin.layouts.main')

@section('title', 'Bank Branch List')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Bank Branch List</h4>
            <a href="{{ route('admin.banks_branches.create') }}" class="btn btn-primary btn-sm">+ Add New Branch</a>
        </div>

        <div class="card-body">

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Branch Name</th>
                            <th>Bank</th>
                            <th>Branch Code</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($branches as $branch)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $branch->branch_name }}</td>
                                <td>{{ $branch->bank->name ?? '-' }}</td>
                                <td>{{ $branch->branch_code }}</td>
                                <td>
                                    @if($branch->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.banks_branches.edit', $branch->id) }}" class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('admin.banks_branches.destroy', $branch->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this branch?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle me-1"></i> No bank branches found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
