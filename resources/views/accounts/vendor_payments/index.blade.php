@extends('admin.layouts.main')

@section('title', 'Profit Centers')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Vendor Payments</h2>
        <a href="{{ route('accounts.payables.vendorPayments.create') }}" class="btn btn-primary">Create Payment</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Voucher No</th>
                <th>Date</th>
                <th>Vendor</th>
                <th>Invoice Status</th>
                <th>Invoice Amt</th>
                <th>Pending</th>
                <th>Paid</th>
                <th>Mode</th>
                <th>Prepared By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $p)
                <tr>
                    <td>{{ $p->voucher_no }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->payment_date)->format('Y-m-d') }}</td>

                    <!-- ⭐ UPDATED VENDOR NAME + TYPE (->food) -->
                    <td>
                        {{ optional($p->vendor)->name }}
                        {{ optional($p->vendor)->type ? '->' . $p->vendor->type : '' }}
                    </td>

                    <td>{{  $p->invoice->delivery_status ?? '-' }}</td>
                    <td>{{ number_format($p->invoice_amount ?? 0,2) }}</td>
                    <td>{{ number_format($p->pending_amount ?? 0,2) }}</td>
                    <td>{{ number_format($p->payment_amount,2) }}</td>
                    <td>{{ $p->payment_mode }}</td>
                    <td>{{ optional($p->preparedByUser)->name ?? '-' }}</td>

                    <td style="white-space:nowrap">
                        <a href="{{ route('accounts.payables.vendorPayments.edit', $p->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <a href="{{ route('accounts.payables.vendorPayments.print', $p->id) }}" class="btn btn-sm btn-info" target="_blank">Print</a>

                        <form action="{{ route('accounts.payables.vendorPayments.delete', $p->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this payment?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10" class="text-center">No payments found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $payments->links() }}
</div>
@endsection
