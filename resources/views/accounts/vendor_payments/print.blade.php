@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4>Vendor Payment Voucher</h4>
                <div>Voucher: <strong>{{ $vp->voucher_no }}</strong></div>
                <div>Date: <strong>{{ \Carbon\Carbon::parse($vp->payment_date)->format('Y-m-d') }}</strong></div>
            </div>
            <div class="text-end">
                <h5>{{ optional($vp->vendor)->name }}</h5>
                <div>{{ optional($vp->vendor)->email }}</div>
                <div>{{ optional($vp->vendor)->phone }}</div>
            </div>
        </div>

        <table class="table table-borderless">
            <tr>
                <th style="width:200px">Invoice / GRN</th>
                <td>{{ optional($vp->invoice)->invoice_no ?? '-' }}</td>
            </tr>
            <tr>
                <th>Invoice Amount</th>
                <td>{{ number_format($vp->invoice_amount ?? 0,2) }}</td>
            </tr>
            <tr>
                <th>Pending Amount</th>
                <td>{{ number_format($vp->pending_amount ?? 0,2) }}</td>
            </tr>
            <tr>
                <th>Payment Amount</th>
                <td>{{ number_format($vp->payment_amount,2) }}</td>
            </tr>
            <tr>
                <th>Mode / Account</th>
                <td>{{ $vp->payment_mode }} {{ $vp->account_id ? ' | Account ID: '.$vp->account_id : '' }}</td>
            </tr>
            <tr>
                <th>Cheque</th>
                <td>{{ $vp->cheque_no ? $vp->cheque_no . ' / ' . ($vp->cheque_date ? \Carbon\Carbon::parse($vp->cheque_date)->format('Y-m-d') : '') : '-' }}</td>
            </tr>
            <tr>
                <th>Narration</th>
                <td>{{ $vp->remarks }}</td>
            </tr>
            <tr>
                <th>Prepared By</th>
                <td>{{ optional($vp->preparedByUser)->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Approved By</th>
                <td>{{ optional($vp->approvedByUser)->name ?? '-' }}</td>
            </tr>
        </table>

        @if($vp->attachment)
            <div class="mt-3">
                Attachment: <a href="{{ asset('storage/'.$vp->attachment) }}" target="_blank">View</a>
            </div>
        @endif

        <div class="mt-5 d-flex justify-content-between">
            <div class="text-center" style="width:200px">
                _______________________<br>
                Receiver Signature
            </div>

            <div class="text-center" style="width:200px">
                _______________________<br>
                Authorized Signature
            </div>
        </div>
    </div>
</div>

@endsection
