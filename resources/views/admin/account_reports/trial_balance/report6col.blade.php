@inject('request', 'Illuminate\Http\Request')
@if($request->get('medium_type') != 'web')
    @if($request->get('medium_type') == 'pdf')
        @include('partials.pdf_head')
    @else
        @include('partials.head')
    @endif
    
@endif
{{--<style type="text/css">--}}
    {{--table {--}}
 {{--width: 100%;--}}
{{--}--}}

{{--thead, tbody, tr, td, th { display: block; }--}}

{{--tr:after {--}}
 {{--content: ' ';--}}
 {{--display: block;--}}
 {{--visibility: hidden;--}}
 {{--clear: both;--}}
{{--}--}}
{{--thead th {--}}
 {{--height: 60px;--}}
{{--thead th, td {--}}
 {{--height: 60px;--}}

 {{--/*text-align: left;*/--}}
{{--}--}}

{{--tbody {--}}
 {{--height: 500px;--}}
 {{--overflow-y: auto;--}}
{{--}--}}

{{--thead {--}}
 {{--/* fallback */--}}
{{--}--}}


{{--tbody td, thead th {--}}
 {{--width: 110px;--}}
 {{--float: left;--}}
{{--}--}}
     {{----}}
 {{--</style>--}}
@inject('CoreAccounts', '\App\Helpers\CoreAccounts')
<div class="panel-body pad table-responsive">
    <div class="col-md-6">
        <h4>Trial Balance from {{ $start_date }} to {{ $end_date }}</h4>
    </div>
    @if($request->get('medium_type') == 'web')
        <div class="col-md-6">
            <div class="text-center pull-right">
                <button onclick="FormControls.printReport('excel');" type="button" class="btn bg-olive btn-flat"><i class="fa fa-file-excel-o"></i>&nbsp;Excel</button>
                <button onclick="FormControls.printReport('pdf');" type="button" class="btn btn-danger btn-flat"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</button>
                <button onclick="FormControls.printReport('print');" type="button" class="btn btn-flat"><i class="fa fa-print"></i>&nbsp;Print</button>
            </div>
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
        <tr>
            <th style="width:480px;">Account Name</th>
            <th>Account Type</th>
            <th style="text-align: right;">Opening Balance ({{ $DefaultCurrency->code }})</th>
            <th style="text-align: right;">Opening Balance ({{ $DefaultCurrency->code }})</th>
            <th style="text-align: right;">Debit ({{ $DefaultCurrency->code }})</th>
            <th style="text-align: right;">Credit ({{ $DefaultCurrency->code }})</th>
            <th style="text-align: right;">Closing Balance ({{ $DefaultCurrency->code }})</th>
            <th style="text-align: right;">Closing Balance ({{ $DefaultCurrency->code }})</th>
        </tr>
        </thead>
        <tbody>
            {!! $ReportData !!}
            <tr class="bold-text @if($CoreAccounts::calculate($accountlist->dr_total, $accountlist->cr_total, '==')) ok-text @else error-text @endif">
                <td align="right" colspan="2">Grand Total</td>
                <td align="right">00</td>
                <td align="right">00</td>
                <td align="right">{{ $CoreAccounts::toCurrency('d', $accountlist->dr_total) }}</td>
                <td align="right">{{ $CoreAccounts::toCurrency('c', $accountlist->cr_total) }}</td>
                <td align="right">00</td>
                <td align="right">00</td>
                {{--<td><span class="glyphicon @if($CoreAccounts::calculate($accountlist->dr_total, $accountlist->cr_total, '==')) glyphicon-ok-sign @else glyphicon-remove-sign @endif"></span></td>--}}
            </tr>
        </tbody>
    </table>
</div>