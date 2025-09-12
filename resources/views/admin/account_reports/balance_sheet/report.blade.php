{{--

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
@if($request->get('medium_type') == 'web')
<div class="col-md-6">
    <div class="text-center pull-right">
        <button onclick="loadReport(`print`);" type="button" class="btn btn-flat"><i
                class="fa fa-print"></i>&nbsp;Print</button>
    </div>
</div>
@endif
<div class="panel-body pad table-responsive">

    <table width="100%">
        <tbody>
        </tbody>
    </table>
    <div class="clear clearfix"></div>

    <div class="col-md-12">
        <table class="table">
            <tbody>
                {!! $data !!}
            </tbody>
        </table>
    </div>
    --}}


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
            <h4>Balance Sheet Report from {{ $start_date }} to {{ $end_date }}</h4>
        </div>
        @if($request->get('medium_type') == 'web')
        <div class="col-md-6">
            <div class="text-center pull-right">
                <button onclick="loadReport(`print`);" type="button" class="btn btn-flat"><i
                        class="fa fa-print"></i>&nbsp;Print</button>
            </div>
        </div>
        @endif
    </div>

    <table class="table">
        <tbody>
            {!! $data !!}
        </tbody>
    </table>
</div>