@extends('layouts.app')
@section('breadcrumbs')
    <section class="content-header" style="padding: 10px 15px !important;">
        <h1>LC Payment Voucher</h1>
    </section>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Create LC Payment Voucher</h3>
            <a href="{{ route('admin.entries.index') }}" class="btn btn-success pull-right">Back</a>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {!! Form::open(['method' => 'POST', 'route' => ['admin.voucher.lcpv_store'], 'id' => 'validation-form']) !!}
            <div class="box-body">
                @include('admin.entries.voucher.lc_voucher.lc_payment.fields')
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger globalSaveBtn']) !!}
            </div>
        {!! Form::close() !!}
        @include('admin.entries.voucher.lc_voucher.lc_payment.entries_template')
    </div>
@stop

@section('javascript')
    <script src="{{ url('public/js/admin/entries/voucher/lc_voucher/lc_payment/create_modify.js') }}" type="text/javascript"></script>

@endsection

