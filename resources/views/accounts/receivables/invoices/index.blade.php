@extends('admin.layouts.main')

@section('title', 'Invoices')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Customer Invoices</h4>
            <div class="page-title-right">
                <a href="{{ route('accounts.receivables.invoices.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> New Invoice
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
                                <th>Invoice #</th>
                                <th>Customer</th>
                                <th>Invoice Date</th>
                                <th>Due Date</th>
                                <th>Total Amount</th>
                                <th>Received</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                            <tr>
                                <td><a href="{{ route('accounts.receivables.invoices.show', $invoice->id) }}">{{ $invoice->invoice_number }}</a></td>
                                <td>{{ $invoice->customer->name }}</td>
                                <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                                <td>{{ $invoice->due_date->format('d M Y') }}</td>
                                <td class="text-end">Rs. {{ number_format($invoice->total_amount, 2) }}</td>
                                <td class="text-end">Rs. {{ number_format($invoice->received_amount, 2) }}</td>
                                <td class="text-end">
                                    <strong class="text-success">Rs. {{ number_format($invoice->balance, 2) }}</strong>
                                </td>
                                <td>
                                    @if($invoice->status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($invoice->status == 'overdue')
                                        <span class="badge bg-danger">Overdue</span>
                                    @elseif($invoice->status == 'partially_paid')
                                        <span class="badge bg-warning">Partially Paid</span>
                                    @else
                                        <span class="badge bg-info">{{ ucfirst($invoice->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('accounts.receivables.invoices.show', $invoice->id) }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">No invoices found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $invoices->links('pagination::bootstrap-4') }}
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
