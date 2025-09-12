@inject('request', 'Illuminate\Http\Request')
@include('partials.head')

<style type="text/css">
    @page {
        margin: 10px 20px;
    }
    table{
        text-align: center;
    }
    @media print {
        table {
            font-size: 12px;
        }
        .tr-root-group {
            background-color: #F3F3F3;
            color: rgba(0, 0, 0, 0.98);
            font-weight: bold;
        }
        .tr-group {
            font-weight: bold;
        }
        .bold-text {
            font-weight: bold;
        }
        .error-text {
            font-weight: bold;
            color: #FF0000;
        }
        .ok-text {
            color: #006400;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
            padding: 2px !important;
        }

    }
    .table th{ text-align: center; }
    .last-th th{border-bottom: 1px solid}
</style>
<div class="panel-body pad table-responsive">
    @include('partials.print_head')
    <table align="center">
        <tbody>
        <tr>
            <td><h3 align="center"><span style="border-bottom: double;">Basic Ledger Report</span></h3>
                <p>{!! $ledger_name->name !!} ({!! date('d-m-Y',strtotime($start_date)) !!} To {!! date('d-m-Y', strtotime($end_date)) !!})</p>
            </td>
        </tr>
        <tr>
            <td align="center"><span></span>
            </td>
        </tr>
        </tbody>
    </table>
    <table class="table">
        <thead>
        <tr>
            <th width="10%" align="center">Date</th>
            <th width="5%" align="center">VN</th>
            <th width="12%" align="center">Voucher Type</th>
            <th width="40%">Descriptions</th>
            <th width="5%" align="center">Currency </th>
            <th width="10%" align="center">Debit </th>
            <th width="10%" align="center">Credit </th>
        </tr>
        </thead>
        <tbody>
        @foreach($opening_bal_array as $ob)
        <tr>
            <td colspan="6" align="right">Opening Balance As on in {{ $ob['currency'] }} {{ $ob['as_on_date'] }}</td>
            <td style="text-align: right">{{ $ob['amount'] }}</td>
        </tr>
        @endforeach
            @foreach($array as $data)
                <tr>
                    <td>{{ $data['voucher_date'] }}</td>
                    <td>{{ $data['number'] }}</td>
                    <td>{{ $data['vt'] }}</td>
                    <td style="text-align: left">{{ $data['narration'] }}</td>
                    <td>{{ $data['currency'] }}</td>
                    <td>{{ $data['dr_amount'] }}</td>
                    <td style="text-align: right">{{ $data['cr_amount'] }}</td>
                </tr>
            @endforeach
        @foreach($closing_bal_array as $cb)
            <tr>
                <td colspan="6" align="right">Closing Balance As on in {{ $cb['currency'] }}</td>
                <td style="text-align: right">{{ $cb['amount'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>