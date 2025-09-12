<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">


    <style>

        * {
            box-sizing: border-box;
        }

        /* Create four equal columns that floats next to each other */
        .column {
            float: left;
            width: 50%;

            /* Should be removed. Only for demonstration */
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

    </style>


</head>


<style>

    body{
        padding: 0 30px;
        line-height: 14px;
    }
</style>

<body>

<!--header-->




@inject('Currency', '\App\Helpers\Currency')



@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
        <!-- /.box-header -->
            <div class="header">
                <h6 style="border-bottom: 1px solid black; font-size: 36px; padding-bottom: 7px; margin-bottom: 20px !important;">
                    OAG <Span style="margin-left: 100px;">Office Automation Group</Span>
                </h6>

                <p style="font-size: 9px; margin-top: -20px; margin-bottom: 20px;">
                    Head Office: 8/1 Habibullah Road, off Davis Road, Lahore 54000 Tel: 042-636 2835 Fax: 92-42-636 2834
                    Email: info@oag.com.pk Web Site: www.oag.com.pk
                </p>
            </div>
        <div class="box-body">
            <div >
                <table class="table table-bordered" style="border: 1px solid black; width: 100%; border-bottom: none;">
                    <tbody>
                    <tr>
                        <th width="10%" >Voucher #</th>
                        <td>{{ $Entrie->number }}</td>
                        <th width="20%">Voucher Date</th>
                        <td>{{ $Entrie->voucher_date }}</td>
                        <th width="10%">Branch</th>
                        <td>{{ $Branch->name }}</td>
                    </tr>
                    <tr>
                        <th>Department</th>
                        <td>@if($Department && isset($Department->name)){{ $Department->name }}@else N/A @endif</td>
                        <th>Employee</th>
                        <td>
                            @if($Employee == '')
                                Admin
                            @else
                                {{$Employee->first_name}}
                            @endif

                        </td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <th>Narration</th>
                        <td colspan="4">@if($Entrie->narration){{ $Entrie->narration }}@else{{ 'N/A' }}@endif</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Slip Area Started -->
            <div class="nav-tabs-custom">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <table class="table table-condensed" id="entry_items" style="border: 1px solid black; width: 100%;">
                            <thead>
                            <tr>
                                <th colspan="4">Account</th>
                                <th width="25%" style="">Debit</th>
                                <th width="25%" style="">Credit</th>
                                <th width="36%">Narration</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($Entry_items))
                                @foreach($Entry_items as $Entry_item)
                                    <tr>
                                        <td colspan="4">
                                            <div class="form-group" style="margin-bottom: 0px !important;">
                                                @if(isset($Ledgers) && !empty($Ledgers))
                                                    {{ $Ledgers[$Entry_item->ledger_id]->number }} - {{ $Ledgers[$Entry_item->ledger_id]->name }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group" style="margin-bottom: 0px !important;">
                                                @if($Entry_item->dc == 'd'){{ $DefaultCurrency->symbol . ' ' . $Currency::curreny_format($Entry_item->amount) }}@else
                                                    {{--{{ $DefaultCurrency->symbol . ' ' . $Currencies::curreny_format(0) }}--}}
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group" style="margin-bottom: 0px !important;">
                                                @if($Entry_item->dc == 'c'){{ $DefaultCurrency->symbol . ' ' . $Currency::curreny_format($Entry_item->amount) }}@else
                                                    {{--{{ $DefaultCurrency->symbol . ' ' . $Currencies::curreny_format(0) }}--}}
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group" style="margin-bottom: 0px !important;">
                                                {{ $Entry_item->narration }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" align="center">No Entry Item found.</td>
                                </tr>
                            @endif
                            <tr>
                                <td style="padding-top: 12px;" width="17%"><b>Difference</b></td>
                                <td width="35%" style="padding-top: 12px;">
                                    {{ number_format($Entrie->dr_total - $Entrie->cr_total, 2) }}
                                </td>
                                <td style="padding-top: 12px;" width="10%"><b>Total</b></td>
                                <td style="padding-top: 12px;" width="30%" >
                                    <b>{{ $Entrie->dr_total }}</b>
                                </td>
                                <td style="padding-top: 12px;" width="15%">
                                    <b>{{ $Entrie->cr_total }}</b>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_2">
                        <table class="table table-bordered" width="100%;">
                            <tbody>
                            <tr>
                                <th width="10%">Cheque #</th>
                                <td width="40%">@if($Entrie->cheque_no){{ $Entrie->cheque_no }}@else N/A @endif</td>
                                <th width="15%">Cheque Date</th>
                                <td width="35%">@if($Entrie->cheque_date){{ $Entrie->cheque_date }}@else N/A @endif</td>
                            </tr>
                            <tr>
                                <th>Invoice #</th>
                                <td>@if($Entrie->invoice_no){{ $Entrie->invoice_no }}@else N/A @endif</td>
                                <th>Invoice Date</th>
                                <td>@if($Entrie->invoice_date){{ $Entrie->invoice_date }}@else N/A @endif</td>
                            </tr>
                            <tr>
                                <th>CDR #</th>
                                <td>@if($Entrie->cdr_no){{ $Entrie->cdr_no }}@else N/A @endif</td>
                                <th>CDR Date</th>
                                <td>@if($Entrie->cdr_date){{ $Entrie->cdr_date }}@else N/A @endif</td>
                            </tr>
                            <tr>
                                <th>BDR #</th>
                                <td>@if($Entrie->bdr_no){{ $Entrie->bdr_no }}@else N/A @endif</td>
                                <th>CDR Date</th>
                                <td>@if($Entrie->bdr_date){{ $Entrie->bdr_date }}@else N/A @endif</td>
                            </tr>
                            <tr>
                                <th>Bank Name</th>
                                <td>@if($Entrie->bank_name){{ $Entrie->bank_name }}@else N/A @endif</td>
                                <th>Bank Branch</th>
                                <td>@if($Entrie->bank_branch){{ $Entrie->bank_branch }}@else N/A @endif</td>
                            </tr>
                            <tr>
                                <th>Drawn Date</th>
                                <td colspan="3">@if($Entrie->drawn_date){{ $Entrie->drawn_date }}@else N/A @endif</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
        </div>

<!--footer-->
            <p id="remarks"><b>Remarks:</b></p>


            <div class="authorised-sig" style=" margin-top: 60px;">
                <table style="width:100%">
                    <tr>
                        <td><p style="display: inline-block; margin-left: 70px; margin-bottom: -20px;">{{$Employee->first_name}}</p></td>
                    </tr>
                    <tr>
                        <td><p style="border-top: 1px solid black; display: inline-block; margin-left: 50px;">Prepared By</p></td>
                        <td><p style="border-top: 1px solid black; display: inline-block; margin-right: 20px;">Checked By</p></td>
                        <td><p style="border-top: 1px solid black; display: inline-block;">Approved By</p></td>
                    </tr>
                </table>
            </div>
            <div class="great">
                <p style="display: inline-block; font-family: arial;font-weight:bold;">Recieved By Name:-------------------------</p>

                <p style="display: inline-block;font-family: arial;font-weight:bold; margin-left: 230px;">Signature:-----------------------</p>


            </div>
            <p style="display: inline-block;font-family: arial;font-weight:bold;">Date:--------------------------</p>

        </div>
    </div>
</body>
</html>