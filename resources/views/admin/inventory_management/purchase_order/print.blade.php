<!DOCTYPE html>
<html>
<head>
    <title>Print Purchase Order</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
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
        @foreach ($purchaseOrder->purchaseOrderItems as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->item->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->measuring_unit }}</td>
            <td>{{ number_format($item->price, 2) }}</td>
            <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<p style="text-align:right;"><strong>Total: </strong>{{ number_format($purchaseOrder->total_amount, 2) }}</p>


        <br><br><br>
    <table style="width:100%; text-align:center; margin-top: 50px;">
        <tr>
            <td>
                <hr style="width:80%; margin: auto;">
                <em>Procurement Officer</em><br>
                <strong>Prepared By</strong>
            </td>
            <td>
                <hr style="width:80%; margin: auto;">
                <br>
                <strong>Purchase Manager</strong>
            </td>
            <td>
                <hr style="width:80%; margin: auto;">
                <br>
                <strong>Approved By</strong>
            </td>
        </tr>
    </table>

<script>
    // auto print
    window.print();
</script>

</body>
</html>
