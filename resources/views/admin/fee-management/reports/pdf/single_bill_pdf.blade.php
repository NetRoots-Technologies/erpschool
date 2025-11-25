<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fee Bills Report</title>

    <style>
       
        html, body {
            margin: 0;
            padding: 0;
            font-family: "DejaVu Sans", Arial, sans-serif;
            color: #111;
            font-size: 11px;
        }

        .wrapper {
            width: 100%;
            box-sizing: border-box;
            padding: 6px;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #e2e2e2;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        .logo {
            width: 70px;
            height: 70px;
            border-radius: 6px;
            overflow: hidden;
            margin-right: 12px;
            background: #f4f4f4;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .school-info {
            flex: 1;
        }
        .school-title {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
        }
        .school-sub {
            margin: 0;
            font-size: 10.5px;
            color: #555;
        }

        .meta {
            text-align: right;
            font-size: 10px;
            color: #333;
        }

        /* Table */
        table.report {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            table-layout: fixed;
        }
        table.report thead th {
            background: #f7f7f7;
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 10.5px;
            font-weight: 700;
            text-align: left;
        }
        table.report tbody td {
            padding: 6px 8px;
            border: 1px solid #e6e6e6;
            font-size: 10.5px;
            vertical-align: top;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        table.report tbody tr:nth-child(odd) {
            background: #ffffff;
        }
        table.report tbody tr:nth-child(even) {
            background: #fbfbfb;
        }

        

        /* Compact row style */
        .compact th, .compact td { padding: 6px 6px; }

        /* Footer (position fixed) */
        .footer {
            position: fixed;
            bottom: 6mm;
            left: 12mm;
            right: 12mm;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 6px;
            display: flex;
            justify-content: space-between;
        }

        /* prevent rows split badly across pages */
        tr { page-break-inside: avoid; }

        /* make long names wrap in a small column if needed */
        .wrap { white-space: normal; word-wrap: break-word; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div class="logo">
            {{-- Put your logo here. Use absolute path or data URI if needed --}}
            <img src="{{ public_path('images/school_logo.png') }}" alt="Logo" style="max-width:100%; max-height:100%;">
        </div>
        <div class="school-info">
            <p class="school-title">{{ config('app.name', 'School Name') }}</p>
            <p class="school-sub">Address line 1 &mdash; City · Phone: 123-456-7890 · Email: info@example.com</p>
        </div>
        <div class="meta">
            <strong>Fee Bills Report</strong><br>
            @if($bill)
                <span class="small">Billing Month: {{ $bill->billing_month }}</span>
            @else
                <span class="small">All Billing Months</span>
            @endif
            <br>
            <span class="small">Generated: {{ now()->format('d-M-Y H:i') }}</span>
        </div>
    </div>

    <table class="report compact" cellspacing="0" cellpadding="0">
        <thead>
            <tr>


                <th>Challan Number</th>
                <th>Bill Month</th>
                <th>Student Name</th>             
                <th>Father Name</th>
                <th>Class Name</th>
                <th>Payment Status</th>
                <th>Billed Amount</th>
                <th>Paid Amount</th>
                <th>Total Outstanding</th>
            </tr>
        </thead>
        <tbody>
            
               
                <tr>
                    <td class="small">{{ $bill->challan_number ?? '-' }}</td>
                    <td class="small">{{ $bill->billing_month ?? '-' }}</td>
                    <td class="small wrap">{{ $bill->student->full_name ?? '—' }} ({{ $bill->student->student_id ?? '-' }})</td>
                    <td class="small wrap">{{ $bill->student->father_name ?? '-' }}</td>
                    <td class="small">{{ $bill->student->academicClass->name ?? '-' }}</td>
                    <td class="small">{{ ucfirst($bill->status ?? '—') }}</td>
                    <td class="small text-right">{{ number_format($bill->total_amount ?? 0, 2) }}</td>
                    <td class="small text-right">{{ number_format($bill->paid_amount ?? 0, 2) }}</td>
                    <td class="small text-right">{{ number_format($bill->outstanding_amount ?? 0, 2) }}</td>
                </tr>
      
        </tbody>
    </table>

    <div style="height: 36px;"></div> {{-- spacing before footer --}}
</div>

<div class="footer">
    <div>Prepared by: {{ auth()->user()->name ?? 'Admin' }}</div>
    <div>Page <span class="pagenum"></span></div>
</div>

<!-- Optional: add page numbering script for some PDF engines -->
<script type="text/php">
    if (isset($pdf)) {
        $font = $fontMetrics->get_font("DejaVu Sans", "normal");
        $pdf->page_text(520, 820, "Page {PAGE_NUM} / {PAGE_COUNT}", $font, 9, array(0,0,0));
    }
</script>
</body>
</html>
