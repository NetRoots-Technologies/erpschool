<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order #{{ $purchaseOrder->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h2>Purchase Order #{{ $purchaseOrder->id }}</h2>
    <p><strong>Branch:</strong> {{ $purchaseOrder->branch->name }}</p>
    <p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->name }}</p>
    <p><strong>Order Date:</strong> {{ $purchaseOrder->order_date }}</p>
    <p><strong>Delivery Date:</strong> {{ $purchaseOrder->delivery_date }}</p>
    <p><strong>Delivery Status:</strong> {{ $purchaseOrder->delivery_status }}</p>

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
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 style="text-align:right;">Total: {{ number_format($purchaseOrder->total_amount, 2) }}</h4>

</body>
</html>
