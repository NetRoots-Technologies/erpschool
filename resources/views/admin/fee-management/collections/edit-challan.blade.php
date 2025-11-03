@extends('admin.layouts.main')

@section('title', 'Edit Challan Payment')

@push('meta')
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Edit Challan Payment</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.collections') }}">Collections</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Payment</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark">
                    <h3 class="card-title mb-0">
                        <i class="fa fa-edit mr-2"></i>
                        Edit Challan Payment
                    </h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Challan Information -->
                    <div class="challan-details mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="fa fa-file-text mr-2"></i>Challan Information
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Challan Number:</strong> {{ $collection->billing->challan_number ?? 'N/A' }}</p>
                                <p><strong>Student:</strong> {{ $collection->student->fullname ?? 'N/A' }}</p>
                                <p><strong>Class:</strong> {{ $collection->student->AcademicClass->name ?? $collection->academicClass->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Session:</strong> {{ $collection->academicSession->name ?? 'N/A' }}</p>
                                <p><strong>Billing Month:</strong> {{ $collection->billing->billing_month ?? 'N/A' }}</p>
                                <p><strong>Due Date:</strong> {{ $collection->billing->due_date ? \Carbon\Carbon::parse($collection->billing->due_date)->format('d M Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <form action="{{ route('admin.fee-management.collections.update-challan', $collection->id) }}" method="POST" id="editChallanPaymentForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="collection_date" class="form-label font-weight-bold">
                                        <i class="fa fa-calendar text-primary mr-1"></i>
                                        Payment Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('collection_date') is-invalid @enderror" 
                                           id="collection_date" name="collection_date" 
                                           value="{{ old('collection_date', $collection->collection_date->format('Y-m-d')) }}" required>
                                    @error('collection_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_method" class="form-label font-weight-bold">
                                        <i class="fa fa-credit-card text-primary mr-1"></i>
                                        Payment Method <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('payment_method') is-invalid @enderror" 
                                            id="payment_method" name="payment_method" required>
                                        <option value="">Select Payment Method</option>
                                        <option value="cash" {{ old('payment_method', $collection->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bank_transfer" {{ old('payment_method', $collection->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="cheque" {{ old('payment_method', $collection->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paid_amount" class="form-label font-weight-bold">
                                        <i class="fa fa-money text-primary mr-1"></i>
                                        Payment Amount <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control @error('paid_amount') is-invalid @enderror" 
                                           id="paid_amount" name="paid_amount" 
                                           value="{{ old('paid_amount', $collection->paid_amount) }}"
                                           placeholder="Enter amount" min="0" step="0.01" required>
                                    @error('paid_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Original amount: Rs. {{ number_format($collection->paid_amount, 2) }}
                                    </small>
                                </div>
                            </div>
                                <input type="hidden" name="fine_amount" value="{{  $collection->billing->fine_amount }}">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="remarks" class="form-label font-weight-bold">
                                        <i class="fa fa-comment text-primary mr-1"></i>
                                        Remarks
                                    </label>
                                    <input type="text" class="form-control @error('remarks') is-invalid @enderror" 
                                           id="remarks" name="remarks" 
                                           value="{{ old('remarks', $collection->remarks) }}" 
                                           placeholder="e.g., Online transfer, Cash deposit">
                                    @error('remarks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        <div class="payment-summary">
                            <h6 class="text-primary mb-3">
                                <i class="fa fa-calculator mr-2"></i>Payment Summary
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td><strong>Challan Amount:</strong></td>
                                            <td class="text-right">Rs. {{ number_format($collection->billing->total_amount ?? 0, 2) }}</td>
                                        </tr>

                                        <tr>
                                            <td><strong>Fine Amount:</strong></td>
                                            <td class="text-right">Rs. {{ number_format($collection->billing->fine_amount ?? 0, 2) }}</td>
                                        </tr>

                                        @if(isset($totalTransportFee) && $totalTransportFee > 0)
                                        <tr>
                                            <td class="text-info"><strong>Transport Fee:</strong></td>
                                            <td class="text-right text-info">+ Rs. {{ number_format($totalTransportFee, 2) }}</td>
                                        </tr>
                                        @endif
                                        @if(isset($totalDiscount) && $totalDiscount > 0)
                                        <tr>
                                            <td class="text-success"><strong>Discount Applied:</strong></td>
                                            <td class="text-right text-success">- Rs. {{ number_format($totalDiscount, 2) }}</td>
                                        </tr>
                                        @endif
                                        <tr class="border-top">
                                            <td><strong>Total Amount:</strong></td>
                                            <td class="text-right"><strong>Rs. {{ number_format(($collection->billing->total_amount ?? 0) + ($collection->billing->fine_amount ?? 0) + (isset($totalTransportFee) ? $totalTransportFee : 0) - (isset($totalDiscount) ? $totalDiscount : 0), 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Current Payment:</strong></td>
                                            <td class="text-right">Rs. {{ number_format($collection->paid_amount, 2) }}</td>
                                        </tr>
                                        <tr class="border-top">
                                            <td><strong>Outstanding:</strong></td>
                                            <td class="text-right text-danger"><strong>Rs. {{ number_format((($collection->billing->total_amount ?? 0) +  ($collection->billing->fine_amount ?? 0) + (isset($totalTransportFee) ? $totalTransportFee : 0) - (isset($totalDiscount) ? $totalDiscount : 0)) - $collection->paid_amount, 2) }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Challan Status:</strong> 
                                        @php
                                            $challan = $collection->billing;
                                            $paidAmount = $challan->paid_amount ?? 0;
                                            $finalAmount = $challan->getFinalAmount();
                                            $outstandingAmount = $finalAmount + $challan->fine_amount - $paidAmount;
                                            
                                            if ($outstandingAmount <= 0) {
                                                $status = 'Paid';
                                                $badgeClass = 'success';
                                            } else if ($paidAmount > 0) {
                                                $status = 'Partial';
                                                $badgeClass = 'warning';
                                            } else {
                                                $status = 'Pending';
                                                $badgeClass = 'info';
                                            }
                                        @endphp
                                        <span class="badge badge-{{ $badgeClass }}">{{ $status }}</span>
                                    </p>
                                    <p><strong>Outstanding:</strong> Rs. {{ number_format($outstandingAmount, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-success btn-block">
                                                <i class="fa fa-save"></i> Update Payment
                                            </button>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="{{ route('admin.fee-management.collections') }}" class="btn btn-secondary btn-block">
                                                <i class="fa fa-times"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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

/* Professional styling improvements */
.card {
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
    border-bottom: none;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #e1e5e9;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #20c997, #17a2b8);
    transform: translateY(-1px);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d, #495057);
    border: none;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #495057, #343a40);
    transform: translateY(-1px);
}

.alert {
    border-radius: 8px;
    border: none;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}

.payment-summary {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #dee2e6;
}

.challan-details {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #ffc107;
}

/* Button layout fixes */
.btn-block {
    width: 100%;
    margin-bottom: 10px;
}

@media (min-width: 768px) {
    .btn-block {
        margin-bottom: 0;
    }
}
</style>
@endsection
