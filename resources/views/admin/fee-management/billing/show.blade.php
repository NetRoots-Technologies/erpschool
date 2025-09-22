@extends('admin.layouts.main')

@section('title', 'View Fee Bill')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Fee Bill Details</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.billing') }}">Billing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Bill</li>
                    </ol>
                </div>
                <div class="page-rightheader">
                    <a href="{{ route('admin.fee-management.billing') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back to Billing
                    </a>
                    <a href="{{ route('admin.fee-management.billing.print', $billing->id) }}" class="btn btn-success" target="_blank">
                        <i class="fa fa-print"></i> Print Bill
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bill Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Bill Details</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Bill Number:</strong></td>
                                    <td>{{ $billing->challan_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Bill Date:</strong></td>
                                    <td>{{ $billing->created_at->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Due Date:</strong></td>
                                    <td>{{ $billing->due_date ? \Carbon\Carbon::parse($billing->due_date)->format('d M Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($billing->status == 'paid')
                                            <span class="badge badge-success">Paid</span>
                                        @elseif($billing->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @else
                                            <span class="badge badge-danger">Overdue</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td><strong>Rs. {{ number_format($billing->total_amount, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Student Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Student Name:</strong></td>
                                    <td>{{ $billing->student->fullname ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Class:</strong></td>
                                    <td>{{ $billing->student->AcademicClass->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Session:</strong></td>
                                    <td>{{ $billing->academicSession->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Student ID:</strong></td>
                                    <td>{{ $billing->student->id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $billing->student->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $billing->student->email ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment History</h3>
                </div>
                <div class="card-body">
                    @if($billing->status == 'paid')
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i> This bill has been paid successfully.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i> This bill is pending payment.
                        </div>
                    @endif
                    
                    <p><strong>Amount Due:</strong> Rs. {{ number_format($billing->total_amount, 2) }}</p>
                    <p><strong>Due Date:</strong> {{ $billing->due_date ? \Carbon\Carbon::parse($billing->due_date)->format('d M Y') : 'Not specified' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
.badge {
    font-size: 0.75em;
    padding: 0.25em 0.5em;
    border-radius: 0.25rem;
}
.badge-success {
    background-color: #28a745;
    color: #212529;
}
.badge-warning {
    background-color: #ffc107;
    color: #212529;
}
.badge-danger {
    background-color: #dc3545;
    color: #212529;
}
</style>
@endsection
