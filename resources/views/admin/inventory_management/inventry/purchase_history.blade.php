@extends('admin.layouts.main')

@section('title')
Purchase History
@stop

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center my-4">
        <div class="col-12">
            <div class="card basic-form shadow-sm">
                <div class="card-body table-responsive">

                    <table class="table table-bordered table-striped mb-0" id="data_table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer Name</th>
                                <th>Number</th>
                                <th>Voucher ID</th>
                                <th>Card Number</th>
                                <th>Purchase Date</th>
                                <th>Total Sum</th>
                                <th>Total Price</th>
                                <th>Item List</th>
                                <th>Transaction ID</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Discount Applied</th>
                                <th>Created By</th>
                                <th>Notes</th>
                                <th>Deleted At</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchaseHistory as $key => $history)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$history->customrer_name ?? "NONE"}}</td>
                                <td>Number</td>
                                <td>null</td>
                                <td>null</td>
                                <td>2025-02-12 00:00:00</td>
                                <td>520.00</td>
                                <td>260.00</td>
                                <td>
                                    <ul>
                                        <li><strong>Card:</strong> null</li>
                                        <li><strong>Name:</strong> Notebook, Pen, Math Register</li>
                                        <li><strong>Price:</strong> 10.00, 10.00, 10.00</li>
                                        <li><strong>Total:</strong> 520.00</li>
                                        <li><strong>Voucher:</strong> null</li>
                                        <li><strong>Discount:</strong> 50</li>
                                        <li><strong>Quantity:</strong> 1, 1, 50</li>
                                        <li><strong>Total Price:</strong> 260.00</li>
                                        <li><strong>Inventory ID:</strong> 12, 11, 13</li>
                                        <li><strong>Payment Method:</strong> cash</li>
                                    </ul>
                                </td>
                                <td>null</td>
                                <td>cash</td>
                                <td>completed</td>
                                <td>50.00</td>
                                <td>1</td>
                                <td>null</td>
                                <td>null</td>
                                <td>2025-02-12 10:47:57</td>
                                <td>2025-02-12 10:47:57</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('js')

@endsection