@extends('admin.layouts.main')

@section('title', 'Fee Collection Details')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Fee Collection Details</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.collections') }}">Collections</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Details</li>
                    </ol>
                </div>
                <div class="page-rightheader">
                    <a href="{{ route('admin.fee-management.collections') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back to Collections
                    </a>
                </div>
            </div>
        </div>
    </div>



    <!-- Payment Summary -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success" style="color: white !important;">
                    <h3 class="card-title" style="color: white !important;">
                        <i class="fa fa-calculator"></i> Payment Summary
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Collection ID:</strong></td>
                                    <td><span class="badge badge-info">#{{ $collection->id }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Student:</strong></td>
                                    <td><span class="text-primary font-weight-bold">{{ $collection->student->fullname ?? 'N/A' }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Class:</strong></td>
                                    <td><span class="badge badge-secondary">{{ $collection->academicClass->name ?? $collection->student->AcademicClass->name ?? 'N/A' }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Session:</strong></td>
                                    <td><span class="text-info">{{ $collection->academicSession->name ?? $collection->student->academicSession->name ?? 'N/A' }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td><span class="text-primary font-weight-bold">Rs. {{ number_format($collection->total_amount ?? $collection->paid_amount, 2) }}</span></td>
                                </tr>
                                @if($totalTransportFee > 0)
                                <tr>
                                    <td><strong>Transport Fee:</strong></td>
                                    <td><span class="text-info font-weight-bold">Rs. {{ number_format($totalTransportFee, 2) }}</span></td>
                                </tr>
                                @endif
                                @if($totalDiscount > 0)
                                <tr>
                                    <td><strong>Discount Applied:</strong></td>
                                    <td><span class="text-success font-weight-bold">- Rs. {{ number_format($totalDiscount, 2) }}</span></td>
                                </tr>
                                @endif
                                <tr class="border-top">
                                    <td><strong>Paid Amount:</strong></td>
                                    <td><span class="text-success font-weight-bold">Rs. {{ number_format($collection->paid_amount, 2) }}</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if($transportFees && $transportFees->count() > 0)
                            <h6 class="text-info"><i class="fa fa-bus"></i> Transport Details</h6>
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Vehicle</th>
                                        <th>Route</th>
                                        <th>Charges</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transportFees as $transport)
                                    <tr>
                                        <td>{{ $transport->vehicle->vehicle_number ?? 'N/A' }}</td>
                                        <td>{{ $transport->route->route_name ?? 'N/A' }}</td>
                                        <td>Rs. {{ number_format($transport->monthly_charges, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                            
                            @if($discounts && $discounts->count() > 0)
                            <h6 class="text-success mt-3"><i class="fa fa-gift"></i> Applied Discounts</h6>
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($discounts as $discount)
                                    @php
                                        $discountAmount = $discount->calculateDiscount($collection->billing->total_amount);
                                    @endphp
                                    <tr>
                                        <td>{{ $discount->category->name ?? 'General' }}</td>
                                        <td>Rs. {{ number_format($discountAmount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
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
