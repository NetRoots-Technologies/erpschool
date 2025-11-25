<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fee Bills Report</title>

    <style>
        /* Use A3 landscape paper size for wide tables */
        @page { size: A3 landscape; margin: 12mm 10mm; }

        html, body {
            margin: 0;
            padding: 0;
            font-family: "DejaVu Sans", Arial, sans-serif;
            color: #111;
            font-size: 10px;
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
        .school-info { flex: 1; }
        .school-title { font-size: 16px; font-weight: 700; margin: 0; }
        .school-sub { margin: 0; font-size: 11px; color: #555; }
        .meta { text-align: right; font-size: 10px; color: #333; }

        /* Table */
        table.report {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            table-layout: auto; /* allow flexible column widths */
            word-break: break-word;
            overflow-wrap: break-word;
        }
        table.report thead th {
            background: #f7f7f7;
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 10px;
            font-weight: 700;
            text-align: left;
        }
        table.report tbody td {
            padding: 6px 8px;
            border: 1px solid #e6e6e6;
            font-size: 10px;
            vertical-align: top;
            /* allow wrapping instead of single-line overflow */
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        table.report tbody tr:nth-child(odd) { background: #ffffff; }
        table.report tbody tr:nth-child(even) { background: #fbfbfb; }

        .small { font-size: 10px; color: #444; }
        .text-right { text-align: right; }
        .status-unpaid { color: #b91c1c; font-weight: 700; } /* red */
        .status-paid   { color: #0f766e; font-weight: 700; } /* teal */

        /* Compact row style */
        .compact th, .compact td { padding: 6px 6px; }

        /* Footer (position fixed) */
        .footer {
            position: fixed;
            bottom: 8mm;
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

        /* allow longer text to flow to multiple lines */
        .wrap { white-space: normal; word-wrap: break-word; overflow-wrap: break-word; }

        /* reduce logo image bleed on some PDF engines */
        .logo img { display: block; max-width: 100%; height: auto; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div class="logo">
            {{-- Put your logo here. Use absolute path or data URI if needed --}}
            <img src="{{ public_path('images/school_logo.png') }}" alt="Logo">
        </div>
        <div class="school-info">
            <p class="school-title">{{ config('app.name', 'School Name') }}</p>
            <p class="school-sub">Address line 1 &mdash; City · Phone: 123-456-7890 · Email: info@example.com</p>
        </div>
        <div class="meta">
            <strong>Fee Bills Report</strong><br>
            @if(!empty($filterMonth))
                <span class="small">Billing Month: {{ $filterMonth }}</span>
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
                <th style="width:9%;">Challan Number</th>
                <th style="width:9%;">Bill Month</th>
                <th style="width:22%;">Student Name</th>
                <th style="width:18%;">Father Name</th>
                <th style="width:10%;">Class Name</th>
                <th style="width:8%;">Payment Status</th>
                <th style="width:8%;" class="text-right">Billed Amount</th>
                <th style="width:8%;" class="text-right">Paid Amount</th>
                <th style="width:8%;" class="text-right">Outstanding</th>
            </tr>
        </thead>
        <tbody>
            @foreach($feeBillingData as $bill)
                @php
                    $student = optional($bill->student);
                    $statusClass = strtolower($bill->status ?? '') === 'paid' ? 'status-paid' : 'status-unpaid';
                @endphp
                <tr>
                    <td class="small">{{ $bill->challan_number ?? '-' }}</td>
                    <td class="small">{{ $bill->billing_month ?? '-' }}</td>
                    <td class="small wrap">{{ $student->full_name ?? '—' }} <br><small>({{ $student->student_id ?? '-' }})</small></td>
                    <td class="small wrap">{{ $student->father_name ?? '-' }}</td>
                    <td class="small">{{ $student->academicClass->name ?? '-' }}</td>
                    <td class="small {{ $statusClass }}">{{ ucfirst($bill->status ?? '—') }}</td>
                    <td class="small text-right">{{ number_format($bill->total_amount ?? 0, 2) }}</td>
                    <td class="small text-right">{{ number_format($bill->paid_amount ?? 0, 2) }}</td>
                    <td class="small text-right">{{ number_format($bill->outstanding_amount ?? 0, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="height: 36px;"></div> {{-- spacing before footer --}}
</div>

<div class="footer">
    <div>Prepared by: {{ auth()->user()->name ?? 'Admin' }}</div>
    <div>Page <span class="pagenum"></span></div>
</div>

<!-- Page numbering for many PDF engines -->
<script type="text/php">
    if (isset($pdf)) {
        $font = $fontMetrics->get_font("DejaVu Sans", "normal");
        /* Coordinates adjusted for A3 landscape */
        $pdf->page_text($pdf->get_width() - 80, $pdf->get_height() - 30, "Page {PAGE_NUM} / {PAGE_COUNT}", $font, 9, array(0,0,0));
    }
</script>
</body>
</html>
