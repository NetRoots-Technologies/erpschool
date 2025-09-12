<!DOCTYPE>
<!DOCTYPE html>
<html>
<head>
    <title>fee voucher</title>

    <style>
        * {
            box-sizing: border-box;
        }

        /* Create four equal columns that floats next to each other */
        .column {
            float: left;
            width: 33.33%;

            /* Should be removed. Only for demonstration */
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        td {
            max-width: 50%;
            font-size: 10px;
            word-wrap: break-word;
            text-wrap: normal;

        }

        tr {
            max-width: 100%;
        }

        table {
            table-layout: fixed;
            width: 100% "
        }
    </style>


</head>
<style>

    body {
        padding: 0 30px;
        line-height: 12px;
    }
</style>
<body>


<!-------------------
Sale Tax Invoice
-------------------->


<!-------------------
 supplier & buyer Details
-------------------->


<div class="row">
    <div class="column" style=" font-size: 14px; border: 1px solid black;">
        <div class="buyer-info" style="">


            <table style="padding: 10px 10px 0 10px;table-layout: fixed; width: 100%"
            " width="100%" >
            <h2 align="center" style="margin:5px 0;">ONEZ COMMERCE</h2>
            <p style="text-align: center; margin: 0; font-size: 12px;">357 Saman Berg Society, Allah Hoo Chowk,<br>
                Block E Johar Town, Lahore, 54000, Pakistan</p>


            <h3 style="padding: 0px 0px;text-align: center;">Fee Voucher</h3>

            <p style="margin: 10px  10px 0px; display: flex;align-items: center;">Voucher No: <span
                    style="padding:5px 7px; border: 1px solid black; margin-left: 10px;">O</span> <span
                    style="padding:5px 7px; border: 1px solid black;">C</span> <span
                    style="padding:5px 7px; border: 1px solid black;">0</span><span
                    style="padding:5px 7px; border: 1px solid black;">0</span><span
                    style="padding:5px 7px; border: 1px solid black;">0</span><span
                    style="padding:5px 7px; border: 1px solid black;">1</span></p>
            <p style="margin: 10px  10px 0px;">Date:<span
                    style="border-bottom: 1px solid black; width: 70px;padding: 1px 70px;">{!! $data[0]['start_date'] !!}</span>
            </p>

            <tbody style="padding: 100px; font-size: 14px;">
            <tr>

                <td width="100%" style=" background-color:black; padding: 2px 10px; color: white; text-align: center;">
                    Details
                </td>

            </tr>

            </tbody>
            </table>
            <table style=" padding: 0 10px; font-size: 14px;table-layout: fixed; width: 100%" width="100%">
                <tbody style="padding: 100px;">
                <tr>
                    <td width="70%" style="background-color:black; padding: 2px 10px; color: white; text-align: left;">
                        Student Name
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->student_fee->student['name'] !!}</td>

                </tr>


                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Contact Number
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->student_fee->student['mobile_no'] !!}</td>

                </tr>


                <tr style="word-wrap: break-word;">
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Email-ID
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;text-wrap:normal;word-wrap:break-word">
                        {!! $data[0]->student_fee->student['email'] !!}
                    </td>

                </tr>


                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Course Title
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->student_fee->course['name'] !!}</td>

                </tr>

                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Course Fee
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->student_fee->course['fee'] !!}</td>

                </tr>
                <tr>
                    <td width="70%" style="background-color:black; padding: 2px 10px; color: white; text-align: left;">
                        Campus/Online
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">Campus</td>

                </tr>


                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Session
                    </td>

                    <td style="border:1px solid black; padding: 2px 10px;">
                        @if($data[0]->student_fee->session) {!! $data[0]->student_fee->session['title'] !!} @endif
                    </td>

                </tr>


                {{--                <tr>--}}
                {{--                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">--}}
                {{--                        Start Date--}}
                {{--                    </td>--}}
                {{--                    <td style="border:1px solid black; padding: 2px 10px;">--}}
                {{--                        @if($data[0]) {!! $data[0]->start_date !!} @endif</td>--}}

                {{--                </tr>--}}
                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Due Date
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">
                        @if($data[0]) {!! $data[0]->due_date !!} @endif</td>

                </tr>


                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Class Timings
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">
                        @if($data[0]->student_fee->session)  {!! $data[0]->student_fee->session['start_time'] !!}
                        -{!! $data[0]->student_fee->session['end_time'] !!} @endif</td>

                </tr>
                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Payment Method
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->source !!}</td>

                </tr>
                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Payment Date
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">
                        @if($data[0]->paid_date) {!! $data[0]->paid_date !!}
                        @endif
                    </td>

                </tr>
                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                   @if($data[0]->paid_status=='paid')   Paid Amount  @else     Payable Amount @endif
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->installement_amount !!}</td>

                </tr>
                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Remaining Fee
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->student_fee['remaining_amount'] !!}</td>

                </tr>

                </tbody>


                <table style="padding: 0px 10px 0 10px; font-size: 14px;" width="100%">
                    <tbody style="padding: 100px;">
                    <tr>

                        <td width="100%"
                            style=" background-color:black; padding: 2px 10px; color: white; text-align: center;">Terms
                            & Condition
                        </td>

                    </tr>
                    </tbody>
                </table>

                <table style=" padding: 0 10px; font-size: 14px;" width="100%">
                    <tbody style="padding: 100px;">
                    <tr>
                        <td width="70%"
                            style=" padding: 4px 10px; border:1px solid black;width: 100% ;text-align: left;">
                            <ul>
                                <li>The voucher having a payment date is confirmed as a paid voucher</li>
                                <li>Payment Voucher is non-transferable to another student.</li>
                                <li>This voucher is non-refundable in all circumstances</li>
                                <li>For any questions regarding your account and the payments, contact customer
                                    services
                                </li>
                            </ul>
                        </td>


                    </tr>


                    </tbody>
                </table>


        </div>
    </div>
    <div class="column" style=" font-size: 14px; border: 1px solid black;">
        <div class="buyer-info" style="">


            <table style="padding: 10px 10px 0 10px;table-layout: fixed; width: 100%"
            " width="100%" >
            <h2 align="center" style="margin:5px 0;">ONEZ COMMERCE</h2>
            <p style="text-align: center; margin: 0; font-size: 12px;">357 Saman Berg Society, Allah Hoo Chowk,<br>
                Block E Johar Town, Lahore, 54000, Pakistan</p>


            <h3 style="padding: 0px 0px;text-align: center;">Fee Voucher</h3>

            <p style="margin: 10px  10px 0px; display: flex;align-items: center;">Voucher No: <span
                    style="padding:5px 7px; border: 1px solid black; margin-left: 10px;">O</span> <span
                    style="padding:5px 7px; border: 1px solid black;">C</span> <span
                    style="padding:5px 7px; border: 1px solid black;">0</span><span
                    style="padding:5px 7px; border: 1px solid black;">0</span><span
                    style="padding:5px 7px; border: 1px solid black;">0</span><span
                    style="padding:5px 7px; border: 1px solid black;">1</span></p>
            <p style="margin: 10px  10px 0px;">Date:<span
                    style="border-bottom: 1px solid black; width: 70px;padding: 1px 70px;">{!! $data[0]['start_date'] !!}</span>
            </p>

            <tbody style="padding: 100px; font-size: 14px;">
            <tr>

                <td width="100%" style=" background-color:black; padding: 2px 10px; color: white; text-align: center;">
                    Details
                </td>

            </tr>

            </tbody>
            </table>
            <table style=" padding: 0 10px; font-size: 14px;table-layout: fixed; width: 100%" width="100%">
                <tbody style="padding: 100px;">
                <tr>
                    <td width="70%" style="background-color:black; padding: 2px 10px; color: white; text-align: left;">
                        Student Name
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->student_fee->student['name'] !!}</td>

                </tr>


                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Contact Number
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;"> {!! $data[0]->student_fee->student['mobile_no'] !!}</td>

                </tr>


                <tr style="word-wrap: break-word;">
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Email-ID
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;text-wrap:normal;word-wrap:break-word">
                        {!! $data[0]->student_fee->student['email'] !!}
                    </td>

                </tr>


                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Course Title
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->student_fee->course['name'] !!}</td>

                </tr>

                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Course Fee
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->student_fee->course['fee'] !!}</td>

                </tr>
                <tr>
                    <td width="70%" style="background-color:black; padding: 2px 10px; color: white; text-align: left;">
                        Campus/Online
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">Campus</td>

                </tr>


                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Session
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">
                        @if($data[0]->student_fee->session) {!! $data[0]->student_fee->session['title'] !!} @endif</td>

                </tr>


                {{--                <tr>--}}
                {{--                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">--}}
                {{--                        Start Date--}}
                {{--                    </td>--}}
                {{--                    <td style="border:1px solid black; padding: 2px 10px;">--}}
                {{--                        @if($data[0]->student_fee->session) {!! $data[0]->student_fee->session['start_date'] !!} @endif</td>--}}

                {{--                </tr>--}}

                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Due Date
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">
                        @if($data[0]) {!! $data[0]->due_date !!} @endif</td>

                </tr>
                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Class Timings
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">
                        @if($data[0]->student_fee->session)  {!! $data[0]->student_fee->session['start_time'] !!}
                        -{!! $data[0]->student_fee->session['end_time'] !!} @endif</td>

                </tr>
                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Payment Method
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->source !!}</td>

                </tr>
                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Payment Date
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">
                        @if($data[0]->paid_date) {!! $data[0]->paid_date !!}
                        @endif
                    </td>

                </tr>
                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        @if($data[0]->paid_status=='paid')   Paid Amount  @else     Payable Amount @endif
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->installement_amount !!}</td>

                </tr>
                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Remaining Fee
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->student_fee['remaining_amount'] !!}</td>

                </tr>

                </tbody>


                <table style="padding: 0px 10px 0 10px; font-size: 14px;" width="100%">
                    <tbody style="padding: 100px;">
                    <tr>

                        <td width="100%"
                            style=" background-color:black; padding: 2px 10px; color: white; text-align: center;">Terms
                            & Condition
                        </td>

                    </tr>
                    </tbody>
                </table>

                <table style=" padding: 0 10px; font-size: 14px;" width="100%">
                    <tbody style="padding: 100px;">
                    <tr>
                        <td width="70%"
                            style=" padding: 4px 10px; border:1px solid black;width: 100% ;text-align: left;">
                            <ul>
                                <li>The voucher having a payment date is confirmed as a paid voucher</li>
                                <li>Payment Voucher is non-transferable to another student.</li>
                                <li>This voucher is non-refundable in all circumstances</li>
                                <li>For any questions regarding your account and the payments, contact customer
                                    services
                                </li>
                            </ul>
                        </td>


                    </tr>


                    </tbody>
                </table>


        </div>
    </div>
    <div class="column" style=" font-size: 14px; border: 1px solid black;">
        <div class="buyer-info" style="">


            <table style="padding: 10px 10px 0 10px;table-layout: fixed; width: 100%"
            " width="100%" >
            <h2 align="center" style="margin:5px 0;">ONEZ COMMERCE</h2>
            <p style="text-align: center; margin: 0; font-size: 12px;">357 Saman Berg Society, Allah Hoo Chowk,<br>
                Block E Johar Town, Lahore, 54000, Pakistan</p>


            <h3 style="padding: 0px 0px;text-align: center;">Fee Voucher</h3>

            <p style="margin: 10px  10px 0px; display: flex;align-items: center;">Voucher No: <span
                    style="padding:5px 7px; border: 1px solid black; margin-left: 10px;">O</span> <span
                    style="padding:5px 7px; border: 1px solid black;">C</span> <span
                    style="padding:5px 7px; border: 1px solid black;">0</span><span
                    style="padding:5px 7px; border: 1px solid black;">0</span><span
                    style="padding:5px 7px; border: 1px solid black;">0</span><span
                    style="padding:5px 7px; border: 1px solid black;">1</span></p>
            <p style="margin: 10px  10px 0px;">Date:<span
                    style="border-bottom: 1px solid black; width: 70px;padding: 1px 70px;">{!! $data[0]['start_date'] !!}</span>
            </p>

            <tbody style="padding: 100px; font-size: 14px;">
            <tr>

                <td width="100%" style=" background-color:black; padding: 2px 10px; color: white; text-align: center;">
                    Details
                </td>

            </tr>

            </tbody>
            </table>
            <table style=" padding: 0 10px; font-size: 14px;table-layout: fixed; width: 100%" width="100%">
                <tbody style="padding: 100px;">
                <tr>
                    <td width="70%" style="background-color:black; padding: 2px 10px; color: white; text-align: left;">
                        Student Name
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->student_fee->student['name'] !!}</td>

                </tr>


                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Contact Number
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->student_fee->student['mobile_no'] !!}</td>

                </tr>


                <tr style="word-wrap: break-word;">
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Email-ID
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;text-wrap:normal;word-wrap:break-word">
                        {!! $data[0]->student_fee->student['email'] !!}
                    </td>

                </tr>


                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Course Title
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->student_fee->course['name'] !!}</td>

                </tr>

                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Course Fee
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->student_fee->course['fee'] !!}</td>

                </tr>
                <tr>
                    <td width="70%" style="background-color:black; padding: 2px 10px; color: white; text-align: left;">
                        Campus/Online
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">Campus</td>

                </tr>


                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Session
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">
                        @if($data[0]->student_fee->session)  {!! $data[0]->student_fee->session['title'] !!} @endif</td>

                </tr>


                {{--                <tr>--}}
                {{--                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">--}}
                {{--                        Start Date--}}
                {{--                    </td>--}}
                {{--                    <td style="border:1px solid black; padding: 2px 10px;">--}}
                {{--                        @if($data[0]->student_fee->session) {!! $data[0]->student_fee->session['start_date'] !!} @endif</td>--}}

                {{--                </tr>--}}
                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Due Date
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">
                        @if($data[0]) {!! $data[0]->due_date !!} @endif</td>

                </tr>

                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Class Timings
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">
                        @if($data[0]->student_fee->session) {!! $data[0]->student_fee->session['start_time'] !!}
                        -{!! $data[0]->student_fee->session['end_time'] !!} @endif</td>

                </tr>
                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Payment Method
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->source !!}</td>

                </tr>

                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Payment Date
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">
                        @if($data[0]->paid_date) {!! $data[0]->paid_date !!}
                        @endif
                    </td>

                </tr>
                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        @if($data[0]->paid_status=='paid')   Paid Amount  @else     Payable Amount @endif
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->installement_amount !!}</td>

                </tr>
                <tr>
                    <td width="70%" style="background-color: black; padding: 2px 10px; color: white; text-align: left;">
                        Remaining Fee
                    </td>
                    <td style="border:1px solid black; padding: 2px 10px;">{!! $data[0]->student_fee['remaining_amount'] !!}</td>

                </tr>

                </tbody>


                <table style="padding: 0px 10px 0 10px; font-size: 14px;" width="100%">
                    <tbody style="padding: 100px;">
                    <tr>

                        <td width="100%"
                            style=" background-color:black; padding: 2px 10px; color: white; text-align: center;">Terms
                            & Condition
                        </td>

                    </tr>
                    </tbody>
                </table>

                <table style=" padding: 0 10px; font-size: 14px;" width="100%">
                    <tbody style="padding: 100px;">
                    <tr>
                        <td width="70%"
                            style=" padding: 4px 10px; border:1px solid black;width: 100% ;text-align: left;">
                            <ul>
                                <li>The voucher having a payment date is confirmed as a paid voucher</li>
                                <li>Payment Voucher is non-transferable to another student.</li>
                                <li>This voucher is non-refundable in all circumstances</li>
                                <li>For any questions regarding your account and the payments, contact customer
                                    services
                                </li>
                            </ul>
                        </td>


                    </tr>


                    </tbody>
                </table>


        </div>
    </div>
</div>
</body>
</html>
