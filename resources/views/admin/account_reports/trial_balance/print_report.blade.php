@inject('request', 'Illuminate\Http\Request')
@include('partials.head')

    <style type="text/css">
        @page {
            margin: 10px 20px;
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
        <td><h3 align="center"><span style="border-bottom: double;">Trial Balance Report</span></h3>
        </td>
    </tr>
    <tr>
        <td align="center"><span>Trial Balance from {{ $start_date }} To {{ $end_date }}</span>
        </td>
    </tr>
    </tbody>
    </table>
    <table class="table">
        <thead>
        <tr>
            <th>Account Name</th>
            <th width="10%" align="center">Opening Dr</th>
            <th width="10%" align="center">Opening Cr</th>
            <th width="10%" align="center">Debit</th>
            <th width="10%" align="center">Credit </th>
            <th width="12%" align="center">Closing Debit </th>
            <th width="12%" align="center">Closing Credit </th>
        </tr>
        </thead>
        <tbody>
         {!! $data !!}
        </tbody>
    </table>
</div>
