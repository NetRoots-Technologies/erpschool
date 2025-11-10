@extends('admin.layouts.main')

@section('title', 'Student Ledger')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Student Ledger - {{ $student->fullname }}</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.reports') }}">Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Student Ledger</li>
                    </ol>
                </div>
                <div class="page-rightheader mb-2">
                    <button class="btn btn-success" onclick="printLedger()">
                        <i class="fa fa-print"></i> Print Ledger
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Information -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Student Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <strong>Name:</strong> {{ $student->fullname ?? 'N/A' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Father Name:</strong> {{ $student->father_name ?? 'N/A' }}
                        </div>
                        <div class="col-md-2">
                            <strong>Class:</strong> {{ $student->academicClass->name ?? 'N/A' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Session:</strong> {{ $student->academicSession->name ?? 'N/A' }}
                        </div>
                        <div class="col-md-2">
                            <strong>Student ID:</strong> {{ $student->student_id ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-success">Rs. {{ number_format($collections->sum('paid_amount')) }}</h3>
                    <p class="mb-0">Total Collected</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-info">{{ $collections->count() }}</h3>
                    <p class="mb-0">Total Collections</p>
                </div>
            </div>
        </div>
        <div class="col-md-4" style="display: none;">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-warning">Rs. {{ number_format($adjustments->sum('amount')) }}</h3>
                    <p class="mb-0">Total Adjustments</p>
                </div>
            </div>
        </div>

        @php
            $outstandingBalance = $feeBilling->sum('outstanding_amount');
        @endphp
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-danger">Rs. {{  $outstandingBalance  }}</h3>
                    <p class="mb-0">Outstanding Balance</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Collections -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fee Collections</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($collections as $collection)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($collection->collection_date)->format('d M Y') }}</td>
                                    <td>Rs. {{ number_format($collection->paid_amount) }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ ucfirst(str_replace('_', ' ', $collection->payment_method)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $collection->status == 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($collection->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $collection->remarks ?? 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No collections found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Adjustments -->
    <div class="row" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fee Adjustments</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($adjustments as $adjustment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($adjustment->adjustment_date)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $adjustment->adjustment_type == 'refund' ? 'success' : 'warning' }}">
                                            {{ ucfirst($adjustment->adjustment_type) }}
                                        </span>
                                    </td>
                                    <td>Rs. {{ number_format($adjustment->amount) }}</td>
                                    <td>{{ $adjustment->reason }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No adjustments found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Summary -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transaction Summary</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Collections by Payment Method</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between">
                                    Cash Collections
                                    <span class="badge badge-primary">
                                        Rs. {{ number_format($collections->where('status' , 'paid')->whereIn('payment_method', ['cash', 'Cash'])->sum('paid_amount')) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    Bank Transfers
                                    <span class="badge badge-info">
                                        Rs. {{ number_format($collections->where('payment_method', 'bank_transfer')->sum('paid_amount')) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    Cheque Payments
                                    <span class="badge badge-warning">
                                        Rs. {{ number_format($collections->where('payment_method', 'cheque')->sum('paid_amount')) }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Recent Activity</h5>
                            <div class="timeline">
                                @foreach($collections->take(5) as $collection)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Fee Collection</h6>
                                        <p class="timeline-text">
                                            Rs. {{ number_format($collection->paid_amount) }} collected on 
                                            {{ \Carbon\Carbon::parse($collection->collection_date)->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
<style>
.badge {
    color: #212529 !important;
}
.badge-success {
    background-color: #28a745 !important;
    color: #212529 !important;
}
.badge-danger {
    background-color: #dc3545 !important;
    color: #212529 !important;
}
.badge-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}
.badge-info {
    background-color: #17a2b8 !important;
    color: #212529 !important;
}
.badge-secondary {
    background-color: #6c757d !important;
    color: #212529 !important;
}
</style>
@endsection

@section('js')
<script>
    function printLedger() {
        window.print();
    }
</script>
@endsection
