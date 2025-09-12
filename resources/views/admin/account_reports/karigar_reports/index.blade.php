@inject('request', 'Illuminate\Http\Request')
@inject('Currency', '\App\Helpers\Currency')
@inject('CoreAccounts', '\App\Helpers\CoreAccounts')
@inject('AccountsList', '\App\Helpers\AccountsList')
@extends('layouts.app')

@section('stylesheet')
    <!-- Pace style -->
    <link rel="stylesheet" href="{{ url('public/adminlte') }}/plugins/pace/pace.min.css">
    <!-- Select2 -->
    {{--<link rel="stylesheet" href="{{ url('adminlte') }}/bower_components/select2/dist/css/select2.min.css">--}}
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ url('public/adminlte') }}/bower_components/bootstrap-daterangepicker/daterangepicker.css">
@stop

@section('breadcrumbs')
    <section class="content-header" style="padding: 10px 15px !important;">
        <h1>Account Reports</h1>
    </section>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <i class="fa fa-list"></i><h3 class="box-title">KARIGAR ACCOUNT REPORT</h3>
        </div>
        <!-- /.box-header -->
        <div class="panel-body pad table-responsive">
            <form action="{{ route('admin.print_karigar_report') }}" method="post" target="_blank">
                @CSRF
                <div class="form-group col-md-3  @if($errors->has('date_range')) has-error @endif">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        {!! Form::text('date_range', null, ['id' => 'date_range', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="form-group col-md-2 @if($errors->has('group_id')) has-error @endif">
                    <button type="submit" id="load_report" class="btn btn-success">Load Report</button>
                </div>
            </form>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>V.NO</th>
                    <th>DATE</th>
                    <th>DESCRIPTIONS</th>
                    <th>ITEM NAME</th>
                    <th>SHIFT TO</th>
                    <th>GROSS WT</th>
                    <th>DIA WT</th>
                    <th>ST ST</th>
                    <th>BEEDS</th>
                    <th>NET WT</th>
                    <th>WASTE/RATE</th>
                    <th>TOTAL/WT</th>
                    <th>PURITY</th>
                    <th>MAKING</th>
                    <th>OTHERS</th>
                    <th>DAI $</th>
                    <th>ST RS</th>
                    <th>ST Rs</th>
                    <th>BEEDS RS</th>
                    <th style="padding: 0px; text-align: center">PURE WT
                        <table class="table-bordered" style="width: 100; text-align: center">
                            <tr>
                                <td>Dr</td>
                                <td>Cr</td>
                            </tr>
                        </table>
                    </th>
                    <th style="padding: 0px; text-align: center">TOTAL PKR
                        <table class="table-bordered" style="width: 100; text-align: center">
                            <tr>
                                <td>Dr</td>
                                <td>Cr</td>
                            </tr>
                        </table>
                    </th>
                    <th style="padding: 0px; text-align: center">CURRENCY
                        <table class="table-bordered" style="width: 100; text-align: center">
                            <tr>
                                <td>Dr</td>
                                <td>Cr</td>
                            </tr>
                        </table>
                    </th>
                    <th>GOLD BAL</th>
                    <th>PKR BAL</th>
                    <th>CURRENCY BAL</th>
                </tr>
                </thead>
                <tr>
                    <td colspan="25"><h5><u>Payments:</u></h5></td>
                </tr>
                <tr>
                    <td>V.NO</td>
                    <td>Date</td>
                    <td>DESCRIPTIONS</td>
                    <td colspan="16"></td>
                    <th style="padding: 0px; text-align: center">PURE WT
                        <table class="table-bordered" style="width: 100; text-align: center">
                            <tr>
                                <td>Dr</td>
                                <td>Cr</td>
                            </tr>
                        </table>
                    </th>
                    <th style="padding: 0px; text-align: center">TOTAL PKR
                        <table class="table-bordered" style="width: 100; text-align: center">
                            <tr>
                                <td>Dr</td>
                                <td>Cr</td>
                            </tr>
                        </table>
                    </th>
                    <th style="padding: 0px; text-align: center">CURRENCY
                        <table class="table-bordered" style="width: 100; text-align: center">
                            <tr>
                                <td>Dr</td>
                                <td>Cr</td>
                            </tr>
                        </table>
                    </th>
                    <th>GOLD BAL</th>
                    <th>PKR BAL</th>
                    <th>CURRENCY BAL</th>
                </tr>
            </table>
        </div>
    </div>
@stop

@section('javascript')
    <!-- PACE -->
    <script src="{{ url('public/adminlte') }}/bower_components/PACE/pace.min.js"></script>
    <!-- date-range-picker -->
    <script src="{{ url('public/adminlte') }}/bower_components/moment/min/moment.min.js"></script>
    <script src="{{ url('public/adminlte') }}/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- Select2 -->
    {{--<script src="{{ url('adminlte') }}/bower_components/select2/dist/js/select2.full.min.js"></script>--}}
    <script src="{{ url('public/js/admin/account_reports/ledger_statement.js') }}" type="text/javascript"></script>
@endsection