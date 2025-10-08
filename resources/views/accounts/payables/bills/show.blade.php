@extends('admin.layouts.main')

@section('title', 'Bill Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Bill: {{ $bill->bill_number }}</h4>
            <div class="page-title-right">
                <a href="{{ route('accounts.payables.bills.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
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
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Bill Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Vendor Information</h6>
                        <p class="mb-1"><strong>{{ $bill->vendor->name }}</strong></p>
                        <p class="mb-1">{{ $bill->vendor->contact_person }}</p>
                        <p class="mb-1">{{ $bill->vendor->phone }}</p>
                        <p class="mb-1">{{ $bill->vendor->email }}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h6>Bill Information</h6>
                        <p class="mb-1"><strong>Bill #:</strong> {{ $bill->bill_number }}</p>
                        <p class="mb-1"><strong>Vendor Invoice #:</strong> {{ $bill->vendor_invoice_number ?: 'N/A' }}</p>
                        <p class="mb-1"><strong>Bill Date:</strong> {{ $bill->bill_date->format('d M Y') }}</p>
                        <p class="mb-1"><strong>Due Date:</strong> {{ $bill->due_date->format('d M Y') }}</p>
                    </div>
                </div>

                <hr>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Description</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Subtotal</td>
                                <td class="text-end">Rs. {{ number_format($bill->subtotal, 2) }}</td>
                            </tr>
                            @if($bill->tax_amount > 0)
                            <tr>
                                <td>Tax</td>
                                <td class="text-end">Rs. {{ number_format($bill->tax_amount, 2) }}</td>
                            </tr>
                            @endif
                            @if($bill->discount > 0)
                            <tr>
                                <td>Discount</td>
                                <td class="text-end">- Rs. {{ number_format($bill->discount, 2) }}</td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th>Total Amount</th>
                                <th class="text-end">Rs. {{ number_format($bill->total_amount, 2) }}</th>
                            </tr>
                            <tr>
                                <th>Paid Amount</th>
                                <th class="text-end text-success">Rs. {{ number_format($bill->paid_amount, 2) }}</th>
                            </tr>
                            <tr>
                                <th>Balance Due</th>
                                <th class="text-end text-danger">Rs. {{ number_format($bill->balance, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($bill->notes)
                <div class="mt-3">
                    <h6>Notes:</h6>
                    <p>{{ $bill->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Status</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    @if($bill->status == 'paid')
                        <span class="badge bg-success fs-6">Paid</span>
                    @elseif($bill->status == 'overdue')
                        <span class="badge bg-danger fs-6">Overdue</span>
                    @elseif($bill->status == 'partially_paid')
                        <span class="badge bg-warning fs-6">Partially Paid</span>
                    @else
                        <span class="badge bg-info fs-6">{{ ucfirst($bill->status) }}</span>
                    @endif
                </div>

                @if($bill->balance > 0)
                <form action="{{ route('accounts.payables.bills.pay', $bill->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Payment Amount</label>
                        <input type="number" step="0.01" name="payment_amount" class="form-control" max="{{ $bill->balance }}" value="{{ $bill->balance }}" required>
                        <small class="text-muted">Max: Rs. {{ number_format($bill->balance, 2) }}</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fa fa-money"></i> Record Payment
                    </button>
                </form>
                @else
                <div class="alert alert-success">
                    <i class="fa fa-check-circle"></i> This bill has been fully paid.
                </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Linked Entries</h5>
            </div>
            <div class="card-body">
                @if($bill->journal_entry_id)
                <p class="mb-0">
                    <i class="fa fa-link"></i> 
                    <a href="{{ route('accounts.journal.show', $bill->journal_entry_id) }}">
                        View Journal Entry
                    </a>
                </p>
                @else
                <p class="text-muted mb-0">No linked journal entry</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
