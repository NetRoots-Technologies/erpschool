@extends('admin.layouts.main')

@section('title', 'Cost Center Reports')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Cost Center Reports</h4>
            <div class="page-title-right">
                <a href="{{ route('accounts.cost_centers.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Expense Analysis by Cost Center</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Cost Center</th>
                                <th>Code</th>
                                <th>Type</th>
                                <th class="text-end">Total Expenses</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item['cost_center']->name }}</strong><br>
                                    <small class="text-muted">{{ $item['cost_center']->description }}</small>
                                </td>
                                <td>{{ $item['cost_center']->code }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($item['cost_center']->type) }}</span></td>
                                <td class="text-end">
                                    <strong class="text-danger">Rs. {{ number_format($item['total_expenses'], 2) }}</strong>
                                </td>
                                <td>
                                    @if($item['cost_center']->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No cost center data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th class="text-end">Rs. {{ number_format(collect($data)->sum('total_expenses'), 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
