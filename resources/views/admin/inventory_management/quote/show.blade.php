@extends('admin.layouts.main')

@section('title', 'View Quote')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header">
            <h4>Quote Details</h4>
        </div>
        <div class="card-body">
            <p><strong>Branch:</strong> {{ $quote->branch->name }}</p>
            <p><strong>Supplier:</strong> {{ $quote->supplier->name }}</p>
            <p><strong>Quote Date:</strong> {{ $quote->quote_date }}</p>
            <p><strong>Due Date:</strong> {{ $quote->due_date }}</p>
            <p><strong>Comments:</strong> {{ $quote->comments ?? '-' }}</p>

            <hr>
            <h5>Items</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quote->quoteItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->item->name }}</td>
                            <td>{{ $item->item->measuring_unit }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

           <a href="#" id="backBtn" class="btn btn-secondary mt-3">Back</a>

            <script>
                document.getElementById('backBtn').addEventListener('click', function (e) {
                    e.preventDefault();

                    // get current URL path (e.g., /inventory/quotes/food/12)
                    const pathParts = window.location.pathname.split('/');

                    // find type (food/stationary/uniform)
                    const type = ['food', 'stationary', 'uniform'].find(t => pathParts.includes(t)) || 'stationary';

                    // redirect accordingly
                    window.location.href = `/inventory/quotes/${type}`;
                });
            </script>
        </div>
    </div>
</div>
@endsection
