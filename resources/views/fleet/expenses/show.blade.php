@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Expense Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.expenses.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Expenses
                        </a>
                        <a href="{{ route('fleet.expenses.edit', $expense->id) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Expense Type:</th>
                                    <td>
                                        <span class="badge badge-info" style="color: #000 !important;">
                                            {{ ucfirst(str_replace('_', ' ', $expense->expense_type)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Vehicle:</th>
                                    <td>
                                        @if($expense->vehicle)
                                            <strong>{{ $expense->vehicle->vehicle_number }}</strong><br>
                                            <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $expense->vehicle->vehicle_type)) }}</small>
                                        @else
                                            <span class="text-muted">Not Assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Driver:</th>
                                    <td>
                                        @if($expense->driver)
                                            <strong>{{ $expense->driver->driver_name }}</strong><br>
                                            <small class="text-muted">{{ $expense->driver->driver_phone }}</small>
                                        @else
                                            <span class="text-muted">Not Assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Expense Date:</th>
                                    <td>{{ $expense->expense_date ? $expense->expense_date->format('d M Y') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Amount:</th>
                                    <td><strong>Rs. {{ number_format($expense->amount) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Receipt Number:</th>
                                    <td>{{ $expense->receipt_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($expense->status == 'approved')
                                            <span class="badge badge-success" style="color: #000 !important;">Approved</span>
                                        @elseif($expense->status == 'pending')
                                            <span class="badge badge-warning" style="color: #000 !important;">Pending</span>
                                        @else
                                            <span class="badge badge-danger" style="color: #000 !important;">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $expense->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($expense->description)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Description:</h5>
                            <p class="text-muted">{{ $expense->description }}</p>
                        </div>
                    </div>
                    @endif

                    @if($expense->notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Notes:</h5>
                            <p class="text-muted">{{ $expense->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection