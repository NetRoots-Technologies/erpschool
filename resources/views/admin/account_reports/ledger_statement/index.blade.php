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
            <i class="fa fa-list"></i><h3 class="box-title">Ledger Statement Report</h3>
        </div>
        <!-- /.box-header -->
        <div class="panel-body pad table-responsive">
            {!! Form::open(['method' => 'POST', 'id' => 'account-reports-form']) !!}
                <div class="form-group col-md-3  @if($errors->has('date_range')) has-error @endif">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        {!! Form::text('date_range', null, ['id' => 'date_range', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="form-group col-md-3 @if($errors->has('branch_id')) has-error @endif">
                    {!! Form::select('branch_id', $Branches, null, ['id' => 'branch_id', 'style' => 'width: 100%;', 'class' => 'form-control select2']) !!}
                    <span id="branch_id_handler"></span>
                </div>
                <div class="form-group col-md-3 @if($errors->has('employee_id')) has-error @endif">
                    {!! Form::select('employee_id', $Employees, null, ['id' => 'employee_id', 'style' => 'width: 100%;', 'class' => 'form-control select2']) !!}
                    <span id="employee_id_handler"></span>
                </div>
                <div class="form-group col-md-3 @if($errors->has('department_id')) has-error @endif">
                    {!! Form::select('department_id', $Departments, null, ['id' => 'department_id', 'style' => 'width: 100%;', 'class' => 'form-control select2']) !!}
                    <span id="department_id_handler"></span>
                </div>
                <div class="form-group col-md-3 @if($errors->has('entry_type_id')) has-error @endif">
                    {!! Form::select('entry_type_id', $EntryTypes, null, ['id' => 'entry_type_id', 'style' => 'width: 100%;', 'class' => 'form-control select2']) !!}
                    <span id="entry_type_id_handler"></span>
                </div>
                <div class="form-group col-md-3 @if($errors->has('account_type_id')) has-error @endif">
                    {!! Form::select('account_type_id', $AccountTypes, null, ['id' => 'account_type_id', 'style' => 'width: 100%;', 'class' => 'form-control']) !!}
                    <span id="account_type_id_handler"></span>
                </div>
                <div class="form-group col-md-4 @if($errors->has('group_id')) has-error @endif">
                    <span id="group_id_content">
                        <select name="group_id" id="group_id" class="form-control select2" style="width: 100%;">
                            <option value=""> Select a Ledger </option>
                            @if (count($Ledgers) > 0)
                                @foreach ($Ledgers as $id => $data)
                                    @if ($id == 0) @continue; @endif
                                    <option value="{{ $id }}" @if ($id < 0) disabled="disabled" @endif >{{ $data['name'] }}   </option>
                                @endforeach
                            @endif
                        </select>
                    </span>
                    <span id="group_id_handler"></span>
                </div>
                <div class="form-group col-md-2 @if($errors->has('group_id')) has-error @endif">
                    <button type="submit" id="load_report" class="btn btn-success">Load Report</button>
                </div>
            {!! Form::close() !!}

            <div id="content"></div>

            {!! Form::open(['method' => 'POST', 'target' => '_blank', 'route' => ['admin.account_reports.ledger_statement_report'], 'id' => 'report-form']) !!}
                {!! Form::hidden('date_range', null, ['id' => 'date_range-report']) !!}
                {!! Form::hidden('branch_id', null, ['id' => 'branch_id-report']) !!}
                {!! Form::hidden('employee_id', null, ['id' => 'employee_id-report']) !!}
                {!! Form::hidden('department_id', null, ['id' => 'department_id-report']) !!}
                {!! Form::hidden('entry_type_id', null, ['id' => 'entry_type_id-report']) !!}
                {!! Form::hidden('account_type_id', null, ['id' => 'account_type_id-report']) !!}
                {!! Form::hidden('group_id', null, ['id' => 'group_id-report']) !!}
                {!! Form::hidden('medium_type', null, ['id' => 'medium_type-report']) !!}
            {!! Form::close() !!}
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
