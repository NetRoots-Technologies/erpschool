@extends('admin.layouts.main')

@section('title', 'Customers')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Customers</h4>
            <div class="page-title-right">
                <a href="{{ route('accounts.receivables.customers.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Customer
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
                                <th>Credit Limit</th>
                                <th>Outstanding</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr>
                                <td>{{ $customer->code }}</td>
                                <td>
                                    <strong>{{ $customer->name }}</strong><br>
                                    <small class="text-muted">{{ $customer->city }}, {{ $customer->country }}</small>
                                </td>
                                <td>{{ $customer->contact_person }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->email }}</td>
                                <td class="text-end">Rs. {{ number_format($customer->credit_limit, 2) }}</td>
                                <td class="text-end">
                                    <strong class="text-success">Rs. {{ number_format($customer->total_outstanding, 2) }}</strong>
                                </td>
                                <td>
                                    @if($customer->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('accounts.receivables.customers.edit', $customer->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('accounts.receivables.invoices.index') }}?customer_id={{ $customer->id }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-file-invoice"></i> Invoices
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">No customers found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
