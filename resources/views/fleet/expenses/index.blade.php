@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Fleet Expenses</h3>
                        <div class="card-tools">
                            @if (Gate::allows('Fleet-expense-create'))
                                <a href="{{ route('fleet.expenses.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Add Expense
                                </a>
                            @endif

                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Vehicle</th>
                                        <th>Driver</th>
                                        <th>Expense Type</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Receipt Number</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->id }}</td>
                                            <td>
                                                @if ($expense->vehicle)
                                                    {{ $expense->vehicle->vehicle_number }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($expense->driver)
                                                    {{ $expense->driver->driver_name }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-info" style="color: #000 !important;">
                                                    {{ ucfirst(str_replace('_', ' ', $expense->expense_type)) }}
                                                </span>
                                            </td>
                                            <td>{{ $expense->expense_date ? $expense->expense_date->format('d M Y') : 'N/A' }}
                                            </td>
                                            <td><strong>Rs. {{ number_format($expense->amount) }}</strong></td>
                                            <td>{{ Str::limit($expense->description, 30) }}</td>
                                            <td>{{ $expense->receipt_number ?? 'N/A' }}</td>
                                            <td>
                                                @if ($expense->status == 'approved')
                                                    <span class="badge badge-success"
                                                        style="color: #000 !important;">Approved</span>
                                                @elseif($expense->status == 'pending')
                                                    <span class="badge badge-warning"
                                                        style="color: #000 !important;">Pending</span>
                                                @else
                                                    <span class="badge badge-danger"
                                                        style="color: #000 !important;">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if (Gate::allows('Fleet-expense-create'))
                                                        <a href="{{ route('fleet.expenses.show', $expense->id) }}"
                                                            class="btn btn-info btn-sm" title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    @if (Gate::allows('Fleet-expense-create'))
                                                        <a href="{{ route('fleet.expenses.edit', $expense->id) }}"
                                                            class="btn btn-warning btn-sm" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if (Gate::allows('Fleet-expense-create'))
                                                        <form action="{{ route('fleet.expenses.destroy', $expense->id) }}"
                                                            method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                title="Delete"
                                                                onclick="return confirm('Are you sure you want to delete this expense?')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif



                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No expenses found</td>
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
