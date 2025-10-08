@extends('admin.layouts.main')

@section('title', 'Profit Centers')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Profit Centers</h4>
            <div class="page-title-right">
                <a href="{{ route('accounts.profit_centers.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Profit Center
                </a>
                <a href="{{ route('accounts.profit_centers.reports') }}" class="btn btn-info">
                    <i class="fa fa-bar-chart"></i> Reports
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($profitCenters as $center)
                            <tr>
                                <td>{{ $center->code }}</td>
                                <td>{{ $center->name }}</td>
                                <td><span class="badge bg-success">{{ ucfirst($center->type) }}</span></td>
                                <td>{{ $center->description }}</td>
                                <td>
                                    @if($center->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('accounts.profit_centers.edit', $center->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No profit centers found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $profitCenters->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
