@extends('admin.layouts.main')

@section('title', 'Income Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Income Report</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.reports') }}">Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Income Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Report</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.fee-management.reports.income') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="from_date" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="from_date" name="from_date" 
                                           value="{{ $fromDate }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="to_date" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="to_date" name="to_date" 
                                           value="{{ $toDate }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> Generate Report
                                        </button>
                                        <a href="{{ route('admin.fee-management.reports.income') }}" class="btn btn-secondary">
                                            <i class="fa fa-refresh"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-primary">{{ $collections->count() }}</h3>
                    <p class="mb-0">Total Collections</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-success">Rs. {{ number_format($collections->sum('paid_amount')) }}</h3>
                    <p class="mb-0">Total Collected</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-info">Rs. {{ number_format($collections->sum('total_amount')) }}</h3>
                    <p class="mb-0">Total Amount</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-warning">{{ $collections->where('payment_method', 'cash')->count() }}</h3>
                    <p class="mb-0">Cash Collections</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Collections Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Collection Details</h3>
                    <div class="card-options">
                        <button class="btn btn-success" onclick="exportToExcel()">
                            <i class="fa fa-file-excel-o"></i> Export Excel
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="incomeTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Total Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Collection Date</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($collections as $collection)
                                <tr>
                                    <td>{{ $collection->id }}</td>
                                    <td>{{ $collection->student->name ?? 'N/A' }}</td>
                                    <td>{{ $collection->academicClass->name ?? 'N/A' }}</td>
                                    <td>Rs. {{ number_format($collection->total_amount) }}</td>
                                    <td>Rs. {{ number_format($collection->paid_amount) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($collection->collection_date)->format('d M Y') }}</td>
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
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#incomeTable').DataTable({
            order: [[0, 'desc']],
            pageLength: 25,
            responsive: true
        });
    });

    function exportToExcel() {
        // Implement Excel export functionality
        toastr.info('Excel export feature will be implemented');
    }
</script>
@endsection
