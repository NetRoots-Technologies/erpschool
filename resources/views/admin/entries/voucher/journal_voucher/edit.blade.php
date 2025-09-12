@extends('admin.layouts.main')


@section('stylesheet')
    {{--<!-- Select2 -->--}}
    {{--<link rel="stylesheet" href="{{ url('adminlte') }}/bower_components/select2/dist/css/select2.min.css">--}}
    {{--<!-- bootstrap datepicker -->--}}
    {{--<link rel="stylesheet" href="{{ url('adminlte') }}/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">--}}
@stop

@section('breadcrumbs')
    <section class="content-header" style="padding: 10px 15px !important;">
        <h1>Entries</h1>
    </section>
@stop

@section('content')
{{--    <div class="box box-primary">--}}
{{--        <div class="box-header with-border">--}}
{{--            <h3 class="box-title">Update Entry</h3>--}}
{{--            <a href="{{ route('admin.entries.index') }}" class="btn btn-success pull-right">Back</a>--}}
{{--        </div>--}}
{{--        <!-- /.box-header -->--}}
{{--        <!-- form start -->--}}
{{--        {!! Form::model($VoucherData, ['method' => 'PUT', 'id' => 'validation-form', 'route' => ['admin.entries.update', $VoucherData['id']]]) !!}--}}
{{--        <div class="box-body">--}}
{{--            @include('admin.entries.voucher.journal_voucher.fields')--}}
{{--        </div>--}}
{{--        <!-- /.box-body -->--}}

{{--        <div class="box-footer">--}}
{{--            {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger globalSaveBtn']) !!}--}}
{{--        </div>--}}
{{--        {!! Form::close() !!}--}}
{{--        @include('admin.entries.voucher.journal_voucher.entries_template')--}}
{{--    </div>--}}


    <div class="container">
        <div class="row justify-content-center p-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header"><strong>Update Entry</strong> <span class="float-end"><a
                                    href="{{ route('admin.entries.index') }}" class="btn btn-primary">Back</a></span>
                        </div>
                        <div class="card-body">
                            {!! Form::model($VoucherData, ['method' => 'PUT', 'id' => 'validation-form', 'route' => ['admin.entries.update', $VoucherData['id']]]) !!}

                            @csrf
                            <div class="row">
                                @include('admin.entries.voucher.journal_voucher.fields')
                            </div>
                            {!! Form::submit('Save', ['class' => 'btn btn-danger globalSaveBtn']) !!}
                            {!! Form::close() !!}
                            @include('admin.entries.voucher.journal_voucher.entries_template')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <!-- bootstrap datepicker -->

    <script src="{{ url('/js/admin/entries/voucher/journal_voucher/create_modify.js') }}" type="text/javascript"></script>
@endsection
