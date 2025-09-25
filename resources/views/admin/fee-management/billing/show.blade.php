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
                                        @php
                                            $paidAmount = $billing->paid_amount ?? 0;
                                            $finalAmount = $billing->getFinalAmount();
                                            $outstandingAmount = $finalAmount - $paidAmount;
                                            
                                            if ($outstandingAmount <= 0) {
                                                $status = 'paid';
                                                $badgeClass = 'success';
                                            } else if ($paidAmount > 0) {
                                                $status = 'partial';
                                                $badgeClass = 'warning';
                                            } else {
                                                $status = 'pending';
                                                $badgeClass = 'info';
                                            }
                                        @endphp
                                        <span class="badge badge-{{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td><strong>Rs. {{ number_format($billing->total_amount, 2) }}</strong></td>
                                </tr>
                                @if($totalDiscount > 0)
                                <tr>
                                    <td><strong>Discount Applied:</strong></td>
                                    <td><strong class="text-success">- Rs. {{ number_format($totalDiscount, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Final Amount:</strong></td>
                                    <td><strong class="text-primary">Rs. {{ number_format($finalAmount, 2) }}</strong></td>
                                </tr>
                                @endif
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

    @if($totalDiscount > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Applied Discounts</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Discount Type</th>
                                    <th>Value</th>
                                    <th>Amount</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($discounts as $discount)
                                <tr>
                                    <td>{{ $discount->category->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($discount->discount_type == 'percentage')
                                            <span class="badge badge-info">Percentage</span>
                                        @else
                                            <span class="badge badge-warning">Fixed Amount</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($discount->discount_type == 'percentage')
                                            {{ $discount->discount_value }}%
                                        @else
                                            Rs. {{ number_format($discount->discount_value, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($discount->discount_type == 'percentage')
                                            Rs. {{ number_format(($billing->total_amount * $discount->discount_value) / 100, 2) }}
                                        @else
                                            Rs. {{ number_format($discount->discount_value, 2) }}
                                        @endif
                                    </td>
                                    <td>{{ $discount->reason ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-success">
                                    <th colspan="3">Total Discount</th>
                                    <th>Rs. {{ number_format($totalDiscount, 2) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment History</h3>
                </div>
                <div class="card-body">
                    @php
                        $paidAmount = $billing->paid_amount ?? 0;
                        $finalAmount = $billing->getFinalAmount();
                        $outstandingAmount = $finalAmount - $paidAmount;
                    @endphp
                    
                    @if($outstandingAmount <= 0)
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i> This bill has been paid successfully.
                        </div>
                    @elseif($paidAmount > 0)
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i> This bill has been partially paid. Remaining amount: <strong>Rs. {{ number_format($outstandingAmount, 2) }}</strong>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i> This bill is pending payment.
                        </div>
                    @endif
                    
                    <p><strong>Amount Due:</strong> Rs. {{ number_format($finalAmount, 2) }}</p>
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
.badge-info {
    background-color: #17a2b8;
    color: #212529;
}
</style>
@endsection
