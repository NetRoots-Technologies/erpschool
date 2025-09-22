@extends('admin.layouts.main')

@section('title', 'Outstanding Dues Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Outstanding Dues Report</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.reports') }}">Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Outstanding Dues</li>
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
                    <form method="GET" action="{{ route('admin.fee-management.reports.outstanding') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="class_id" class="form-label">Class</label>
                                    <select class="form-control" id="class_id" name="class_id">
                                        <option value="">All Classes</option>
                                        <option value="1">Class 1</option>
                                        <option value="2">Class 2</option>
                                        <option value="3">Class 3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="session_id" class="form-label">Session</label>
                                    <select class="form-control" id="session_id" name="session_id">
                                        <option value="">All Sessions</option>
                                        <option value="1">2024-25</option>
                                        <option value="2">2025-26</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('admin.fee-management.reports.outstanding') }}" class="btn btn-secondary">
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
                    <h3 class="text-warning">{{ $outstanding->count() }}</h3>
                    <p class="mb-0">Total Outstanding</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-danger">Rs. {{ number_format($outstanding->sum('total_amount')) }}</h3>
                    <p class="mb-0">Total Outstanding Amount</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-info">{{ $outstanding->where('status', 'pending')->count() }}</h3>
                    <p class="mb-0">Pending Bills</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-danger">{{ $outstanding->where('status', 'overdue')->count() }}</h3>
                    <p class="mb-0">Overdue Bills</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Outstanding Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Outstanding Dues</h3>
                    <div class="card-options">
                        <button class="btn btn-success" onclick="exportToExcel()">
                            <i class="fa fa-file-excel-o"></i> Export Excel
                        </button>
                        <button class="btn btn-warning" onclick="sendReminders()">
                            <i class="fa fa-envelope"></i> Send Reminders
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="outstandingTable">
                            <thead>
                                <tr>
                                    <th>Challan No</th>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Session</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Days Overdue</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($outstanding as $bill)
                                <tr>
                                    <td>{{ $bill->challan_number }}</td>
                                    <td>{{ $bill->student->name ?? 'N/A' }}</td>
                                    <td>{{ $bill->academicClass->name ?? 'N/A' }}</td>
                                    <td>{{ $bill->academicSession->name ?? 'N/A' }}</td>
                                    <td>Rs. {{ number_format($bill->total_amount) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($bill->due_date)->format('d M Y') }}</td>
                                    <td>
                                        @php
                                            $daysOverdue = \Carbon\Carbon::parse($bill->due_date)->diffInDays(now());
                                        @endphp
                                        @if($daysOverdue > 0)
                                            <span class="text-danger">{{ $daysOverdue }} days</span>
                                        @else
                                            <span class="text-success">On time</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $bill->status == 'overdue' ? 'danger' : 'warning' }}">
                                            {{ ucfirst($bill->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.fee-management.billing.show', $bill->id) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.fee-management.billing.print', $bill->id) }}" 
                                           class="btn btn-sm btn-success" target="_blank">
                                            <i class="fa fa-print"></i> Print
                                        </a>
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
        $('#outstandingTable').DataTable({
            order: [[6, 'desc']], // Sort by days overdue
            pageLength: 25,
            responsive: true
        });
    });

    function exportToExcel() {
        // Implement Excel export functionality
        toastr.info('Excel export feature will be implemented');
    }

    function sendReminders() {
        if (confirm('Send reminder messages to all students with outstanding dues?')) {
            // Implement reminder sending functionality
            toastr.info('Reminder sending feature will be implemented');
        }
    }
</script>
@endsection
