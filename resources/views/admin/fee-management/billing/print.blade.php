<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{!! $billing->challan_number ?? '' !!}</title>
    <style>
        body {
            font-size: 9px;
            font-family: Arial, Helvetica, sans-serif;
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }

        .container-fluid {
            width: 100%;
            display: flex;
            /align-items: center;/ /justify-content: space-around;/ /border: 10px solid green;/
        }

        /.box-3 {
            / / ! width: 35%;
            !/ / !
        }

        !/ .box-1 {
            width: 33%;
            display: flex;
            /border: 3px solid black;/ float: left;
            justify-content: space-around;
            /border: 10px solid green;/ margin-right: 5px;
        }


        h5 {
            font-size: 10px;
            font-weight: 800;
        }

        h6 {
            font-size: 9px;
            font-weight: 600;
        }

        .allin-one {
            border-bottom: 1px solid black;
            margin: 0px 10px;
        }


        .section-1 {
            display: flex;
            align-items: center;
            justify-content: space-around;


        }

        /.img {
            
            /*    margin-right: 15px;*/
            
        }

           .section-1 .p-1 {

            margin-top: -15px;
            font-size: 8px;
            margin-right: 20px;
            font-family: "Archivo", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";

        }

        .section-2 {
            text-align: center;
            line-height: 0px;
            margin: 10px 0px 0px 0px;
        }


        .section-3 span {
            font-size: 10px;
        }

        /.section-6 {
            /
            /*    display: flex;*/
            /*    align-items: center;*/
            /*    justify-content: flex-end;*/
            /*    margin: -6px 10px 0px 10px;*/
            /
        }

        / .section-7 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: -15px 10px 0px 10px;
        }


        .section-8 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: -2px 0px;

        }


        .section-9 span {
            border-bottom: 3px solid black;
            padding-left: 20px;
        }


        .bank-stamp {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
        }

        .allin-one {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px
        }

        .signature_style {
            margin: 20px 0px 0px 8px;
            width: 320px;
        }

        .note_style {
            border-top: 4px solid black;
            border-bottom: 4px solid black;
            overflow: hidden;
            margin: 8px 0px 0px 8px;
            width: 320px;
        }

        .bank_stamp_style {
            text-align: center;
            width: 150px;
            margin: auto;
            border: 1px solid black;
            padding: 5px 0px;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(52, 152, 219, 0.3);
        }

        .print-button:hover {
            background: #2980b9;
        }

        @media print {
            body {
                background: white;
                padding: 0;
                font-size: 12px;
            }

            .print-button {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .bill-info {
                flex-direction: column;
                gap: 20px;
            }

            .bill-content {
                padding: 20px;
            }

            .print-button {
                top: 15px;
                right: 15px;
                padding: 10px 15px;
                font-size: 12px;
            }
        }

        .discount-section {
            /* background: #d4edda;
            border: 1px solid #28a745;
            border-radius: 6px; */
            padding: 11px;
            /* margin: 8px 0; */
        }

        .discount-title {
            /* color: #155724;
            font-size: 14px; */
            font-weight: 600;
            /* margin-bottom: 6px; */
        }

        .discount-item {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            border-bottom: 1px solid #c3e6cb;
        }

        .discount-item:last-child {
            border-bottom: none;
        }

        .discount-name {
            /* color: #155724; */
            font-weight: 500;
        }

        .discount-value {
            /* color: #155724; */
            font-weight: 600;
        }

        .transport-section {
            /* background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 6px; */
            padding: 8px;
            /* margin: 8px 0; */
        }

        .transport-title {
            /* color: #0d47a1; */
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .transport-item {
            display: flex;
            justify-content: space-between;
            padding: 1px 0;
            border-bottom: 1px solid #bbdefb;
        }

        .transport-item:last-child {
            border-bottom: none;
        }

        .transport-name {
            /* color: #0d47a1; */
            font-weight: 500;
        }

        .transport-value {
            /* color: #0d47a1; */
            font-weight: 600;
        }

        .transport-total {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            border-top: 2px solid #000000;
            margin-top: 3px;
            font-weight: 600;
        }

        .transport-total-label {
            color: #000000;
        }

        .transport-total-value {
            color: #000000;
        }
    </style>
</head>

<body>

    <div class="print-container" style="margin: 79px;">
        <button class="print-button" onclick="window.print()">Print Bill</button>
    </div>
    <div class="container-fluid">
        {{--    <div style="border: 3px solid pink;"> --}}
        <div class="box-1">
            <div class="row">
                <div class="section-1">
                    
                    <div>
                        <h3 style="margin-top: 20px;font-weight: bold;">CORNERSTONE
                            SCHOOL<br>{!! $billing->student->branch->name ?? '' !!}</h3>
                        <span class="p-1" style="width: 10px;">
                            {!! $billing->student->branch->address ?? '' !!}<br>
                            Ph: 042-35454001-2 Email: fee@cornerstone.pk
                        </span>
                    </div>

                    <div class="img" style="float: right;margin-top: 10px;margin-right: 30px;">
                        <img src="{{asset("logos/1759388635.png")}}" style="height: 50px;">
                    </div>
                </div>


                <div class="section-2">
                    <h5>BANK Copy</h5>
                    <h5 style="margin-top: -5px;">FEE BILL </h5>
                    <h5 style="margin-top: -5px;">BANK AL-HABIB LTD. COLLECTION A/C: 0080-900445-01</h5>
                    <h5 style="margin-top: -5px;">MCB BANK LTD. A/C: PK72MUCB1042024051003582</h5>
                    <h5 style="margin-top: -5px;">(PAYABLE AT ANY BANK BRANCH)</h5>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Bill No:</span>
                        <span style=" margin-left: auto;font-weight: bold;">{!! $billing->challan_number ?? '' !!}</span>
                        <span style="margin-left: 70px;font-weight: bold;">Due Date: </span>
                        <span
                            style="margin-right: 20px;font-weight: bold; float: right;">{{ $billing->due_date ? \Carbon\Carbon::parse($billing->due_date)->format('d M Y') : 'N/A' }}</span>
                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Bill Date:</span>
                        <span
                            style=" margin-left: auto;font-weight: bold;">{{ $billing->bill_date ? \Carbon\Carbon::parse($billing->bill_date)->format('d M Y') : 'N/A' }}</span>
                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Student Name:</span>
                        <span style=" margin-left: auto;font-weight: bold;">{!! $billing->student->first_name . ' ' . $billing->student->last_name ?? '' !!}</span>
                        <span style="margin-left: 122px;">Student ID: {!! $billing->student->id ?? '' !!} </span>
                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Father's Name:</span>
                        <span style=" margin-left: auto; font-weight: bold;">{!! $billing->student->father_name ?? '' !!}</span>

                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Class:</span>
                        <span style=" margin-left: auto; font-weight: bold;">{!! $billing->student->AcademicClass->name ?? '' !!}</span>
                        <span style="margin-left: 190px">Fee Term: 02240551 </span>
                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Remarks:</span>
                        <span style="margin-left: auto;">{!! date('F Y', strtotime($billing->billing_month)) !!}</span>
                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 3px solid black; display: flex;">
                        <span style="margin-right: auto;font-weight: bold;">Description:</span>
                        <span style="margin-left: 283px;font-weight: bold;">Amount</span>
                    </div>
                </div>

                <div class="section-6" style="float: right;padding-right: 10px;">
                    <span>Rs. {{ number_format($billing->total_amount, 2) }}</span>
                </div>


                @if ($applicableDiscounts && $applicableDiscounts->count() > 0)
                    <div class="discount-section">
                        <div class="discount-title">Applied Discounts</div>
                        @foreach ($applicableDiscounts as $discount)
                            <div class="discount-item">
                                <span class="discount-name">{{ $discount->category->name ?? 'General' }}
                                    ({{ ucfirst($discount->discount_type) }})
                                </span>
                                <span class="discount-value">
                                    @if ($discount->discount_type == 'percentage')
                                        {{ $discount->discount_value }}%
                                    @else
                                        Rs. {{ number_format($discount->discount_value, 2) }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($transportFees && $transportFees->count() > 0)
                    <div class="transport-section">
                        <div class="transport-title">Transport Fees</div>
                        @foreach ($transportFees as $transport)
                            <div class="transport-item">
                                <span class="transport-name">{{ $transport->vehicle->vehicle_number ?? 'N/A' }} -
                                    {{ $transport->route->route_name ?? 'N/A' }}</span>
                                <span class="transport-value">Rs.
                                    {{ number_format($transport->monthly_charges, 2) }}</span>
                            </div>
                        @endforeach
                        <div class="transport-total">
                            <span class="transport-total-label">Total Transport Fee:</span>
                            <span class="transport-total-value">Rs. {{ number_format($totalTransportFee, 2) }}</span>
                        </div>
                    </div>
                @endif



                @php
                    $paidAmount = $billing->paid_amount ?? 0;
                    $finalAmount = $billing->getFinalAmount() + (isset($totalTransportFee) ? $totalTransportFee : 0);
                    $outstandingAmount = $finalAmount - $paidAmount;
                @endphp


                <div class="allin-one" style="margin-top: 40px;">
                    <div class="section-8"
                        style="border-bottom: 1px solid black; border-top: 1px solid black;padding : 3px 0px;">
                        <span>Previous Amounts </span>
                        <span style="float: right padding-right: 256px; margin-left:255px;"> Rs.
                            {{ number_format($outstandingAmount, 2) }}</span>
                    </div>
                </div>


                <div class="section-9-9" style="justify-content: space-between; margin-top: 7px">
                    <h6 style="margin: 10px 0px 0px 5px; display: inline;">Total Payment By Due Date
                        ({{ $billing->bill_date ? \Carbon\Carbon::parse($billing->bill_date)->format('d M Y') : 'N/A' }}):
                    </h6>
                    <span style="margin: 0px  9px 0px 0px; display: inline;float: right;"> Total: <span
                            style="border-bottom: 2px solid black;font-weight: bold;">{!! $billing->paid_amount ?? '' !!}</span></span>
                </div>



                <div class="section-10"
                    style="padding: 5px 20px; margin: 8px 0px 0px 8px; border: 2px solid black;width: 280px">
                    <h5 style="margin: 5px 0px;display: inline;padding-left: 50px;">Bill Valid Till:</h5>
                    <h5 style="display: inline;float: right;margin-top: -1px; "> {!! $billing->bill_date ?? '' !!}</h5>
                </div>

                <div class="bank-stamp">
                    <div class="section-11 bank_stamp_style">
                        <span style="margin: 5px 0px;">BANK STAMP</span>
                    </div>
                </div>


                <div class="signature_style">
                    <h5 style="border-top: 1px solid black; display: inline;">Depositor's Signature</h5>
                    <h5 style="border-top: 1px solid black; float: right; display: inline; margin-top: -1px;">Bank
                        Officer's
                        Signature</h5>
                </div>


                <div class="section-last note_style">
                    <span style="margin: 3px 0px;">Note:<br>
                        {!! $billing->message ?? '' !!}
                    </span>
                </div>


                {{--            <div class="section-14"> --}}
                {{--                <span>Awais.Abid, 04/05/2024, 3:28:18PM, FE0001</span> --}}
                {{--            </div> --}}
            </div>
        </div>

        <div class="box-1">
            <div class="row">
                <div class="section-1">

                    <div>
                        <h3 style="margin-top: 20px;font-weight: bold;">CORNERSTONE
                            SCHOOL<br>{!! $billing->student->branch->name ?? '' !!}</h3>
                        <span class="p-1" style="width: 10px;">
                            {!! $billing->student->branch->address ?? '' !!}<br>
                            Ph: 042-35454001-2 Email: fee@cornerstone.pk
                        </span>
                    </div>

                    <div class="img" style="float: right;margin-top: 10px;margin-right: 30px;">
                        <img src="{{asset("logos/1759388635.png")}}" style="height: 50px;">
                    </div>
                </div>


                <div class="section-2">
                    <h5>BANK Copy</h5>
                    <h5 style="margin-top: -5px;">FEE BILL </h5>
                    <h5 style="margin-top: -5px;">BANK AL-HABIB LTD. COLLECTION A/C: 0080-900445-01</h5>
                    <h5 style="margin-top: -5px;">MCB BANK LTD. A/C: PK72MUCB1042024051003582</h5>
                    <h5 style="margin-top: -5px;">(PAYABLE AT ANY BANK BRANCH)</h5>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Bill No:</span>
                        <span style=" margin-left: auto;font-weight: bold;">{!! $billing->challan_number ?? '' !!}</span>
                        <span style="margin-left: 70px;font-weight: bold;">Due Date: </span>
                        <span
                            style="margin-right: 20px;font-weight: bold; float: right;">{{ $billing->due_date ? \Carbon\Carbon::parse($billing->due_date)->format('d M Y') : 'N/A' }}</span>
                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Bill Date:</span>
                        <span
                            style=" margin-left: auto;font-weight: bold;">{{ $billing->bill_date ? \Carbon\Carbon::parse($billing->bill_date)->format('d M Y') : 'N/A' }}</span>
                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Student Name:</span>
                        <span style=" margin-left: auto;font-weight: bold;">{!! $billing->student->first_name . ' ' . $billing->student->last_name ?? '' !!}</span>
                        <span style="margin-left: 122px;">Student ID: {!! $billing->student->id ?? '' !!} </span>
                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Father's Name:</span>
                        <span style=" margin-left: auto; font-weight: bold;">{!! $billing->student->father_name ?? '' !!}</span>

                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Class:</span>
                        <span style=" margin-left: auto; font-weight: bold;">{!! $billing->student->AcademicClass->name ?? '' !!}</span>
                        <span style="margin-left: 190px">Fee Term: 02240551 </span>
                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Remarks:</span>
                        <span style="margin-left: auto;">{!! date('F Y', strtotime($billing->billing_month)) !!}</span>
                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 3px solid black; display: flex;">
                        <span style="margin-right: auto;font-weight: bold;">Description:</span>
                        <span style="margin-left: 283px;font-weight: bold;">Amount</span>
                    </div>
                </div>

                <div class="section-6" style="float: right;padding-right: 10px;">
                    <span>Rs. {{ number_format($billing->total_amount, 2) }}</span>
                </div>


                @if ($applicableDiscounts && $applicableDiscounts->count() > 0)
                    <div class="discount-section">
                        <div class="discount-title">Applied Discounts</div>
                        @foreach ($applicableDiscounts as $discount)
                            <div class="discount-item">
                                <span class="discount-name">{{ $discount->category->name ?? 'General' }}
                                    ({{ ucfirst($discount->discount_type) }})
                                </span>
                                <span class="discount-value">
                                    @if ($discount->discount_type == 'percentage')
                                        {{ $discount->discount_value }}%
                                    @else
                                        Rs. {{ number_format($discount->discount_value, 2) }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($transportFees && $transportFees->count() > 0)
                    <div class="transport-section">
                        <div class="transport-title">Transport Fees</div>
                        @foreach ($transportFees as $transport)
                            <div class="transport-item">
                                <span class="transport-name">{{ $transport->vehicle->vehicle_number ?? 'N/A' }} -
                                    {{ $transport->route->route_name ?? 'N/A' }}</span>
                                <span class="transport-value">Rs.
                                    {{ number_format($transport->monthly_charges, 2) }}</span>
                            </div>
                        @endforeach
                        <div class="transport-total">
                            <span class="transport-total-label">Total Transport Fee:</span>
                            <span class="transport-total-value">Rs. {{ number_format($totalTransportFee, 2) }}</span>
                        </div>
                    </div>
                @endif



                @php
                    $paidAmount = $billing->paid_amount ?? 0;
                    $finalAmount = $billing->getFinalAmount() + (isset($totalTransportFee) ? $totalTransportFee : 0);
                    $outstandingAmount = $finalAmount - $paidAmount;
                @endphp


                <div class="allin-one" style="margin-top: 40px;">
                    <div class="section-8"
                        style="border-bottom: 1px solid black; border-top: 1px solid black;padding : 3px 0px;">
                        <span>Previous Amounts </span>
                        <span style="float: right padding-right: 256px; margin-left:255px;"> Rs.
                            {{ number_format($outstandingAmount, 2) }}</span>
                    </div>
                </div>


                <div class="section-9-9" style="justify-content: space-between; margin-top: 7px">
                    <h6 style="margin: 10px 0px 0px 5px; display: inline;">Total Payment By Due Date
                        ({{ $billing->bill_date ? \Carbon\Carbon::parse($billing->bill_date)->format('d M Y') : 'N/A' }}):
                    </h6>
                    <span style="margin: 0px  9px 0px 0px; display: inline;float: right;"> Total: <span
                            style="border-bottom: 2px solid black;font-weight: bold;">{!! $billing->paid_amount ?? '' !!}</span></span>
                </div>



                <div class="section-10"
                    style="padding: 5px 20px; margin: 8px 0px 0px 8px; border: 2px solid black;width: 280px">
                    <h5 style="margin: 5px 0px;display: inline;padding-left: 50px;">Bill Valid Till:</h5>
                    <h5 style="display: inline;float: right;margin-top: -1px; "> {!! $billing->bill_date ?? '' !!}</h5>
                </div>

                <div class="bank-stamp">
                    <div class="section-11 bank_stamp_style">
                        <span style="margin: 5px 0px;">BANK STAMP</span>
                    </div>
                </div>


                <div class="signature_style">
                    <h5 style="border-top: 1px solid black; display: inline;">Depositor's Signature</h5>
                    <h5 style="border-top: 1px solid black; float: right; display: inline; margin-top: -1px;">Bank
                        Officer's
                        Signature</h5>
                </div>


                <div class="section-last note_style">
                    <span style="margin: 3px 0px;">Note:<br>
                        {!! $billing->message ?? '' !!}
                    </span>
                </div>


                {{--            <div class="section-14"> --}}
                {{--                <span>Awais.Abid, 04/05/2024, 3:28:18PM, FE0001</span> --}}
                {{--            </div> --}}
            </div>
        </div>




        <div class="box-1">
            <div class="row">
                <div class="section-1">
                    
                    <div>
                        <h3 style="margin-top: 20px;font-weight: bold;">CORNERSTONE
                            SCHOOL<br>{!! $billing->student->branch->name ?? '' !!}</h3>
                        <span class="p-1" style="width: 10px;">
                            {!! $billing->student->branch->address ?? '' !!}<br>
                            Ph: 042-35454001-2 Email: fee@cornerstone.pk
                        </span>
                    </div>

                    <div class="img" style="float: right;margin-top: 10px;margin-right: 30px;">
                        <img src="{{asset("logos/1759388635.png")}}" style="height: 50px;">
                    </div>
                </div>


                <div class="section-2">
                    <h5>BANK Copy</h5>
                    <h5 style="margin-top: -5px;">FEE BILL </h5>
                    <h5 style="margin-top: -5px;">BANK AL-HABIB LTD. COLLECTION A/C: 0080-900445-01</h5>
                    <h5 style="margin-top: -5px;">MCB BANK LTD. A/C: PK72MUCB1042024051003582</h5>
                    <h5 style="margin-top: -5px;">(PAYABLE AT ANY BANK BRANCH)</h5>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Bill No:</span>
                        <span style=" margin-left: auto;font-weight: bold;">{!! $billing->challan_number ?? '' !!}</span>
                        <span style="margin-left: 70px;font-weight: bold;">Due Date: </span>
                        <span
                            style="margin-right: 20px;font-weight: bold; float: right;">{{ $billing->due_date ? \Carbon\Carbon::parse($billing->due_date)->format('d M Y') : 'N/A' }}</span>
                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Bill Date:</span>
                        <span
                            style=" margin-left: auto;font-weight: bold;">{{ $billing->bill_date ? \Carbon\Carbon::parse($billing->bill_date)->format('d M Y') : 'N/A' }}</span>
                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Student Name:</span>
                        <span style=" margin-left: auto;font-weight: bold;">{!! $billing->student->first_name . ' ' . $billing->student->last_name ?? '' !!}</span>
                        <span style="margin-left: 122px;">Student ID: {!! $billing->student->id ?? '' !!} </span>
                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Father's Name:</span>
                        <span style=" margin-left: auto; font-weight: bold;">{!! $billing->student->father_name ?? '' !!}</span>

                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Class:</span>
                        <span style=" margin-left: auto; font-weight: bold;">{!! $billing->student->AcademicClass->name ?? '' !!}</span>
                        <span style="margin-left: 190px">Fee Term: 02240551 </span>
                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 1px solid black;">
                        <span style="margin-right: 10px;">Remarks:</span>
                        <span style="margin-left: auto;">{!! date('F Y', strtotime($billing->billing_month)) !!}</span>
                    </div>
                </div>

                <div class="allin-one">
                    <div class="section-3" style="border-bottom: 3px solid black; display: flex;">
                        <span style="margin-right: auto;font-weight: bold;">Description:</span>
                        <span style="margin-left: 283px;font-weight: bold;">Amount</span>
                    </div>
                </div>

                <div class="section-6" style="float: right;padding-right: 10px;">
                    <span>Rs. {{ number_format($billing->total_amount, 2) }}</span>
                </div>


                @if ($applicableDiscounts && $applicableDiscounts->count() > 0)
                    <div class="discount-section">
                        <div class="discount-title">Applied Discounts</div>
                        @foreach ($applicableDiscounts as $discount)
                            <div class="discount-item">
                                <span class="discount-name">{{ $discount->category->name ?? 'General' }}
                                    ({{ ucfirst($discount->discount_type) }})
                                </span>
                                <span class="discount-value">
                                    @if ($discount->discount_type == 'percentage')
                                        {{ $discount->discount_value }}%
                                    @else
                                        Rs. {{ number_format($discount->discount_value, 2) }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($transportFees && $transportFees->count() > 0)
                    <div class="transport-section">
                        <div class="transport-title">Transport Fees</div>
                        @foreach ($transportFees as $transport)
                            <div class="transport-item">
                                <span class="transport-name">{{ $transport->vehicle->vehicle_number ?? 'N/A' }} -
                                    {{ $transport->route->route_name ?? 'N/A' }}</span>
                                <span class="transport-value">Rs.
                                    {{ number_format($transport->monthly_charges, 2) }}</span>
                            </div>
                        @endforeach
                        <div class="transport-total">
                            <span class="transport-total-label">Total Transport Fee:</span>
                            <span class="transport-total-value">Rs. {{ number_format($totalTransportFee, 2) }}</span>
                        </div>
                    </div>
                @endif



                @php
                    $paidAmount = $billing->paid_amount ?? 0;
                    $finalAmount = $billing->getFinalAmount() + (isset($totalTransportFee) ? $totalTransportFee : 0);
                    $outstandingAmount = $finalAmount - $paidAmount;
                @endphp


                <div class="allin-one" style="margin-top: 40px;">
                    <div class="section-8"
                        style="border-bottom: 1px solid black; border-top: 1px solid black;padding : 3px 0px;">
                        <span>Previous Amounts </span>
                        <span style="float: right padding-right: 256px; margin-left:255px;"> Rs.
                            {{ number_format($outstandingAmount, 2) }}</span>
                    </div>
                </div>


                <div class="section-9-9" style="justify-content: space-between; margin-top: 7px">
                    <h6 style="margin: 10px 0px 0px 5px; display: inline;">Total Payment By Due Date
                        ({{ $billing->bill_date ? \Carbon\Carbon::parse($billing->bill_date)->format('d M Y') : 'N/A' }}):
                    </h6>
                    <span style="margin: 0px  9px 0px 0px; display: inline;float: right;"> Total: <span
                            style="border-bottom: 2px solid black;font-weight: bold;">{!! $billing->paid_amount ?? '' !!}</span></span>
                </div>



                <div class="section-10"
                    style="padding: 5px 20px; margin: 8px 0px 0px 8px; border: 2px solid black;width: 280px">
                    <h5 style="margin: 5px 0px;display: inline;padding-left: 50px;">Bill Valid Till:</h5>
                    <h5 style="display: inline;float: right;margin-top: -1px; "> {!! $billing->bill_date ?? '' !!}</h5>
                </div>

                <div class="bank-stamp">
                    <div class="section-11 bank_stamp_style">
                        <span style="margin: 5px 0px;">BANK STAMP</span>
                    </div>
                </div>


                <div class="signature_style">
                    <h5 style="border-top: 1px solid black; display: inline;">Depositor's Signature</h5>
                    <h5 style="border-top: 1px solid black; float: right; display: inline; margin-top: -1px;">Bank
                        Officer's
                        Signature</h5>
                </div>


                <div class="section-last note_style">
                    <span style="margin: 3px 0px;">Note:<br>
                        {!! $billing->message ?? '' !!}
                    </span>
                </div>


                {{--            <div class="section-14"> --}}
                {{--                <span>Awais.Abid, 04/05/2024, 3:28:18PM, FE0001</span> --}}
                {{--            </div> --}}
            </div>
        </div>


    </div>


</body>

</html>
