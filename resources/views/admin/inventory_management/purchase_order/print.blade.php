<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Purchase Order #{{ $purchaseOrder->id }}</title>
    <style>
        /* Reset-ish */
        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: Arial, sans-serif; font-size: 14px; color:#000; margin: 16px; }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-top: 16px; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; vertical-align: middle; word-wrap: break-word; }
        th { background: #f2f2f2; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        tr { page-break-inside: avoid; }

        /* Totals */
        .text-right { text-align: right; }
        .mt-8  { margin-top: 8px; }
        .mt-16 { margin-top: 16px; }
        .mt-24 { margin-top: 24px; }

        /* Signature row */
        .sig-row { width:100%; border-collapse:collapse; margin-top:30px; table-layout:fixed; }
        .sig-cell { width:16.66%; text-align:center; vertical-align:bottom; padding:0 6px; border:0; }
        .sig-line { width:120px; height:0; margin:0 auto 4px auto; border-top:1px solid #000; }
        .sig-under { font-style:italic; font-size:11px; line-height:1.2; }
        .sig-above { font-size:10px; line-height:1.2; margin-bottom:2px; }

        /* Make sure NOTHING is hidden in print */
        @media print {
            body { margin: 10mm; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            /* Force visibility */
            html, body, table, thead, tbody, tfoot, tr, th, td, h1, h2, h3, h4, p, div, span {
                visibility: visible !important;
                overflow: visible !important;
            }
        }
        @page { margin: 12mm; }
    </style>
</head>
<body>

    <h2 style="margin:0 0 10px 0;">Purchase Order #{{ $purchaseOrder->id }}</h2>

    <table style="margin-top:8px;">
        <tbody>
            <tr>
                <td style="width:33.33%;"><strong>Branch:</strong> {{ $purchaseOrder->branch->name }}</td>
                <td style="width:33.33%;"><strong>Supplier:</strong> {{ $purchaseOrder->supplier->name }}</td>
                <td style="width:33.33%;"><strong>Order Date:</strong> {{ $purchaseOrder->order_date }}</td>
            </tr>
            <tr>
                <td><strong>Delivery Date:</strong> {{ $purchaseOrder->delivery_date }}</td>
                <td><strong>Delivery Status:</strong> {{ $purchaseOrder->delivery_status }}</td>
                <td><strong>Comments:</strong> {{ $purchaseOrder->description }}</td>
            </tr>
        </tbody>
    </table>

    <table class="mt-16">
        <thead>
            <tr>
                <th style="width:5%;">#</th>
                <th style="width:45%;">Item</th>
                <th style="width:10%;">Qty</th>
                <th style="width:10%;">Unit</th>
                <th style="width:15%;">Unit Price</th>
                <th style="width:15%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchaseOrder->purchaseOrderItems as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->measuring_unit }}</td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                       <td>{{ number_format($item->total_price, 2) }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="text-right mt-8"><strong>Total: </strong>{{ number_format($purchaseOrder->total_amount, 2) }}</p>

    <!-- Signatures -->
    <table class="sig-row">
      <tr>
        <td class="sig-cell">
          <div class="sig-above">
            {{ $preparedBy ?? 'Super Admin' }}<br>
            {{ \Carbon\Carbon::parse($preparedAt ?? now())->format('d-M-Y h:i a') }}
          </div>
          <div class="sig-line"></div>
          <div class="sig-under">Prepared By</div>
        </td>
        <td class="sig-cell">
          <div class="sig-line"></div>
          <div class="sig-under">Department Head</div>
        </td>
        <td class="sig-cell">
          <div class="sig-line"></div>
          <div class="sig-under">Store Manager</div>
        </td>
        <td class="sig-cell">
          <div class="sig-line"></div>
          <div class="sig-under">Approved By</div>
        </td>
        <td class="sig-cell">
          <div class="sig-line"></div>
          <div class="sig-under">Admin Manager</div>
        </td>
        <td class="sig-cell">
          <div class="sig-line"></div>
          <div class="sig-under">Accounts Officer</div>
        </td>
      </tr>
    </table>

    <script>
        // Sirf is standalone print page par auto-print
        if (typeof window !== 'undefined' && window.print) {
            setTimeout(function(){ window.print(); }, 50);
        }
    </script>

</body>
</html>
