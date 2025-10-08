@extends('admin.layouts.main')

@section('title', 'Bills')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Vendor Bills</h4>
            <div class="page-title-right">
                <a href="{{ route('accounts.payables.bills.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> New Bill
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
                                <th>Bill #</th>
                                <th>Vendor</th>
                                <th>Bill Date</th>
                                <th>Due Date</th>
                                <th>Total Amount</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bills as $bill)
                            <tr>
                                <td><a href="{{ route('accounts.payables.bills.show', $bill->id) }}">{{ $bill->bill_number }}</a></td>
                                <td>{{ $bill->vendor->name }}</td>
                                <td>{{ $bill->bill_date->format('d M Y') }}</td>
                                <td>{{ $bill->due_date->format('d M Y') }}</td>
                                <td class="text-end">Rs. {{ number_format($bill->total_amount, 2) }}</td>
                                <td class="text-end">Rs. {{ number_format($bill->paid_amount, 2) }}</td>
                                <td class="text-end">
                                    <strong class="text-danger">Rs. {{ number_format($bill->balance, 2) }}</strong>
                                </td>
                                <td>
                                    @if($bill->status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($bill->status == 'overdue')
                                        <span class="badge bg-danger">Overdue</span>
                                    @elseif($bill->status == 'partially_paid')
                                        <span class="badge bg-warning">Partially Paid</span>
                                    @else
                                        <span class="badge bg-info">{{ ucfirst($bill->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('accounts.payables.bills.show', $bill->id) }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">No bills found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $bills->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
