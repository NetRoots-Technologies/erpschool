@extends('admin.layouts.main')

@section('title')
    View
@stop

@section('css')
    <style>
        .form-container {
            background: white;
            margin: 0px 30px 30px 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .info-box {
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .info-box div {
            display: flex;
            padding: 5px 0;
        }


        .info-box span {
            flex: 0.47;
        }

        .label {
            font-weight: bold;
            border-radius: 10px;
            padding: 10px;
        }

        .value {
            text-align: center;
            border-radius: 10px;
            padding: 10px;
        }

        table tr th {
            font-weight: 800 !important;
            padding: 10px !important;
        }

        table tr td {
            padding: 5px;
        }

        table tr th,
        td {
            text-align: center;
        }

        table tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        table tr:nth-child(even) {
            background-color: #ffffff;
        }

        select.value {
            border: none;
            background: white;
            text-align: center;
            border-radius: 10px;
            padding: 5px;
            appearance: none;
        }

        select:disabled {
            opacity: 1 !important;
        }

        #deliveryStatus {
            text-align: center;
            margin-left: 127px;
            border-radius: 10px;
            background: white !important;
        }

        #paymentMethod {
            margin-left: 180px;
        }
    </style>
@stop

@section('content')
    <div class="container mt-4">
        <div class="row w-100 mt-4">
            <h3 class="text-22 text-center text-bold w-100 mb-4">Purchase Order</h3>
        </div>
        <div class="form-container p-4">

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="info-box">
                        <div><span class="label">Branch Name:</span> <span class="value"
                                id="{{ $purchase_order->branch->branch_id }}">{{ $purchase_order->branch->name }}</span></div>
                        <div><span class="label">Supplier Name:</span> <span class="value"
                                id="{{ $purchase_order->supplier_id }}">{{ $purchase_order->supplier->name }}</span></div>

                        <div>
                            <span class="label">Delivery Status:</span>
                            <select name="deliveryStatus" id="deliveryStatus" class="form-select">

                                @if ($currentStatus == 'PENDING')
                                    <option value="1" selected disabled>PENDING</option>
                                @elseif ($currentStatus == 'SHIPPED')
                                    <option value="2" selected disabled>SHIPPED</option>
                                @elseif ($currentStatus == 'CANCELLED' || $currentStatus == 'COMPLETED')
                                    <option value="{{ $currentStatus }}" selected disabled>{{ $currentStatus }}</option>
                                @endif

                                @foreach ($delivery_status as $key => $status)
                                    @if ($currentStatus == 'PENDING' && ($status == 'SHIPPED' || $status == 'CANCELLED'))
                                        <option value="{{ $key }}">{{ $status }}</option>
                                    @elseif ($currentStatus == 'SHIPPED' && ($status == 'COMPLETED' || $status == 'CANCELLED'))
                                        <option value="{{ $key }}">{{ $status }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        @if ($currentStatus === 'SHIPPED')
                            <div class="d-flex align-items-center">
                                <span class="label">Select Ledger:</span>
                                <select name="paymentMethod" id="paymentMethod" class="form-select w-100" required>
                                    <option value="" disabled selected>Select Ledger</option>
                                    @foreach ($ledgers as $ledger)
                                        <option value="{{ $ledger->id }}">{{ $ledger->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="d-flex justify-content-center">
                            <button id="confirmOrder" class="btn btn-primary mt-2 ms-5" disabled>OK</button>
                        </div>

                        {{-- <div><span class="label">Payment Status:</span>
                        <select name="paymentStatus" id="paymentStatus" class="form-select">
                            <option value="{{ $purchase_order->payment_status }}" selected disabled>{{ $purchase_order->payment_status }}</option>
                            @foreach ($payment_status as $key => $value)
                                @if ($value != $purchase_order->payment_status)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div> --}}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-box">
                        <div><span class="label">Order Date:</span> <span
                                class="value">{{ $purchase_order->order_date }}</span></div>
                        <div><span class="label">Delivery Date:</span> <span
                                class="value">{{ $purchase_order->delivery_date }}</span></div>
                        <div><span class="label">Total Amount:</span> <span
                                class="value">{{ $purchase_order->total_amount }}</span></div>

                        {{-- <div><span class="label">Payment Method:</span>
                        <select name="paymentMethod" id="paymentMethod" class="form-select">
                            <option value="{{ $purchase_order->payment_method }}" selected disabled>{{ $purchase_order->payment_method }}</option>
                            @foreach ($payment_methods as $key => $value)
                                @if ($value != $purchase_order->payment_method)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div> --}}
                    </div>
                </div>



                <div class="item_info mt-4">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase_order->purchaseOrderItems as $order)
                                <tr>
                                    <td>{{ $order->item->name }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ $order->unit_price }}</td>
                                    <td>{{ $order->total_price }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @endsection

    @section('js')
        <script type="text/javascript" defer>
        $(document).ready(function() {
            'use strict';

            $("#deliveryStatus, #paymentMethod").select2();
            var $deliveryStatus = $('#deliveryStatus');
            var $paymentMethod = $('#paymentMethod');
            var $confirmOrder = $('#confirmOrder');

            var currentStatus = @json($purchase_order->delivery_status);
            var purchaseOrderId = @json($purchase_order->id);

            const deliveryStatusApi = @json(route('inventory.purchase_order.change.status', ['purchase_order' => ':purchase_order', 'status' => ':status']));
            const paymentMethodApi = @json(route('inventory.purchase_order.change.pMethod', ['purchase_order' => ':purchase_order', 'status' => ':status']));



            $deliveryStatus.on('change', updateButtonState);
            $paymentMethod.on('change', updateButtonState);

            $confirmOrder.on('click', function() {
                var selectedStatus = $deliveryStatus.val();
                var requestUrl = deliveryStatusApi.replace(':purchase_order', purchaseOrderId).replace(':status', selectedStatus);
                var selectedPaymentMethod = $paymentMethod.val();

                if (selectedStatus === '4' && !selectedPaymentMethod) {
                    toastr.warning("Please select a payment method before completing the order.");
                    return;
                }

                $.ajax({
                    url: requestUrl,
                    type: 'POST',
                    beforeSend: function(xhr) {
                        let token = $('meta[name="csrf-token"]').attr('content');
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    },
                    success: function(response) {
                        toastr.success("Delivery status updated successfully.");

                        if (selectedStatus === '4') {
                            var selectedPaymentMethod = $paymentMethod.val();
                            if (!selectedPaymentMethod) {
                                toastr.warning("Please select a payment method.");
                                return;
                            }

                            var paymentUrl = paymentMethodApi.replace(':purchase_order', purchaseOrderId).replace(':status', selectedPaymentMethod);

                            $.ajax({
                                url: paymentUrl,
                                type: 'POST',
                                beforeSend: function(xhr) {
                                    let token = $('meta[name="csrf-token"]').attr('content');
                                    xhr.setRequestHeader('X-CSRF-TOKEN', token);
                                },
                                success: function(response) {
                                    toastr.success("Payment method updated successfully.");
                                    location.reload();
                                },
                                error: function(xhr) {
                                    toastr.error("Error updating payment method.");
                                }
                            });
                        } else {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Error updating delivery status.");
                    }
                });
            });

            function updateButtonState() {
                $confirmOrder.prop('disabled', !$deliveryStatus.val());
            }

            updateButtonState();
        });
        </script>
    @endsection
