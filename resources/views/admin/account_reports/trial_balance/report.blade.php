@inject('request', 'Illuminate\Http\Request')
@if($request->get('medium_type') != 'web')
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
    }
</style>
@endif
@inject('CoreAccounts', '\App\Helpers\CoreAccounts')
<div class="panel-body pad table-responsive">
    <div class="col-md-6">
        <h4>Trial Balance from {{ $start_date }} to {{ $end_date }}</h4>
    </div>
    @if($request->get('medium_type') == 'web')
    <div class="col-md-6">
        <div class="text-center pull-right">
            <button onclick="loadReport(`print`);" type="button" class="btn btn-flat"><i
                    class="fa fa-print"></i>&nbsp;Print</button>
        </div>
    </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Account Name</th>
                <th style="text-align: right;">Opening Dr</th>
                <th style="text-align: right;">Opening Cr</th>
                <th style="text-align: right;">Debit</th>
                <th style="text-align: right;">Credit</th>
                <th style="text-align: right;">Closing Dr</th>
                <th style="text-align: right;">Closing Cr</th>
            </tr>
        </thead>
        <tbody>
            {!! $ReportData !!}
        </tbody>
    </table>
</div>
