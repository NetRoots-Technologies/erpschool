@extends('admin.layouts.main')

@section('title', 'Fee Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Fee Reports</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reports</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Income Report</h3>
                </div>
                <div class="card-body">
                    <p>View collected fees vs pending amounts for a specific period.</p>
                    <a href="{{ route('admin.fee-management.reports.income') }}" class="btn btn-primary btn-block">
                        <i class="fa fa-chart-line"></i> View Income Report
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Outstanding Dues</h3>
                </div>
                <div class="card-body">
                    <p>View students with outstanding fee payments.</p>
                    <a href="{{ route('admin.fee-management.reports.outstanding') }}" class="btn btn-warning btn-block">
                        <i class="fa fa-exclamation-triangle"></i> View Outstanding
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Student Ledger</h3>
                </div>
                <div class="card-body">
                    <p>View individual student fee transactions.</p>
                    <form action="{{ route('admin.fee-management.reports.student-ledger', 0) }}" method="GET" class="d-inline">
                        <div class="form-group">
                            <select name="studentId" class="form-control" required>
                                <option value="">Select Student</option>
                                <option value="1">Student 1</option>
                                <option value="2">Student 2</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fa fa-user"></i> View Ledger
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Statistics</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">Rs. 0</h4>
                                <p>Total Collected Today</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">Rs. 0</h4>
                                <p>Total Collected This Month</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">Rs. 0</h4>
                                <p>Outstanding Amount</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">0</h4>
                                <p>Students with Dues</p>
                            </div>
                        </div>
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
        // Initialize any report-specific JavaScript here
        console.log('Fee Reports page loaded');
    });
</script>
@endsection
