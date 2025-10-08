@extends('admin.layouts.main')

@section('title', 'Accounts & Finance Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Accounts & Finance Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Accounts</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Financial Summary Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Assets</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <span class="counter-value" data-target="{{ $total_assets }}">
                                Rs. {{ number_format($total_assets, 2) }}
                            </span>
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

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Liabilities</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            Rs. {{ number_format($total_liabilities, 2) }}
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-danger rounded fs-3">
                            <i class="fa fa-credit-card"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Revenue</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            Rs. {{ number_format($total_revenue, 2) }}
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-info rounded fs-3">
                            <i class="fa fa-line-chart"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Expenses</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            Rs. {{ number_format($total_expenses, 2) }}
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning rounded fs-3">
                            <i class="fa fa-shopping-cart"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payables & Receivables -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Accounts Payable</h4>
                <a href="{{ route('accounts.payables.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <p class="text-muted mb-2">Total Payable</p>
                            <h5 class="text-danger">Rs. {{ number_format($accounts_payable, 2) }}</h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <p class="text-muted mb-2">Overdue Bills</p>
                            <h5 class="text-warning">{{ $overdue_bills }}</h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <p class="text-muted mb-2">Vendors</p>
                            <h5>{{ \App\Models\Accounts\Vendor::count() }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Accounts Receivable</h4>
                <a href="{{ route('accounts.receivables.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <p class="text-muted mb-2">Total Receivable</p>
                            <h5 class="text-success">Rs. {{ number_format($accounts_receivable, 2) }}</h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <p class="text-muted mb-2">Overdue Invoices</p>
                            <h5 class="text-warning">{{ $overdue_invoices }}</h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <p class="text-muted mb-2">Customers</p>
                            <h5>{{ \App\Models\Accounts\Customer::count() }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Journal Entries -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Recent Journal Entries</h4>
                <a href="{{ route('accounts.journal.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-nowrap align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Entry #</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_entries as $entry)
                            <tr>
                                <td><a href="{{ route('accounts.journal.show', $entry->id) }}">{{ $entry->entry_number }}</a></td>
                                <td>{{ $entry->entry_date->format('d M Y') }}</td>
                                <td>{{ $entry->description }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($entry->entry_type) }}</span></td>
                                <td>Rs. {{ number_format($entry->total_debit, 2) }}</td>
                                <td>
                                    @if($entry->status == 'posted')
                                        <span class="badge bg-success">Posted</span>
                                    @elseif($entry->status == 'draft')
                                        <span class="badge bg-warning">Draft</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($entry->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No journal entries found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Quick Actions</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('accounts.journal.create') }}" class="btn btn-primary btn-block w-100 mb-2">
                            <i class="fa fa-plus"></i> New Journal Entry
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('accounts.payables.bills.create') }}" class="btn btn-danger btn-block w-100 mb-2">
                            <i class="fa fa-file-text"></i> New Bill
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('accounts.receivables.invoices.create') }}" class="btn btn-success btn-block w-100 mb-2">
                            <i class="fa fa-file-invoice"></i> New Invoice
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('accounts.reports.trial_balance') }}" class="btn btn-info btn-block w-100 mb-2">
                            <i class="fa fa-bar-chart"></i> Trial Balance
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Add any dashboard-specific JavaScript here
</script>
@endsection
