@extends('admin.layouts.main')
@section('stylesheet')
    <!-- Pace style -->
    <link rel="stylesheet" href="{{ url('public/adminlte') }}/plugins/pace/pace.min.css">
    <!-- Select2 -->
    {{--<link rel="stylesheet" href="{{ url('adminlte') }}/bower_components/select2/dist/css/select2.min.css">--}}
    <!-- daterange picker -->
    <link rel="stylesheet"
          href="{{ url('public/adminlte') }}/bower_components/bootstrap-daterangepicker/daterangepicker.css">
@stop

@section('breadcrumbs')
    <section class="content-header" style="padding: 10px 15px !important;">
        <h1>Account Reports</h1>
    </section>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border text-center">

            <h3 class="box-title">Expense Summary Report</h3>
        </div>
        <!-- /.box-header -->
        <div class="panel-body pad table-responsive">


            <form action="" method="post" class="w-100">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4  ">
                        <label for="name">Start Date<b>*</b> </label>
                        {!! Form::date('start', null, ['id' => 'date_range', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-4 ">
                        <label for="name"> End Date<b>*</b> </label>
                        {!! Form::date('end', null, ['id' => 'date_range', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-3" name="currency_id">
                        <label for="name"> Currency <b>*</b> </label>
                        <select class="form-control">
                            {!! \App\Helpers\Currency::currencyList() !!}
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label for="name"> Search </label>
                        <button class="btn btn-primary" formaction="expense_summary_report" formmethod="post"
                                formtarget="_target"><i class="fa fa-search"></i></button>
                        {{--                        <button type="submit" formaction="expense_summary_report?type=excel" formmethod="post"--}}
                        {{--                                formtarget="_blank" class="btn btn-success"><i class="fa fa fa-file-excel-o"></i></button>--}}
                        {{--                        <button type="submit" formaction="expense_summary_report?type=pdf" formmethod="post"--}}
                        {{--                                formtarget="_blank" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i></button>--}}
                    </div>
                    {{--                    <div class="col-md-2">--}}
                    {{--                        <button class="btn btn-success"><i class="fa fa-file-excel-o"></i></button>--}}
                    {{--                        <button class="btn btn-danger"><i class="fa fa-file-pdf-o"></i></button>--}}
                    {{--                    </div>--}}
                </div>
            {{--                <div class="form-group col-md-4  @if($errors->has('date_range')) has-error @endif">--}}
            {{--                    <div class="input-group">--}}
            {{--                        <lable>Start Date</lable>--}}
            {{--                        <input class="form-control" type="date" name="start_date"/>--}}
            {{--                        --}}{{--                        {!! Form::text('date_range', null, ['id' => 'date_range', 'class' => 'form-control']) !!}--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--                <div class="col-md-2 form-group">--}}
            {{--                    <select class="form-control" name="currency_id">--}}
            {{--                        {!! \App\Helpers\Currency::currencyList() !!}--}}
            {{--                    </select>--}}
            {{--                </div>--}}
            {{--                <div class="form-group col-md-1">--}}
            {{--                    <button type="submit" formaction="expense_summary_report" formmethod="post" formtarget="_blank"--}}
            {{--                            class="btn btn-primary"><i class="fa fa-search"></i></button>--}}
            {{--                </div>--}}
            {{--                <div class="form-group col-md-2">--}}
            {{--                    <button type="submit" formaction="expense_summary_report?type=excel" formmethod="post"--}}
            {{--                            formtarget="_blank" class="btn btn-success"><i class="fa fa fa-file-excel-o"></i></button>--}}
            {{--                    <button type="submit" formaction="expense_summary_report?type=pdf" formmethod="post"--}}
            {{--                            formtarget="_blank" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i></button>--}}
        </div>
        </form>


        <div class="clear clearfix"></div>
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
    <script src="{{ url('public/js/admin/account_reports/profit_loss.js') }}" type="text/javascript"></script>
@endsection
