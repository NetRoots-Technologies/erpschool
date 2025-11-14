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
            <h3 class="text-22 text-center text-bold w-100 mb-4">GRN</h3>
        </div>
        <div class="form-container p-4">

            <div class="row mt-4">
                <div class="col-md-6">
                    <button id="backButton" class="btn btn-dark mt-2 ms-2">Back</button>
                    
                    <div class="info-box">
                        <div><span class="label">Branch Name:</span> <span class="value"
                                id="{{ $purchase_order->branch->branch_id }}">{{ $purchase_order->branch->name }}</span>
                        </div>
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
                                    {{-- @if ($currentStatus == 'PENDING' && ($status == 'SHIPPED' || $status == 'CANCELLED' || $status == 'PARTIALLY'))
                                        <option value="{{ $key }}">{{ $status }}</option>
                                    @elseif ($currentStatus == 'SHIPPED' && ($status == 'COMPLETED' || $status == 'CANCELLED' || $status == 'PARTIALLY')) --}}
                                        <option value="{{ $key }}">{{ $status }}</option>
                                    
                                    {{-- @endif --}}
                                @endforeach
                            </select>
                        </div>

                        {{-- @if ($currentStatus === 'SHIPPED')
                            <div class="d-flex align-items-center">
                                <span class="label">Select Ledger (optional):</span>
                                <select name="paymentMethod" id="paymentMethod" class="form-select w-100">
                                    <option value="" selected>-- Optional --</option>
                                    @foreach ($ledgers as $ledger)
                                        <option value="{{ $ledger->id }}">{{ $ledger->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif --}}


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
                                <th>Received Quantity</th>
                                <th>Price</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase_order->purchaseOrderItems as $order)

                            @php
                                $check = $order->quantity - $order->received_quantity;
                            @endphp
                                <tr>
                                    <td>{{ $order->item->name }}</td>
                                    <td>{{ $check }}</td>
                                    <td><input type="text" name="received_qty" value="0" class="received-qty"></td>
                                    <td>{{ $order->unit_price }}</td>
                                    <td><input type="text" name="total_price" value="0.00" class="total-price"
                                            readonly></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <p style="display: inline; margin-right: 20px;">
                <strong>Prepared By:</strong> {{ auth()->user()->name ?? '-' }}
            </p>

            <p style="display: inline; margin-right: 20px;">
                <strong>Approved By:</strong> ____________
            </p>

            <p style="display: inline;">
                <strong>Checked By:</strong> ____________
            </p>
        </div>

    @endsection

    @section('js')
        <script>
            $(document).ready(function() {
                // selector for table - adjust if your table has different id/class
                var $table = $('.item_info table');

                // ensure a grand total row exists (create if not)
                if ($table.find('tfoot').length === 0) {
                    $table.append(
                        '<tfoot><tr class="grand-total-row"><td colspan="4" style="text-align:right"><strong>Grand Total:</strong></td>' +
                        '<td><input type="text" id="grand-total" value="0.00" readonly class="form-control" name="grand_total"></td></tr></tfoot>'
                    );
                }

                // helper to parse number safely
                function parseNum(v) {
                    v = String(v).replace(/,/g, '').trim();
                    var n = parseFloat(v);
                    return isNaN(n) ? 0 : n;
                }

                // recalc a single row (row is a <tr>)
                function recalcRow($row) {
                    // column indices based on your table:
                    // 0 = Item Name, 1 = Quantity (ordered), 2 = Received Qty (input), 3 = Price (text), 4 = Total (input)
                    var $receivedInput = $row.find('td').eq(2).find('input');
                    var $priceCell = $row.find('td').eq(3);
                    var $totalInput = $row.find('td').eq(4).find('input');

                    // if inputs not found by class, we try fallback to find any input in that td
                    if ($receivedInput.length === 0) $receivedInput = $row.find('td').eq(2).find(
                        'input, [name*=received]');
                    if ($totalInput.length === 0) $totalInput = $row.find('td').eq(4).find('input, [name*=total]');

                    var qty = parseNum($receivedInput.val());
                    var unit = parseNum($priceCell.text() || $priceCell.find('input').val());

                    var total = qty * unit;
                    // set formatted value
                    $totalInput.val(total.toFixed(2));

                    return total;
                }

                // recalc all rows and update grand total
                function recalcAll() {
                    var grand = 0;
                    $table.find('tbody tr').each(function() {
                        grand += recalcRow($(this));
                    });
                    $('#grand-total').val(grand.toFixed(2));
                }

                // attach input event to received qty inputs (delegated)
                $table.on('input', 'tbody tr td:nth-child(3) input, tbody tr td:nth-child(3) [name*=received]',
                    function() {
                        var $row = $(this).closest('tr');
                        recalcRow($row);

                        // update grand total
                        var grand = 0;
                        $table.find('tbody tr').each(function() {
                            var v = parseNum($(this).find('td').eq(4).find('input').val());
                            grand += v;
                        });
                        $('#grand-total').val(grand.toFixed(2));
                    });

                // initial calculation (in case defaults are non-zero)
                recalcAll();
            });
        </script>

        <script type="text/javascript" defer>
            $(document).ready(function() {
                'use strict';

                $("#deliveryStatus, #paymentMethod").select2();
                var $deliveryStatus = $('#deliveryStatus');
                var $paymentMethod = $('#paymentMethod');
                var $confirmOrder = $('#confirmOrder');
                var $backButton = $('#backButton');

                var currentStatus = @json($purchase_order->delivery_status);
                var purchaseOrderId = @json($purchase_order->id);

                const deliveryStatusApi = @json(route('inventory.purchase_order.change.status', ['purchase_order' => ':purchase_order', 'status' => ':status']));
                const paymentMethodApi = @json(route('inventory.purchase_order.change.pMethod', ['purchase_order' => ':purchase_order', 'status' => ':status']));



                $deliveryStatus.on('change', updateButtonState);
                $paymentMethod.on('change', updateButtonState);

                $backButton.on('click', function() {
                    let type = "{{ strtolower($purchase_order->type) }}";
                    if (type === 'f') type = 'food';
                    if (type === 's') type = 'stationary';
                    if (type === 'u') type = 'uniform';

                    window.location.href = `/inventory/grn/${type}`;
                });

                $confirmOrder.on('click', function() {

                    let items = [];

                    $("table tbody tr").each(function () {

                    let row = $(this);
                    let itemId = row.data("item-id");
                    let receivedQty = row.find(".received-qty").val();
                    let totalPrice = row.find(".total-price").val();

                    items.push({
                        id: itemId,
                        received_qty: receivedQty,
                        total_price: totalPrice
                    });
                });
                    console.log(items);
                    var selectedStatus = $deliveryStatus.val();
                    var requestUrl = deliveryStatusApi.replace(':purchase_order', purchaseOrderId).replace(
                        ':status', selectedStatus);
                    var selectedPaymentMethod = $paymentMethod.val();

                    $.ajax({
                        url: requestUrl,
                        type: 'POST',
                        data: {
                            items: items,
                            'grn_amount': $('#grand-total').val(),
                           
                        },
                        beforeSend: function(xhr) {
                            let token = $('meta[name="csrf-token"]').attr('content');
                            xhr.setRequestHeader('X-CSRF-TOKEN', token);
                        },
                        success: function(response) {
                            toastr.success("Delivery status updated successfully.");

                            // if (selectedStatus === '4') {
                            //     var selectedPaymentMethod = $paymentMethod.val();
                            //     if (!selectedPaymentMethod) {
                            //         toastr.warning("Please select a payment method.");
                            //         return;
                            //     }

                            //     var paymentUrl = paymentMethodApi.replace(':purchase_order', purchaseOrderId).replace(':status', selectedPaymentMethod);

                            //     $.ajax({
                            //         url: paymentUrl,
                            //         type: 'POST',
                            //         beforeSend: function(xhr) {
                            //             let token = $('meta[name="csrf-token"]').attr('content');
                            //             xhr.setRequestHeader('X-CSRF-TOKEN', token);
                            //         },
                            //         success: function(response) {
                            //             toastr.success("Payment method updated successfully.");
                            //             location.reload();
                            //         },
                            //         error: function(xhr) {
                            //             toastr.error("Error updating payment method.");
                            //         }
                            //     });
                            // } else {
                            //     location.reload();
                            // }
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
