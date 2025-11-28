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
            padding: 8px;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #e2e2e2;
            padding-bottom: 8px;
            margin-bottom: 8px;
            gap: 12px;
        }
        .logo {
            width: 70px;
            height: 70px;
            border-radius: 6px;
            overflow: hidden;
            margin-right: 12px;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            flex: 0 0 70px;
        }
        .logo img { display: block; max-width: 100%; height: auto; object-fit: contain; }

        .school-info { flex: 1; }
        .school-title { font-size: 16px; font-weight: 700; margin: 0; }
        .school-sub { margin: 0; font-size: 11px; color: #555; }
        .meta { text-align: right; font-size: 10px; color: #333; min-width:180px; }

        /* student info */
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
            font-size: 10px;
        }
        .student-card .label { color:#6b7280; font-size: 10px; display:block; }
        .student-card .value { font-weight:600; margin-top:2px; }

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
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        table.report tbody tr:nth-child(odd) { background: #ffffff; }
        table.report tbody tr:nth-child(even) { background: #fbfbfb; }

        .small { font-size: 10px; color: #444; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .status {
            display:inline-block;
            padding:3px 8px;
            border-radius:999px;
            font-size:10px;
            font-weight:700;
            color:#fff;
        }
        .status-paid { background:#16a34a; }       /* green */
        .status-partial { background:#f59e0b; }    /* amber */
        .status-unpaid { background:#ef4444; }     /* red */
        .status-other { background:#6b7280; }      /* gray */

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
            align-items: center;
        }

        /* prevent rows split badly across pages */
        tr { page-break-inside: avoid; }

        /* small helpers */
        .muted { color:#6b7280; font-size: 10px; }
        .wrap { white-space: normal; word-wrap: break-word; overflow-wrap: break-word; }
        .meta {
                text-align: center;
                font-weight: bold;
                line-height: 1.4;
            }

    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <div class="logo">
            @php
                // try to source logo file locally; fall back to remote download once
                $logoPath = public_path('cornerstone_logo.png');
                if (! file_exists($logoPath)) {
                    try {
                        $remote = "https://cornerstone.pk/wp-content/uploads/2022/08/cropped-Round-and-line-logo-1-240x80.png";
                        $imgData = @file_get_contents($remote);
                        if ($imgData) file_put_contents($logoPath, $imgData);
                    } catch (\Exception $e) {
                        // ignore
                    }
                }

                $logoDataUri = file_exists($logoPath)
                    ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
                    : null;
            @endphp

            @if($logoDataUri)
                <img src="{{ $logoDataUri }}" alt="Logo">
            @else
                {{-- fallback text or a packaged local asset --}}
                @if(file_exists(public_path('images/school_logo.png')))
                    <img src="{{ public_path('images/school_logo.png') }}" alt="Logo">
                @else
                    <span style="font-size:12px;color:#888;">Logo</span>
                @endif
            @endif
        </div>
        <div class="meta" style="text-align: center; line-height: 1.4; font-weight: bold;">
            <strong>Fee Bills Report</strong><br>

            @if(!empty($filterMonth ?? $month))
                <span class="small">Billing Month: {{ $filterMonth ?? $month }}</span>
            @else
                <span class="small">All Billing Months</span>
            @endif

            <br>
            <span class="small">Generated: {{ \Carbon\Carbon::now()->format('d-M-Y H:i') }}</span>
        </div>

    </div>

    {{-- Optional student summary when single $bill passed --}}
    @if(isset($bill) && $bill && isset($bill->student))
        <div class="student-info">
            <div class="student-card" style="min-width:220px;">
                <span class="label" style="font-weight:bold;">Student:</span>
                <span class="value">{{ $bill->student->full_name ?? '—' }} <small>({{ $bill->student->student_id ?? '-' }})</small></span>
            </div>

            <div class="student-card">
                <span class="label" style="font-weight:bold;">Father:</span>
                <span class="value">{{ $bill->student->father_name ?? '—' }}</span>
            </div>

            <div class="student-card">
                <span class="label" style="font-weight:bold;">Class:</span>
                <span class="value">{{ $bill->student->academicClass->name ?? '—' }}</span>
            </div>
        </div>
    @endif

    {{-- Table: handle both collection ($feeBillingData) or single $bill --}}
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
            @if(isset($feeBillingData) && $feeBillingData->count())
                @foreach($feeBillingData as $bill)
                    @php
                        $student = optional($bill->student);
                        $status = strtolower(trim($bill->status ?? ''));
                        if($status === 'paid') $statusCls = 'status-paid';
                        elseif(in_array($status, ['partial','partial_paid','partially_paid'])) $statusCls = 'status-partial';
                        elseif(in_array($status, ['unpaid','pending'])) $statusCls = 'status-unpaid';
                        else $statusCls = 'status-other';
                    @endphp
                    <tr>
                        <td class="small">{{ $bill->challan_number ?? '-' }}</td>
                        <td class="small">{{ $bill->billing_month ?? '-' }}</td>
                        <td class="small wrap">{{ $student->full_name ?? '—' }} <br><small>({{ $student->student_id ?? '-' }})</small></td>
                        <td class="small wrap">{{ $student->father_name ?? '-' }}</td>
                        <td class="small">{{ $student->academicClass->name ?? '-' }}</td>
                        <td class="small text-center"><span class="status {{ $statusCls }}">{{ ucfirst(str_replace('_',' ',$status ?: '—')) }}</span></td>
                        <td class="small text-left">{{ number_format($bill->total_amount ?? 0, 2) }}</td>
                        <td class="small text-left">{{ number_format($bill->paid_amount ?? 0, 2) }}</td>
                        <td class="small text-left">{{ number_format($bill->outstanding_amount ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            @elseif(isset($bill) && $bill)
                @php
                    $student = optional($bill->student);
                    $status = strtolower(trim($bill->status ?? ''));
                    if($status === 'paid') $statusCls = 'status-paid';
                    elseif(in_array($status, ['partial','partial_paid','partially_paid'])) $statusCls = 'status-partial';
                    elseif(in_array($status, ['unpaid','pending'])) $statusCls = 'status-unpaid';
                    else $statusCls = 'status-other';
                @endphp
                <tr>
                    <td class="small">{{ $bill->challan_number ?? '-' }}</td>
                    <td class="small">{{ $bill->billing_month ?? '-' }}</td>
                    <td class="small wrap">{{ $student->full_name ?? '—' }} <br><small>({{ $student->student_id ?? '-' }})</small></td>
                    <td class="small wrap">{{ $student->father_name ?? '-' }}</td>
                    <td class="small">{{ $student->academicClass->name ?? '-' }}</td>
                    <td class="small text-center"><span class="status {{ $statusCls }}">{{ ucfirst(str_replace('_',' ',$status ?: '—')) }}</span></td>
                    <td class="small text-right">{{ number_format($bill->total_amount ?? 0, 2) }}</td>
                    <td class="small text-right">{{ number_format($bill->paid_amount ?? 0, 2) }}</td>
                    <td class="small text-right">{{ number_format($bill->outstanding_amount ?? 0, 2) }}</td>
                </tr>
            @else
                <tr>
                    <td class="small text-center" colspan="9">No billing records found.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div style="height: 36px;"></div> {{-- spacing before footer --}}
</div>

<div class="footer">
    <div>Prepared by: {{ auth()->user()->name ?? 'Admin' }}</div>
    <div>Page <span class="pagenum"></span></div>
</div>

<!-- Page numbering for many PDF engines (e.g. DOMPDF, Snappy with wkhtmltopdf via barryvdh/laravel-snappy) -->
<script type="text/php">
    if (isset($pdf)) {
        $font = $fontMetrics->get_font("DejaVu Sans", "normal");
        /* Coordinates adjusted for A3 landscape */
        $pdf->page_text($pdf->get_width() - 120, $pdf->get_height() - 30, "Page {PAGE_NUM} / {PAGE_COUNT}", $font, 9, array(0,0,0));
    }
</script>

</body>
</html>
