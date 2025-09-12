@extends('admin.layouts.main')

@inject('Currency', '\App\Helpers\Currency')
@inject('Ledger', '\App\Models\Admin\Ledgers')

@section('breadcrumbs')
<section class="content-header" style="padding: 10px 15px !important;">
    <h1>View {{ $EntryType->name }}: {{ $Entrie->number }}</h1>
</section>
@stop

@section('content')

<div class="container">
    <div class="row justify-content-center p-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header"><strong>View</strong> <span class="float-end"><a
                                href="{{ route('admin.entries.index') }}" class="btn btn-primary">Back</a></span>
                    </div>
                    <div class="card-body">
                        <div>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="10%"><b>Voucher #</b></th>
                                        <td width="35%">{{ $Entrie->number }}</td>
                                        <th width="13%"><b>Voucher Date</b></th>
                                        <td>{{ $Entrie->voucher_date }}</td>
                                    </tr>
                                    <tr>
                                        <th width="13%"><b>Branch Name</b></th>
                                        <td>NA</td>
                                        <th><b>Department</b></th>
                                        <td>{{ $Department->name ?? "N/A" }}</td>
                                    </tr>
                                    <tr>
                                        <th><b>Narration</b></th>
                                        <td colspan="4" style="text-align: left;">{{ $Entrie->narration ?? "N/A" }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Slip Area Started -->
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="pull-left header"><i class="fa fa-th"></i> Entry Items</li>
                                {{-- <li class="pull-right"><a href="#tab_2" data-toggle="tab"><u>P</u>arameters</a>
                                </li> --}}
                                {{-- <li class="active pull-right mt-3"><a href="#tab_1"
                                        data-toggle="tab"><u>B</u>asic</a></li> --}}
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <table class="table table-bordered" id="entry_items">
                                        <thead>
                                            <tr>
                                                <th colspan="2" style="text-align: center; font-weight: 1000; ">Account
                                                </th>
                                                {{-- <th width="8%" style="text-align: center;">Currency</th> --}}
                                                <th width="5%" style="text-align: center; font-weight: 1000; ">Debit
                                                </th>
                                                <th width="5%" style="text-align: center; font-weight: 1000; ">Credit
                                                </th>
                                                <th width="15%" style="text-align: center; font-weight: 1000; ">
                                                    Narration</th>

                                                {{-- <th width="8%" style="text-align: center;">Rate (Pkr)</th> --}}
                                                {{-- <th width="8%" style="text-align: center;">Currency</th> --}}
                                                {{-- <th width="8%" style="text-align: center;">Debit</th> --}}
                                                {{-- <th width="8%" style="text-align: center;">Credit</th> --}}
                                                {{--<th width="36%">Narration</th>--}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($dr_total=0)
                                            @php($cr_total=0)
                                            @if(count($Entry_items))
                                            @foreach($Entry_items as $Entry_item)

                                            <tr>
                                                <td colspan="2">
                                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                                        @if(isset($Ledgers) && !empty(2))
                                                        @if($Entry_item->ledger_id!=0)
                                                        {{$Entry_item->ledger->number}}
                                                        {{ $Ledgers[$Entry_item->ledger_id]->name }}
                                                        {{--{{ $Ledgers[$Entry_item->ledger_id]->name }}--}}
                                                        @else

                                                        N/A
                                                        @endif
                                                        @else
                                                        N/A
                                                        @endif
                                                    </div>
                                                </td>
                                                {{-- <td align="center">
                                                    <div class="form-group" style="margin-bottom: 0px !important;">


                                                        {{ $Entrie->currenciesA->code ?? "" }}
                                                    </div>
                                                </td> --}}
                                                <td align="right">
                                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                                        @if($Entry_item->dc == 'd')
                                                        @php($dr_total+=$Entry_item->amount)
                                                        {{
                                                        \App\Models\Admin\Currencies::curr_dec_format($Entry_item->amount,
                                                        $Entry_item->other_currency_type) }}@else
                                                        @endif
                                                    </div>
                                                </td>
                                                <td align="right">
                                                    <div class="form-group" style="margin-bottom: 0px !important;">

                                                        @if($Entry_item->dc == 'c')
                                                        @php($cr_total+=$Entry_item->amount)
                                                        {{\App\Models\Admin\Currencies::curr_dec_format($Entry_item->amount,
                                                        $Entry_item->other_currency_type) }}@else
                                                        @endif
                                                    </div>
                                                </td>
                                                <td align="left">
                                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                                        {{ $Entry_item->narration }}
                                                    </div>
                                                </td>
                                                {{-- <td align="center">
                                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                                        {{
                                                        \App\Models\Admin\Currencies::curr_dec_format($Entry_item->rate,
                                                        $Entry_item->other_currency_type) }}
                                                    </div>
                                                </td> --}}
                                                {{-- <td align="center">
                                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                                        @if(isset($Entry_item->itemCurrency)) {{
                                                        $Entry_item->itemCurrency->code ?? "" }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td align="center">
                                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                                        @if($Entry_item->dc ==
                                                        'd'){{\App\Models\Admin\Currencies::curr_dec_format($Entry_item->other_amount,
                                                        $Entry_item->other_currency_type) }}@else
                                                        @endif
                                                    </div>
                                                </td> --}}
                                                {{-- <td align="center">
                                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                                        @if($Entry_item->dc == 'c'){{
                                                        \App\Models\Admin\Currencies::curr_dec_format($Entry_item->other_amount,
                                                        $Entry_item->other_currency_type) }}@else
                                                        @endif
                                                    </div>
                                                </td> --}}
                                                {{--<td align="center">--}}
                                                    {{--<div class="form-group" style="margin-bottom: 0px !important;">
                                                        --}}
                                                        {{--{{ $Entry_item->narration }}--}}
                                                        {{--</div>--}}
                                                    {{--</td>--}}
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="8" align="center">No Entry Item found.</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td colspan="2" align="right" style="padding-top: 12px;" width="12%">
                                                    <b>Total</b>
                                                </td>
                                                {{-- <td width="12%" style="padding-top: 12px;" align="right"> --}}
                                                    {{--{{ number_format($Entrie->dr_total - $Entrie->cr_total, 2)
                                                    }}--}}
                                                </td>
                                                {{-- <td align="right" style="padding-top: 12px;" width="12%">
                                                    <b>Total</b> --}}
                                                </td>
                                                <td style="padding-top: 12px;" align="right">
                                                    {{--<b>{{ $Entrie->dr_total }}</b>--}}
                                                    <b>{!! $Currency::curreny_format($dr_total) !!}</b>
                                                </td>
                                                <td style="padding-top: 12px;" align="right">
                                                    {{--<b>{{ $Entrie->cr_total }}</b>--}}
                                                    <b>{!! $Currency::curreny_format($cr_total) !!}</b>
                                                </td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="tab_2">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="10%">Cheque #</th>
                                                <td width="40%">@if($Entrie->cheque_no){{ $Entrie->cheque_no }}@else N/A
                                                    @endif</td>
                                                <th width="10%">Cheque Date</th>
                                                <td width="40%">@if($Entrie->cheque_date){{ $Entrie->cheque_date }}@else
                                                    N/A @endif</td>
                                            </tr>
                                            <tr>
                                                <th>Invoice #</th>
                                                <td>@if($Entrie->invoice_no){{ $Entrie->invoice_no }}@else N/A @endif
                                                </td>
                                                <th>Invoice Date</th>
                                                <td>@if($Entrie->invoice_date){{ $Entrie->invoice_date }}@else N/A
                                                    @endif</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop


<script>
    $("#downloadPdf").click(function () {
        var inv_id = {{$Entrie->id}};
        var payable = $('#payable_amount').html();
        var credit_limit = parseInt($('#credit_limit').html());
        var customer_credit = $('#customer_credit').html();
        var payable = $('#payable_amount').html();
        var newPayable = parseInt(payable) + parseInt(customer_credit);
        if (newPayable > credit_limit) {
            if (confirm('Customer invoice is exceeding the credit limit. Do You still want to generate invoice?')) {
                window.open('downloadPDF/' + inv_id, '_blank');
            } else {
                return false
            }

        }

    });

</script>