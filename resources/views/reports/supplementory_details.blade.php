@extends('admin.layouts.main')

@section('title') Supplementary Budget Details Report @stop

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3>Expence Report Details - {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('M-Y') }}</h3>
    </div>
    <div class="card-body">
        <div class="mt-4 table-responsive">
            <table id="variance-table" class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Budget Name</th>
                        <th>Category Name</th>
                        <th>Sub Category Name</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($details as $item)
                        <tr>
                            <td>{{ $item->budget_name }}</td>
                            <td>{{ $item->c_name }}</td>
                            <td>{{ $item->sb_name }}</td>
                            <td> {{date("d-m-y" , strtotime($item->expense_date))}}</td>
                            <td>{{ number_format($item->expense_amount, 2) }}</td>
                            <td>{{ $item->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-danger">No Data Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-end">
                {!! $details->links('pagination::bootstrap-4') !!}
            </div>
        </div>
    </div>
</div>
@endsection
