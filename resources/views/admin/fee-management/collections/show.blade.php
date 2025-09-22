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

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Collection Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Collection ID:</strong></td>
                                    <td>#{{ $collection->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Student:</strong></td>
                                    <td>{{ $collection->student->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Class:</strong></td>
                                    <td>{{ $collection->academicClass->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Session:</strong></td>
                                    <td>{{ $collection->academicSession->name ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td>Rs. {{ number_format($collection->total_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Paid Amount:</strong></td>
                                    <td>Rs. {{ number_format($collection->paid_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($collection->status == 'paid')
                                            <span class="badge badge-success">Paid</span>
                                        @elseif($collection->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @else
                                            <span class="badge badge-danger">{{ ucfirst($collection->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Collection Date:</strong></td>
                                    <td>{{ $collection->collection_date ? \Carbon\Carbon::parse($collection->collection_date)->format('d M Y') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment Details</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Payment Method:</strong></td>
                            <td>{{ ucfirst($collection->payment_method ?? 'N/A') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Reference:</strong></td>
                            <td>{{ $collection->reference_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Remarks:</strong></td>
                            <td>{{ $collection->remarks ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td>{{ $collection->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($collection->details && $collection->details->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fee Breakdown</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($collection->details as $detail)
                                <tr>
                                    <td>{{ $detail->feeCategory->name ?? 'N/A' }}</td>
                                    <td>Rs. {{ number_format($detail->amount, 2) }}</td>
                                    <td>
                                        @if($detail->status == 'paid')
                                            <span class="badge badge-success">Paid</span>
                                        @elseif($detail->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @else
                                            <span class="badge badge-danger">{{ ucfirst($detail->status) }}</span>
                                        @endif
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
    @endif
</div>
@endsection
