<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Ledger - {{ $student->fullname ?? 'Student' }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 30px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px double #333;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #f9f9f9;
        }
        .info-table th {
            background-color: #e9ecef;
            text-align: left;
            padding: 10px;
            width: 25%;
            font-weight: bold;
        }
        .info-table td {
            padding: 10px;
            background-color: #fff;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table.data-table th {
            background-color: #f0f0f0;
            padding: 10px;
            text-align: left;
            border: 1px solid #999;
            font-weight: bold;
        }
        table.data-table td {
            padding: 10px;
            border: 1px solid #999;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            color: white;
            display: inline-block;
        }
        .badge-success { background: #28a745; }
        .badge-info { background: #17a2b8; }
        .badge-warning { background: #ffc107; color: #212529; }
        .badge-danger { background: #dc3545; }
        .summary-box {
            background: #f8f9fa;
            padding: 15px;
            border-left: 5px solid #007bff;
            margin: 25px 0;
            border-radius: 4px;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .total-row {
            background-color: #f0f0f0 !important;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Student Fee Ledger</h1>
        <p><strong>{{ $student->fullname }}</strong></p>
        <p>
            Student ID: <strong>{{ $student->student_id }}</strong> | 
            Class: <strong>{{ $student->academicClass->name ?? 'N/A' }}</strong> | 
            Session: <strong>{{ $student->academicSession->name ?? 'N/A' }}</strong>
        </p>
    </div>



    <!-- Summary Box -->
    @php
        $totalCollected = $collections->sum('paid_amount');
        $totalDiscounts = $adjustments->where('adjustment_type', 'discount')->sum('amount') ?? 0;
        $totalRefunds = $adjustments->where('adjustment_type', 'refund')->sum('amount') ?? 0;
        $netCollected = $totalCollected - $totalRefunds;
        // Note: Outstanding balance needs your actual billing logic
        // This is a placeholder - update if you have actual billed amount
        $outstandingBalance = 0; // Replace with real logic if available
    @endphp


    <!-- Fee Collections Table -->
    <h3>Fee Collections ({{ $collections->count() }})</h3>
    @if($collections->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th width="12%">Date</th>
                    <th width="14%">Amount</th>
                    <th width="18%">Payment Method</th>
                    <th width="12%">Status</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($collections as $collection)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($collection->collection_date)->format('d M Y') }}</td>
                        <td class="text-right"><strong>Rs. {{ number_format($collection->paid_amount) }}</strong></td>
                        <td>
                            <span class="badge badge-info">
                                {{ ucfirst(str_replace('_', ' ', $collection->payment_method)) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $collection->status == 'paid' ? 'badge-success' : 'badge-warning' }}">
                                {{ ucfirst($collection->status) }}
                            </span>
                        </td>
                        <td>{{ $collection->remarks ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
            {{-- <tfoot>
                <tr class="total-row">
                    <td><strong>Total Collected</strong></td>
                    <td class="text-right"><strong>Rs. {{ number_format($collections->sum('paid_amount')) }}</strong></td>
                    <td colspan="3"></td>
                </tr>
            </tfoot> --}}
        </table>
    @else
        <p style="text-align:center; color:#888; font-style:italic; padding:20px; background:#f9f9f9;">
            No fee collections recorded yet.
        </p>
    @endif

    <!-- Fee Adjustments Table -->
    @if($adjustments->count() > 0)
        <h3 style="margin-top: 30px;">Fee Adjustments ({{ $adjustments->count() }})</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @foreach($adjustments as $adj)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($adj->created_at)->format('d M Y') }}</td>
                        <td>
                            <span class="badge {{ $adj->adjustment_type == 'discount' ? 'badge-success' : 'badge-danger' }}">
                                {{ ucfirst($adj->adjustment_type) }}
                            </span>
                        </td>
                        <td class="text-right">Rs. {{ number_format($adj->amount) }}</td>
                        <td>{{ $adj->reason ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    
    
    <div class="summary-box">
        <h3>Fee Summary</h3>
        <table width="100%">
            <tr>
                <td><strong>Total Amount Collected:</strong></td>
                <td class="text-right"><strong>Rs. {{ number_format($totalCollected) }}</strong></td>
            </tr>
            @if($totalDiscounts > 0)
            <tr>
                <td><strong>Total Discounts Given:</strong></td>
                <td class="text-right">Rs. {{ number_format($totalDiscounts) }}</td>
            </tr>
            @endif
            @if($totalRefunds > 0)
            <tr>
                <td><strong>Total Refunds:</strong></td>
                <td class="text-right">Rs. {{ number_format($totalRefunds) }}</td>
            </tr>
            @endif
            <tr style="border-top: 2px solid #007bff; font-size: 15px;">
                <td><strong>Net Amount Received:</strong></td>
                <td class="text-right"><strong>Rs. {{ number_format($netCollected) }}</strong></td>
            </tr>
            @if($outstandingBalance > 0)
            <tr style="color: red;">
                <td><strong>Outstanding Balance:</strong></td>
                <td class="text-right"><strong>Rs. {{ number_format($outstandingBalance) }}</strong></td>
            </tr>
            @endif
        </table>
    </div>

    <div class="footer">
        <p>
            Report Generated on: {{ now()->format('d F Y \a\t h:i A') }} 
            | This is a computer-generated document. No signature required.
        </p>
    </div>

</body>
</html>