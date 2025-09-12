<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
            /*align-items: center;*/
            /*justify-content: space-around;*/

            /*border: 10px solid green;*/

        }

        /*.box-3 {*/
        /*!*    width: 35%;*!*/
        /*!*}*!*/
        .box-1 {
            width: 33%;
            display: flex;
            /*border: 3px solid black;*/
            float: left;
            justify-content: space-around;
            /*border: 10px solid green;*/
            margin-right: 5px;
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

        /*.img {*/
        /*    margin-right: 15px;*/
        /*}*/

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

        /*.section-6 {*/
        /*    display: flex;*/
        /*    align-items: center;*/
        /*    justify-content: flex-end;*/
        /*    margin: -6px 10px 0px 10px;*/
        /*}*/

        .section-7 {
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
    </style>
</head>

<body>
<div class="container-fluid">
    {{--    <div style="border: 3px solid pink;">--}}
    <div class="box-1">
        <div class="row">
            <div class="section-1" style="display: flex; align-items: center;">
                <div class="img" style="float: right;margin-top: 10px;margin-right: 30px;">
                    <img src="data:image/png;base64,{{ $main_image }}" style="width: 50px;height: 50px;">
                </div>
                <div style="flex: 1;width: 320px;">
                    <h3 style="margin-top: 20px;font-weight: bold;">CORNERSTONE
                        SCHOOL<br>{!! $studentsFee->branch->name ?? '' !!}</h3>
                    <span class="p-1" style="width: 10px;">
                            {!! $studentsFee->branch->address ?? '' !!}<br>
                        Ph: 042-35454001-2 Email: fee@cornerstone.pk
                     </span>
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
                    <span style=" margin-left: auto;font-weight: bold;">{!! $studentsFee->bill_number ?? '' !!}</span>
                    <span style="margin-left: 70px;font-weight: bold;">Due Date: </span>
                    <span
                        style="margin-right: 20px;font-weight: bold; float: right;">{!! $studentsFee->due_date ?? '' !!}</span>
                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Bill Date:</span>
                    <span style=" margin-left: auto;font-weight: bold;">{!! $studentsFee->bill_date ?? '' !!}</span>
                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Student Name:</span>
                    <span
                        style=" margin-left: auto;font-weight: bold;">{!! $studentsFee->student->first_name.' '.$studentsFee->student->last_name ?? '' !!}</span>
                    <span style="float: right;">Student ID: {!! $studentsFee->student->studentId ?? '' !!} </span>
                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Father's Name:</span>
                    <span
                        style=" margin-left: auto; font-weight: bold;">{!! $studentsFee->student->father_name ?? '' !!}</span>

                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Class:</span>
                    <span
                        style=" margin-left: auto; font-weight: bold;">{!! $studentsFee->AcademicClass->name ?? '' !!}</span>
                    <span style="float: right;">Fee Term: 02240551 </span>
                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Remarks:</span>
                    <span style="margin-left: auto;">{!! date('F Y', strtotime($studentsFee->year_month)) !!}</span>

                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 3px solid black; display: flex;">
                    <span style="margin-right: auto;font-weight: bold;">Description:</span>
                    <span style="float: right;font-weight: bold;">Amount</span>
                </div>
            </div>

            <div class="section-6" style="float: right;padding-right: 10px;">
                <span>Rupees</span>

            </div>
{{--            @dd($studentsFee->billingData)--}}
            @foreach($studentsFee->billingData as $index => $billData)
                @if($billData->bills_amount != 0)
                    <div class="section-7"
                         style="display: flex; align-items: center; justify-content: space-between; margin-top: {{ $index === 0 ? '20px' : '1px' }};">
                        <span>{!! $billData->feeHead->fee_head ?? '' !!}</span>
                        <span style="float: right">{!! $billData->bills_amount ?? '' !!}</span>
                    </div>
                @endif
            @endforeach


            <div class="allin-one" style="margin-top: 40px;">
                <div class="section-8"
                     style="border-bottom: 1px solid black; border-top: 1px solid black;padding : 3px 0px;">
                    <span>Previous Amounts </span>
                    <span style="float: right"> {!! $studentsFee->previous_amount ?? 0 !!}</span>
                </div>
            </div>


            {{--            <div class="allin-one">--}}
            <div class="section-9-9" style="justify-content: space-between; margin-top: 7px">
                <h6 style="margin: 10px 0px 0px 5px; display: inline;">Total Payment By Due Date
                    ({!! $studentsFee->due_date ?? '' !!}):</h6>
                <span style="margin: 0px  9px 0px 0px; display: inline;float: right;"> Total: <span
                        style="border-bottom: 2px solid black;font-weight: bold;">{!! $studentsFee->fees ?? '' !!}</span></span>
            </div>

            <div class="section-10"
                 style="padding: 5px 20px; margin: 8px 0px 0px 8px; border: 2px solid black;width: 280px">
                <h5 style="margin: 5px 0px;display: inline;padding-left: 50px;">Bill Valid Till:</h5>
                <h5 style="display: inline;float: right;margin-top: -1px; "> {!! $studentsFee->valid_date ?? '' !!}</h5>
            </div>

            <div class="bank-stamp">
                <div class="section-11 bank_stamp_style">
                    <span style="margin: 5px 0px;">BANK STAMP</span>
                </div>
            </div>


            <div class="signature_style">
                <h5 style="border-top: 1px solid black; display: inline;">Depositor's Signature</h5>
                <h5 style="border-top: 1px solid black; float: right; display: inline; margin-top: -1px;">Bank Officer's
                    Signature</h5>
            </div>


            <div class="section-last note_style">
                <span style="margin: 3px 0px;">Note:<br>
                   {!! $studentsFee->message ?? '' !!}
                </span>
            </div>


            {{--            <div class="section-14">--}}
            {{--                <span>Awais.Abid, 04/05/2024, 3:28:18PM, FE0001</span>--}}
            {{--            </div>--}}
        </div>
    </div>

    <div class="box-1">
        <div class="row">
            <div class="section-1" style="display: flex; align-items: center;">
                <div class="img" style="float: right;margin-top: 10px;margin-right: 30px;">
                    <img src="data:image/png;base64,{{ $main_image }}" style="width: 50px;height: 50px;">
                </div>
                <div style="flex: 1;width: 320px;">
                    <h3 style="margin-top: 20px;font-weight: bold;">CORNERSTONE
                        SCHOOL<br>{!! $studentsFee->branch->name ?? '' !!}</h3>
                    <span class="p-1" style="width: 40px;">
            {!! $studentsFee->branch->address ?? '' !!}<br>
            Ph: 042-35454001-2 Email: fee@cornerstone.pk
        </span>
                </div>
            </div>


            <div class="section-2">
                <h5>SCHOOL COPY</h5>
                <h5 style="margin-top: -5px;">FEE BILL </h5>
                <h5 style="margin-top: -5px;">BANK AL-HABIB LTD. COLLECTION A/C: 0080-900445-01</h5>
                <h5 style="margin-top: -5px;">MCB BANK LTD. A/C: PK72MUCB1042024051003582</h5>
                <h5 style="margin-top: -5px;">(PAYABLE AT ANY BANK BRANCH)</h5>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Bill No:</span>
                    <span style=" margin-left: auto;font-weight: bold;">{!! $studentsFee->bill_number ?? '' !!}</span>
                    <span style="margin-left: 70px;font-weight: bold;">Due Date: </span>
                    <span
                        style="margin-right: 20px;font-weight: bold; float: right;">{!! $studentsFee->due_date ?? '' !!}</span>
                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Bill Date:</span>
                    <span style=" margin-left: auto;font-weight: bold;">{!! $studentsFee->bill_date ?? '' !!}</span>
                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Student Name:</span>
                    <span
                        style=" margin-left: auto;font-weight: bold;">{!! $studentsFee->student->first_name.' '.$studentsFee->student->last_name ?? '' !!}</span>
                    <span style="float: right;">Student ID: {!! $studentsFee->student->studentId ?? '' !!} </span>
                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Father's Name:</span>
                    <span
                        style=" margin-left: auto; font-weight: bold;">{!! $studentsFee->student->father_name ?? '' !!}</span>

                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Class:</span>
                    <span
                        style=" margin-left: auto; font-weight: bold;">{!! $studentsFee->AcademicClass->name ?? '' !!}</span>
                    <span style="float: right;">Fee Term: 02240551 </span>
                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Remarks:</span>
                    <span style="margin-left: auto;">{!! date('F Y', strtotime($studentsFee->year_month)) !!}</span>

                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 3px solid black; display: flex;">
                    <span style="margin-right: auto;font-weight: bold;">Description:</span>
                    <span style="float: right;font-weight: bold;">Amount</span>
                </div>
            </div>

            <div class="section-6" style="float: right;padding-right: 10px;">
                <span>Rupees</span>

            </div>
            @foreach($studentsFee->billingData as $index => $billData)
                @if($billData->bills_amount != 0)
                    <div class="section-7"
                         style="display: flex; align-items: center; justify-content: space-between; margin-top: {{ $index === 0 ? '20px' : '1px' }};">
                        <span>{!! $billData->feeHead->fee_head ?? '' !!}</span>
                        <span style="float: right">{!! $billData->bills_amount ?? '' !!}</span>
                    </div>
                @endif
            @endforeach


            <div class="allin-one" style="margin-top: 40px;">
                <div class="section-8"
                     style="border-bottom: 1px solid black; border-top: 1px solid black;padding : 3px 0px;">
                    <span>Previous Amounts </span>
                    <span style="float: right"> {!! $studentsFee->previous_amount ?? 0 !!}</span>
                </div>
            </div>


            {{--            <div class="allin-one">--}}
            <div class="section-9-9" style="justify-content: space-between; margin-top: 7px">
                <h6 style="margin: 10px 0px 0px 5px; display: inline;">Total Payment By Due Date
                    ({!! $studentsFee->due_date ?? '' !!}):</h6>
                <span style="margin: 0px  9px 0px 0px; display: inline;float: right;"> Total: <span
                        style="border-bottom: 2px solid black;font-weight: bold;">{!! $studentsFee->fees ?? '' !!}</span></span>
            </div>

            <div class="section-10"
                 style="padding: 5px 20px; margin: 8px 0px 0px 8px; border: 2px solid black;width: 280px">
                <h5 style="margin: 5px 0px;display: inline;padding-left: 50px;">Bill Valid Till:</h5>
                <h5 style="display: inline;float: right;margin-top: -1px; "> {!! $studentsFee->valid_date ?? '' !!}</h5>
            </div>

            <div class="bank-stamp">
                <div class="section-11 bank_stamp_style">
                    <span style="margin: 5px 0px;">BANK STAMP</span>
                </div>
            </div>


            <div class="signature_style">
                <h5 style="border-top: 1px solid black; display: inline;">Depositor's Signature</h5>
                <h5 style="border-top: 1px solid black; float: right; display: inline; margin-top: -1px;">Bank Officer's
                    Signature</h5>
            </div>


            <div class="section-last note_style">
                <span style="margin: 3px 0px;">Note:<br>
                   {!! $studentsFee->message ?? '' !!}
                </span>
            </div>


            {{--            <div class="section-14">--}}
            {{--                <span>Awais.Abid, 04/05/2024, 3:28:18PM, FE0001</span>--}}
            {{--            </div>--}}
        </div>
    </div>


    <div class="box-1">
        <div class="row">
            <div class="section-1" style="display: flex; align-items: center;">
                <div class="img" style="float: right;margin-top: 10px;margin-right: 30px;">
                    <img src="data:image/png;base64,{{ $main_image }}" style="width: 50px;height: 50px;">
                </div>
                <div style="flex: 1;width: 320px;">
                    <h3 style="margin-top: 20px;font-weight: bold;">CORNERSTONE
                        SCHOOL<br>{!! $studentsFee->branch->name ?? '' !!}</h3>
                    <span class="p-1" style="width: 40px;">
           {!! $studentsFee->branch->address ?? '' !!}<br>
            Ph: 042-35454001-2 Email: fee@cornerstone.pk
        </span>
                </div>
            </div>


            <div class="section-2">
                <h5>STUDENT COPY</h5>
                <h5 style="margin-top: -5px;">FEE BILL </h5>
                <h5 style="margin-top: -5px;">BANK AL-HABIB LTD. COLLECTION A/C: 0080-900445-01</h5>
                <h5 style="margin-top: -5px;">MCB BANK LTD. A/C: PK72MUCB1042024051003582</h5>
                <h5 style="margin-top: -5px;">(PAYABLE AT ANY BANK BRANCH)</h5>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Bill No:</span>
                    <span style=" margin-left: auto;font-weight: bold;">{!! $studentsFee->bill_number ?? '' !!}</span>
                    <span style="margin-left: 70px;font-weight: bold;">Due Date: </span>
                    <span
                        style="margin-right: 20px;font-weight: bold; float: right;">{!! $studentsFee->due_date ?? '' !!}</span>
                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Bill Date:</span>
                    <span style=" margin-left: auto;font-weight: bold;">{!! $studentsFee->bill_date ?? '' !!}</span>
                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Student Name:</span>
                    <span
                        style=" margin-left: auto;font-weight: bold;">{!! $studentsFee->student->first_name.' '.$studentsFee->student->last_name ?? '' !!}</span>
                    <span style="float: right;">Student ID: {!! $studentsFee->student->studentId ?? '' !!} </span>
                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Father's Name:</span>
                    <span
                        style=" margin-left: auto; font-weight: bold;">{!! $studentsFee->student->father_name ?? '' !!}</span>

                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Class:</span>
                    <span
                        style=" margin-left: auto; font-weight: bold;">{!! $studentsFee->AcademicClass->name ?? '' !!}</span>
                    <span style="float: right;">Fee Term: 02240551 </span>
                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 1px solid black;">
                    <span style="margin-right: 10px;">Remarks:</span>
                    <span style="margin-left: auto;">{!! date('F Y', strtotime($studentsFee->year_month)) !!}</span>

                </div>
            </div>

            <div class="allin-one">
                <div class="section-3" style="border-bottom: 3px solid black; display: flex;">
                    <span style="margin-right: auto;font-weight: bold;">Description:</span>
                    <span style="float: right;font-weight: bold;">Amount</span>
                </div>
            </div>

            <div class="section-6" style="float: right;padding-right: 10px;">
                <span>Rupees</span>

            </div>
            @foreach($studentsFee->billingData as $index => $billData)
                @if($billData->bills_amount != 0)
                    <div class="section-7"
                         style="display: flex; align-items: center; justify-content: space-between; margin-top: {{ $index === 0 ? '20px' : '1px' }};">
                        <span>{!! $billData->feeHead->fee_head ?? '' !!}</span>
                        <span style="float: right">{!! $billData->bills_amount ?? '' !!}</span>
                    </div>
                @endif
            @endforeach



            <div class="allin-one" style="margin-top: 40px;">
                <div class="section-8"
                     style="border-bottom: 1px solid black; border-top: 1px solid black;padding : 3px 0px;">
                    <span>Previous Amounts </span>
                    <span style="float: right"> {!! $studentsFee->previous_amount ?? 0 !!}</span>
                </div>
            </div>

            {{--            <div class="allin-one">--}}
            <div class="section-9-9" style="justify-content: space-between; margin-top: 7px">
                <h6 style="margin: 10px 0px 0px 5px; display: inline;">Total Payment By Due Date
                    ({!! $studentsFee->due_date ?? '' !!}):</h6>
                <span style="margin: 0px  9px 0px 0px; display: inline;float: right;"> Total: <span
                        style="border-bottom: 2px solid black;font-weight: bold;">{!! $studentsFee->fees ?? '' !!}</span></span>
            </div>

            <div class="section-10"
                 style="padding: 5px 20px; margin: 8px 0px 0px 8px; border: 2px solid black;width: 280px">
                <h5 style="margin: 5px 0px;display: inline;padding-left: 50px;">Bill Valid Till:</h5>
                <h5 style="display: inline;float: right;margin-top: -1px; "> {!! $studentsFee->valid_date ?? '' !!}</h5>
            </div>

            <div class="bank-stamp">
                <div class="section-11 bank_stamp_style">
                    <span style="margin: 5px 0px;">BANK STAMP</span>
                </div>
            </div>


            <div class="signature_style">
                <h5 style="border-top: 1px solid black; display: inline;">Depositor's Signature</h5>
                <h5 style="border-top: 1px solid black; float: right; display: inline; margin-top: -1px;">Bank Officer's
                    Signature</h5>
            </div>


            <div class="section-last note_style">
                <span style="margin: 3px 0px;">Note:<br>
                   {!! $studentsFee->message ?? '' !!}
                </span>
            </div>


            {{--            <div class="section-14">--}}
            {{--                <span>Awais.Abid, 04/05/2024, 3:28:18PM, FE0001</span>--}}
            {{--            </div>--}}
        </div>
    </div>


</div>


</body>

</html>
