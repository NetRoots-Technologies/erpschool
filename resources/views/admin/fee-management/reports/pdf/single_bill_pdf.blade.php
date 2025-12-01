<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fee Bill — {{ $bill->student->full_name ?? 'Bill' }}</title>

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
            padding: 10px;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #e2e2e2;
            padding-bottom: 10px;
            margin-bottom: 10px;
            gap: 12px;
        }

        /* logo left - improved design */
        .logo {
            width: 80px;               /* slightly bigger for clarity */
            height: 80px;
            border-radius: 8px;        /* keep subtle rounding */
            overflow: hidden;
            margin-right: 12px;
            background: transparent;   /* remove strong bg so transparent logos look better */
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            padding: 6px;              /* small padding so round logos don't touch edges */
            flex: 0 0 80px;
        }
        /* ensure image preserves aspect ratio and is centered */
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* keep aspect ratio */
            display: block;
        }

        /* centered title/meta */
        .header-center {
            flex: 1;
            text-align: center;
            line-height: 1;
        }
        .report-title {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: .2px;
        }
        .report-sub {
            margin: 2px 0 0 0;
            font-size: 11px;
            color: #333;
        }
        .report-meta {
            margin-top: 6px;
            font-size: 10px;
            color: #666;
        }

        /* right small meta (kept but reduced) */
        .meta-right {
            width: 140px;
            text-align: right;
            font-size: 10px;
            color: #333;
        }

        /* Student info block (under header) */
        .student-info {
            margin: 10px 0;
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }
        .student-card {
            border: 1px solid #eef2f6;
            padding: 8px 10px;
            border-radius: 6px;
            background: #fbfbfc;
            font-size: 11px;
        }
        .student-card .label { color:#6b7280; font-size: 10px; display:block; }
        .student-card .value { font-weight:600; margin-top:2px; }

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
        table.report tbody tr:nth-child(odd) { background: #ffffff; }
        table.report tbody tr:nth-child(even) { background: #fbfbfb; }

        .text-right { text-align: right; }
        .wrap { white-space: normal; word-wrap: break-word; }

        /* status badges */
        .status {
            display:inline-block;
            padding:3px 7px;
            border-radius:999px;
            font-size:10px;
            font-weight:700;
            color:#fff;
        }
        .status-paid { background:#16a34a; }       /* green */
        .status-partial { background:#f59e0b; }    /* amber */
        .status-unpaid { background:#ef4444; }     /* red */
        .status-other { background:#6b7280; }      /* gray */

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

        /* small helpers */
        .muted { color:#6b7280; font-size: 10px; }
         .logo-container {
        width: 100%;
        text-align: left;   /* Right Side Move */
        margin-bottom: 10px;
    }

    .school-logo {
        height: 70px;        /* Increase Size */
        /* width: auto; */
        /* margin-right: 10px;  Move slightly more to right */
    }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <div class="logo">
            @php
                $logoPath = public_path('cornerstone_logo.png');

                if (!file_exists($logoPath)) {
                    // Download once and store local
                    try {
                        $imgData = file_get_contents("https://cornerstone.pk/wp-content/uploads/2022/08/cropped-Round-and-line-logo-1-240x80.png");
                        if ($imgData) file_put_contents($logoPath, $imgData);
                    } catch (\Exception $e) {
                        // ignore download error
                    }
                }

                $base64Logo = file_exists($logoPath)
                    ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
                    : null;
            @endphp

            @if($base64Logo)
                {{-- <img src="{{ $base64Logo }}" alt="Logo"> --}}
                
        <div class="logo-container">
            <img src="{{ $base64Logo }}" alt="Logo" class="school-logo">
        </div>
            @else
                <span style="font-size:12px;color:#888;">Logo</span>
            @endif
        </div>


        <div class="header-center">
            <p class="report-title">Fee Bills Report</p>

            {{-- center meta: Billing Month & Generated --}}
            <p class="report-meta">
                Billing Month:
                <strong>{{ $bill->billing_month ?? ($month ?? 'All') }}</strong>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                Generated: <strong>{{ \Carbon\Carbon::now()->format('d-M-Y H:i') }}</strong>
            </p>
        </div>
    </div>

    {{-- Student info block (if present) --}}
    @if(isset($bill->student) && $bill->student)
        <div class="student-info" style="display:flex; gap:18px; align-items:center; margin:10px 0; flex-wrap:wrap;">
            <div class="student-card" style="min-width:220px;">
                <span class="label" style="font-weight:bold; margin-right:4px;">Student:</span>
                <span class="value">
                    {{ $bill->student->full_name ?? '—' }}
                    ({{ $bill->student->student_id ?? '-' }})
                </span>
            </div>

            <div class="student-card">
                <span class="label" style="font-weight:bold; margin-right:4px;">Father:</span>
                <span class="value">{{ $bill->student->father_name ?? '—' }}</span>
            </div>

            <div class="student-card">
                <span class="label" style="font-weight:bold; margin-right:4px;">Class:</span>
                <span class="value">{{ $bill->student->academicClass->name ?? '—' }}</span>
            </div>
        </div>
    @endif

    {{-- Bills table --}}
    <table class="report compact" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th style="width:120px">Challan Number</th>
                <th style="width:90px">Bill Month</th>
                <th style="width:110px">Payment Status</th>
                <th style="width:90px" class="text-right">Billed Amount</th>
                <th style="width:90px" class="text-right">Paid Amount</th>
                <th style="width:90px" class="text-right">Outstanding</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="small">{{ $bill->challan_number ?? '-' }}</td>
                <td class="small">{{ $bill->billing_month ?? '-' }}</td>
                <td class="small text-center">
                    @php
                        $status = strtolower(trim($bill->status ?? '—'));
                        if($status === 'paid') $cls = 'status-paid';
                        elseif(in_array($status, ['partial','partial_paid','partially_paid'])) $cls = 'status-partial';
                        elseif(in_array($status, ['unpaid','pending'])) $cls = 'status-unpaid';
                        else $cls = 'status-other';
                    @endphp
                    <span class="status {{ $cls }}">{{ ucfirst(str_replace('_',' ',$status)) }}</span>
                </td>

                <td class="small text-left">{{ number_format($bill->total_amount ?? 0, 2) }}</td>
                <td class="small text-left">{{ number_format($bill->paid_amount ?? 0, 2) }}</td>
                <td class="small text-left">{{ number_format($bill->outstanding_amount ?? 0, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div style="height: 36px;"></div> {{-- spacing before footer --}}
</div>

<div class="footer">
    <div>Prepared by: {{ auth()->user()->name ?? 'Admin' }}</div>
</div>

<!-- Page numbering for supported PDF generators -->
<script type="text/php">
    if (isset($pdf)) {
        $font = $fontMetrics->get_font("DejaVu Sans", "normal");
        $pdf->page_text(520, 820, "Page {PAGE_NUM} / {PAGE_COUNT}", $font, 9, array(0,0,0));
    }
</script>
</body>
</html>
