@extends('layouts.app')

{{--@section('stylesheet')--}}
    {{--<!-- Select2 -->--}}
    {{--<link rel="stylesheet" href="{{ url('adminlte') }}/bower_components/select2/dist/css/select2.min.css">--}}
    {{--<!-- bootstrap datepicker -->--}}
    {{--<link rel="stylesheet" href="{{ url('adminlte') }}/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">--}}
{{--@stop--}}

@section('breadcrumbs')
    <section class="content-header" style="padding: 10px 15px !important;">
        <h1>Gold Receipt Voucher</h1>
    </section>
@stop
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Create Gold Receipt Voucher</h3>
            <a href="{{ route('admin.entries.index') }}" class="btn btn-success pull-right">Back</a>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {!! Form::open(['method' => 'POST', 'route' => ['admin.voucher.crv_store'], 'id' => 'validation-form']) !!}
            <div class="box-body">
                @include('admin.entries.voucher.gold_voucher.gold_receipt.fields')
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger globalSaveBtn']) !!}
            </div>
        {!! Form::close() !!}
        @include('admin.entries.voucher.gold_voucher.gold_receipt.entries_template')
    </div>
@stop

@section('javascript')
    {{--<!-- bootstrap datepicker -->--}}
    {{--<script src="{{ url('adminlte') }}/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>--}}
    {{--<!-- Select2 -->--}}
    {{--<script src="{{ url('adminlte') }}/bower_components/select2/dist/js/select2.full.min.js"></script>--}}
    <script src="{{ url('public/js/admin/entries/voucher/gold_voucher/gold_receipt/create_modify.js') }}" type="text/javascript"></script>
@endsection

