@extends('admin.layouts.main')

@section('title', 'View Purchase Order')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header">
            <h4>Purchase Order Details</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Branch:</strong> {{ $purchaseOrder->branch->name ?? '-' }}</p>
                    <p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->name ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($purchaseOrder->order_date)->format('d-M-Y') }}</p>
                    <p><strong>Delivery Date:</strong> {{ $purchaseOrder->delivery_date ? \Carbon\Carbon::parse($purchaseOrder->delivery_date)->format('d-M-Y') : '-' }}</p>
                    <p><strong>Delivery Status:</strong>
                        <span class="badge bg-{{ $purchaseOrder->delivery_status === 'delivered' ? 'success' : 'warning' }}">
                            {{ ucfirst($purchaseOrder->delivery_status) }}
                        </span>
                    </p>
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Items</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Unit</th>
                            <th class="text-end">Quantity</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Quote Item Price</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @forelse ($purchaseOrder->purchaseOrderItems as $index => $item)
                            @php
                                $grandTotal += $item->total_price;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->item->name ?? '-' }}</td>
                                <td>{{ $item->measuring_unit ?? '-' }}</td>
                                <td class="text-end">{{ $item->quantity }}</td>
                                <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end">{{ number_format($item->quote_item_price, 2) }}</td>
                                <td class="text-end">{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="6" class="text-end">Grand Total</th>
                            <th class="text-end">{{ number_format($grandTotal, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

           
        </div>
    </div>
</div>
@endsection
