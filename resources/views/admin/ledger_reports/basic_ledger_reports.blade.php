@extends('layouts.app')
@section('breadcrumbs')
    <section class="content-header" style="padding: 10px 15px !important;">
        <h1>Basic Ledger Report</h1>
    </section>
@stop
@section('stylesheet')
    <!-- Pace style -->
    {{--<link rel="stylesheet" href="{{ url('public/adminlte') }}/plugins/pace/pace.min.css">--}}
    <link rel="stylesheet" href="{{ url('public/adminlte') }}/bower_components/bootstrap-daterangepicker/daterangepicker.css">
@stop
@section('content')
    <div class="box box-primary">
        <br>
        <form id="ledger-form">
            @csrf
            <div class="col-md-3">
                <label>Date</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input id="date_range" class="form-control" name="date_range" type="text">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Select Ledger</label>
                    <select  name='leadger_id' class="form-control input-sm select2" id="search_ledger_id">
                        <option value="">---Select---</option>

                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Select Currency</label>
                    <select  name='currency' class="form-control input-sm">
                        <option value="">---Currency---</option>
                        {!! \App\Helpers\Currency::currencyList() !!}
                    </select>
                </div>
            </div>
            <div class="col-md-2" style="margin-top: 25px;">
                <div class="form-group">
                    <button type="button" class="btn btn-sm btn-primary" onclick="fetch_ledger()"><i class="fa fa-search"></i> </button>
                    <button type="submit" class="btn btn-sm btn-default" formaction="get_basic_ledger_report?type=print" formmethod="post" formtarget="_blank"><i class="fa fa-print"></i> </button>
                    <button type="submit" class="btn btn-sm btn-success" formaction="get_basic_ledger_report?type=excel" formmethod="post" formtarget="_blank"><i class="fa fa-file-excel-o"></i> </button>
                </div>
            </div>
        </form>
        <div class="clearfix"></div>
        <!-- /.box-header -->
        <div class="panel-body pad table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>{{__('messages.ledger_report.date')}}</th>
                    <th>{{__('messages.ledger_report.vn')}}</th>
                    <th>{{__('messages.ledger_report.vt')}}</th>
                    <th style="">{{__('messages.ledger_report.description')}}</th>
                    <th>{{__('messages.ledger_report.currency')}}</th>
                    <th>{{__('messages.ledger_report.dr')}}</th>
                    <th>{{__('messages.ledger_report.cr')}}</th>
                </tr>
                </thead>
                <tbody id="fetch_ob"></tbody>
                <tbody id="getData"></tbody>
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
    <script src="{{ url('public/js/admin/entries/voucher/journal_voucher/create_modify.js') }}" type="text/javascript"></script>
    <script src="{{ url('public/js/admin/ledger_reports/ledger_report.js') }}" type="text/javascript"></script>
@endsection