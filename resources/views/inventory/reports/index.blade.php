@extends('admin.layouts.main')

@section('title', 'Supplier Ledger')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Supplier Ledger</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#">Inventory</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Supplier Ledger
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN LEDGER SELECT BOXES -->
    <div class="row">

        <!-- Remove extra left and right margins -->
        <div class="col-md-4"> <!-- Changed col-md-4 to col-md-12 to align properly -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Supplier Ledger</h3>
                </div>

                <div class="card-body">
                    <p>View complete purchase + payment ledger of supplier.</p>

                    <form id="supplierLedgerForm" class="d-inline">
                        <div class="form-group">
                            <select name="supplierId" class="form-control select2" required>
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">
                                        {{ $supplier->name }} 
                                        @if ($supplier->company)
                                            ({{ $supplier->company }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fa fa-file"></i> View Ledger
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- QUICK STATISTICS -->
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
                                <p>Total Purchase Today</p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">Rs. 0</h4>
                                <p>Total Purchase This Month</p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">Rs. 0</h4>
                                <p>Pending Supplier Payment</p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">0</h4>
                                <p>Total Suppliers with Pending Dues</p>
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
    $('#supplierLedgerForm').submit(function(e) {
        e.preventDefault();
        var supplierId = $('select[name="supplierId"]').val();
        window.location.href = '/inventory/reports/supplier-ledger/view/' + supplierId;
    });
</script>
@endsection
