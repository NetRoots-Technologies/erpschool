<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Supplier Ledger PDF</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 12px; 
            margin: 20px; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px; 
        }
        th, td { 
            border: 1px solid #555; 
            padding: 6px; 
            text-align: left;
        }
        th { 
            background: #f2f2f2; 
        }
        .text-right { 
            text-align: right; 
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
        }
        .header h2 { 
            margin: 0; 
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Supplier Ledger Report</h2>
    <p><strong>{{ $supplier->name }}</strong></p>
    <p>Phone: {{ $supplier->phone ?? '-' }} | Email: {{ $supplier->email ?? '-' }}</p>
</div>

<h3>Purchase Orders ({{ $purchases->count() }})</h3>
@if($purchases->count() > 0)
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Order No</th>
            <th class="text-right">Amount</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($purchases as $p)
        <tr>
            <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}</td>
            <td>{{ $p->order_number ?? '-' }}</td>
            <td class="text-right">Rs. {{ number_format($p->total_amount, 2) }}</td>
            <td>{{ ucfirst($p->status ?? 'N/A') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No purchase orders found.</p>
@endif

<h3>Payments Made ({{ $payments->count() }})</h3>
@if($payments->count() > 0)
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Voucher No</th>
            <th>Invoice No</th>
            <th class="text-right">Amount Paid</th>
            <th>Mode</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $pay)
        <tr>
            <td>{{ \Carbon\Carbon::parse($pay->payment_date)->format('d M Y') }}</td>
            <td>{{ $pay->voucher_no ?? '-' }}</td>
            <td>{{ optional($pay->invoice)->invoice_number ?? '-' }}</td>
            <td class="text-right">Rs. {{ number_format($pay->payment_amount, 2) }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $pay->payment_mode ?? '-')) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No payments found for this supplier.</p>
@endif

<h3>Ledger Summary</h3>
<table>
    <tr>
        <td><strong>Total Purchased</strong></td>
        <td class="text-right">Rs. {{ number_format($totalOrdered, 2) }}</td>
    </tr>
    <tr>
        <td><strong>Total Paid</strong></td>
        <td class="text-right">Rs. {{ number_format($totalPaid, 2) }}</td>
    </tr>
    <tr>
        <td><strong>Outstanding Balance</strong></td>
        <td class="text-right"><strong>Rs. {{ number_format($outstanding, 2) }}</strong></td>
    </tr>
</table>

</body>
</html>