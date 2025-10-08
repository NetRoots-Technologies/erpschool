@extends('admin.layouts.main')

@section('title', 'Vendors')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Vendors</h4>
            <div class="page-title-right">
                <a href="{{ route('accounts.payables.vendors.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Vendor
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
                                <th>Contact Person</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Outstanding</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendors as $vendor)
                            <tr>
                                <td>{{ $vendor->code }}</td>
                                <td>
                                    <strong>{{ $vendor->name }}</strong><br>
                                    <small class="text-muted">{{ $vendor->city }}, {{ $vendor->country }}</small>
                                </td>
                                <td>{{ $vendor->contact_person }}</td>
                                <td>{{ $vendor->phone }}</td>
                                <td>{{ $vendor->email }}</td>
                                <td class="text-end">
                                    <strong class="text-danger">Rs. {{ number_format($vendor->total_outstanding, 2) }}</strong>
                                </td>
                                <td>
                                    @if($vendor->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('accounts.payables.vendors.edit', $vendor->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('accounts.payables.bills.index') }}?vendor_id={{ $vendor->id }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-file-text"></i> Bills
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No vendors found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $vendors->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
