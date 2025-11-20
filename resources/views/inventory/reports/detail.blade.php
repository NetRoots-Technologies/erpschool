@extends('admin.layouts.main')

@section('title', 'Supplier Ledger')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Supplier Ledger - {{ $supplier->name }}</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Supplier Ledger</li>
                    </ol>
                </div>
                <div class="page-rightheader mb-2 d-flex gap-2">

                    <!-- Print Ledger Button -->
                    <button class="btn btn-info" onclick="printLedger()">
                        <i class="fa fa-print"></i> Print Ledger
                    </button>

                    <!-- PDF Button (optional) -->
                    <a href="{{ route('inventory.reports.supplier-ledger.pdf', $supplier->id) }}" 
                           class="btn btn-danger">
                        <i class="fa fa-file-pdf"></i> Download PDF
                    </a>

                    <!-- Excel Button (optional) -->
                   <a href="{{ route('inventory.reports.supplier-ledger.excel', $supplierId) }}" class="btn btn-success">
                                <i class="fa fa-file-excel-o"></i> Download Excel
                    </a>

                </div>
            </div>
        </div>
    </div>

        <!-- Supplier Information -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Supplier Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <strong>Name:</strong> {{ $supplier->name ?? 'N/A' }}
                            </div>
                            <div class="col-md-2">
                                <strong>Contact:</strong> {{ $supplier->contact ?? 'N/A' }}
                            </div>
                            <div class="col-md-3">
                                <strong>Address:</strong> {{ $supplier->address ?? 'N/A' }}
                            </div>
                            <div class="col-md-3">
                                <strong>Email:</strong> {{ $supplier->email ?? 'N/A' }}
                            </div>
                            <div class="col-md-2">
                                <strong>Type:</strong> {{ $supplier->type ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <!-- Summary Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-success">Rs. {{ number_format($purchases->sum('amount')) }}</h3>
                    <p class="mb-0">Total Purchases</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-info">Rs. {{ number_format($payments->sum('payment_amount')) }}</h3>
                    <p class="mb-0">Total Payments</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            @php
                $outstandingBalance = $purchases->sum('amount') - $payments->sum('payment_amount');
            @endphp
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-danger">Rs. {{ number_format($outstandingBalance) }}</h3>
                    <p class="mb-0">Outstanding Balance</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-warning">{{ $purchases->count() }}</h3>
                    <p class="mb-0">Total Transactions</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchases Table -->
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Purchases</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Order Date</th>                                
                                    <th>Name</th>                                
                                    <th>Total Amount</th>
                                    <th>Delivery Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchases as $purchase)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($purchase->date)->format('d M Y') }}</td>
                                    <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                                    <td>Rs. {{ number_format($purchase->total_amount) }}</td>
                                    <td>{{ $purchase->delivery_status ?? 'N/A' }}</td>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No purchases found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payments</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Voucher No.</th>
                                    <th>Payment Date</th>
                                    <th>Invoice Amount</th>
                                    <th>Pending Amount</th>
                                   <th>Payment Mode</th>
                                    <th>Paid Amount</th>
                                    <th>Cheque No.</th>
                                    <th>Cheque Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                <tr>
                                    <td>{{ $payment->voucher_no ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                                    <td>Rs. {{ number_format($payment->invoice_amount, 2) }}</td>
                                    <td>Rs. {{ number_format($payment->pending_amount, 2) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_mode)) }}</td>
                                    <td>Rs. {{ number_format($payment->payment_amount, 2) }}</td>
                                    <td>{{ $payment->cheque_no ?? 'N/A' }}</td>
                                    <td>{{ $payment->cheque_date ? \Carbon\Carbon::parse($payment->cheque_date)->format('d M Y') : 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No payments found</td>
                                </tr>
                                @endforelse
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
function printLedger() {
    window.print();
}
</script>
@endsection
