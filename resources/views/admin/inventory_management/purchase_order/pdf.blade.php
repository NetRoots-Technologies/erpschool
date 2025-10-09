<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order #{{ $purchaseOrder->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
            .sig-row      { width:100%; border-collapse:collapse; margin-top:30px; table-layout:fixed; }
            .sig-cell     { width:16.66%; text-align:center; vertical-align:bottom; padding:0 6px; }
            .sig-line     { width:120px; height:0; margin:0 auto 4px auto; border-top:1px solid #000; }
            .sig-under    { font-style:italic; font-size:11px; line-height:1.2; }
            .sig-above    { font-size:10px; line-height:1.2; margin-bottom:2px; } /* name/date above the line */

    </style>
</head>
<body>

    <h2>Purchase Order #{{ $purchaseOrder->id }}</h2>
    <p><strong>Branch:</strong> {{ $purchaseOrder->branch->name }}</p>
    <p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->name }}</p>
    <p><strong>Order Date:</strong> {{ $purchaseOrder->order_date }}</p>
    <p><strong>Delivery Date:</strong> {{ $purchaseOrder->delivery_date }}</p>
    <p><strong>Delivery Status:</strong> {{ $purchaseOrder->delivery_status }}</p>
    <p><strong>Comments:</strong> {{ $purchaseOrder->description }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
       <tbody>
            @foreach($purchaseOrder->purchaseOrderItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->measuring_unit }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->total_price, 2) }}</td>
                    {{-- or compute: number_format($item->unit_price * $item->quantity, 2) --}}
                </tr>
            @endforeach
            </tbody>

    </table>

    <h4 style="text-align:right;">Total: {{ number_format($purchaseOrder->total_amount, 2) }}</h4>

<table class="sig-row">
  <tr>
    <!-- Prepared By (name + date above the line) -->
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


</body>
</html>
