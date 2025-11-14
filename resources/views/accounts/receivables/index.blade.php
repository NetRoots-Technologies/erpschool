@extends('admin.layouts.main')

@section('title', 'Accounts Receivable')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Accounts Receivable Dashboard</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Total Receivable</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <span class="text-success">Rs. {{ number_format($summary['total_receivable'], 2) }}</span>
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success rounded fs-3">
                            <i class="fa fa-money"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Overdue</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <span class="text-warning">Rs. {{ number_format($summary['overdue'], 2) }}</span>
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning rounded fs-3">
                            <i class="fa fa-exclamation-triangle"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Due This Month</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <span class="text-info">Rs. {{ number_format($summary['due_this_month'], 2) }}</span>
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-info rounded fs-3">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Outstanding Invoices</h4>
                <a href="{{ route('accounts.receivables.invoices.index') }}" class="btn btn-sm btn-primary">View All Invoices</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice #</th>
                                <th>Customer</th>
                                <th>Student</th>
                                <th>Invoice Date</th>
                                <th>Due Date</th>
                                <th>Total Amount</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                            <tr>
                                <td><a href="{{ route('accounts.receivables.invoices.show', $invoice->id) }}">{{ $invoice->invoice_number }}</a></td>
                                <td>{{ $invoice->customer->name ?? 'N/A' }}</td>
                                <td>{{ $invoice->student->full_name ?? 'N/A' }}</td>
                                <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                                <td>{{ $invoice->due_date->format('d M Y') }}</td>
                                <td class="text-end">Rs. {{ number_format($invoice->total_amount, 2) }}</td>
                                <td class="text-end">
                                    <strong class="text-success">Rs. {{ number_format($invoice->balance, 2) }}</strong>
                                </td>
                                <td>
                                    @if($invoice->status == 'overdue')
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
                                <td colspan="8" class="text-center">No outstanding invoices</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
