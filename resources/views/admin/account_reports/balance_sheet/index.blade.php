@extends('admin.layouts.main')



@section('breadcrumbs')
<section class="content-header" style="padding: 10px 15px !important;">
    <h1>Account Reports</h1>
</section>
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Balance Sheet Report</h3>
    </div>
    <div class="panel-body pad table-responsive">
        <form action="" id="balanceForm">
            @csrf
            <div class="row">
                <div class="form-group col-md-3  ">
                    <label for="name">Start Date<b>*</b> </label>
                    {!! Form::date('start', null, ['id' => 'date_range', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-3  ">
                    <label for="name"> End Date<b>*</b> </label>
                    {!! Form::date('end', null, ['id' => 'date_range', 'class' => 'form-control']) !!}
                </div>

                <div class="form-group col-md-2" name="currency_id">
                    <label for="name"> Currency <b>*</b> </label>
                    <select class="form-control">

                        {!! \App\Helpers\Currency::currencyList() !!}
                    </select>
                </div>
                <div class="col-md-1">

                    <label for="name"> Search </label>
                    <button type="button" onclick="loadReport(`web`);" id="load_report" formmethod="post"
                        formtarget="_blank" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
        <div class="clear clearfix"></div>

        <div id="content"></div>

        {{--{!! Form::open(['method' => 'POST', 'target' => '_blank', 'route' =>
        ['admin.account_reports.balance_sheet_report'], 'id' => 'report-form']) !!}--}}
        {{--{!! Form::hidden('date_range', null, ['id' => 'date_range-report']) !!}--}}
        {{--{!! Form::hidden('branch_id', null, ['id' => 'branch_id-report']) !!}--}}
        {{--{!! Form::hidden('employee_id', null, ['id' => 'employee_id-report']) !!}--}}
        {{--{!! Form::hidden('department_id', null, ['id' => 'department_id-report']) !!}--}}
        {{--{!! Form::hidden('entry_type_id', null, ['id' => 'entry_type_id-report']) !!}--}}
        {{--{!! Form::hidden('account_type_id', null, ['id' => 'account_type_id-report']) !!}--}}
        {{--{!! Form::hidden('group_id', null, ['id' => 'group_id-report']) !!}--}}
        {{--{!! Form::hidden('medium_type', null, ['id' => 'medium_type-report']) !!}--}}
        {{--{!! Form::close() !!}--}}
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function () {

    var loadReport = function (type) {

        $('#load_report').html('<i class="fa fa-spin fa-refresh"></i>&nbsp;Load Report').attr('disabled',true);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: 'balance-sheet-report',
            type: "POST",
            data: {
                date_range: $('#date_range').val(),
                branch_id: $('#branch_id').val(),
                employee_id: $('#employee_id').val(),
                department_id: $('#department_id').val(),
                entry_type_id: $('#entry_type_id').val(),
                account_type_id: $('#account_type_id').val(),
                group_id: $('#group_id').val(),
                medium_type: type,
            },
            success: function(response){
                if (type === 'print') {
                    const printWindow = window.open('', '_blank');
                    printWindow.document.write(response);
                } else {
                    $('#content').html(response);
                }
                // $('#content').html(response);
                $('#load_report').html(`<i class="fa fa-search"></i>`).removeAttr('disabled');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $('#load_report').html('Load Report').removeAttr('disabled');
                return false;
            }
        });
    }


    $.ajax{
        data:{table_name:table_name}
    }

});
</script>
{{-- <script src="{{ url('public/adminlte') }}/bower_components/PACE/pace.min.js">
    </s>
<script src="{{ url('public/adminlte') }}/bower_components/moment/min/moment.min.js"></scrip>
<script src="{{ url('public/adminlte') }}/bower_components/bootstrap-daterangepicker/daterangepicker.js"></>
<script src="{{ asset('/js/admin/account_reports/balance_sheet.js') }}" type="text/javascript">
</script> --}}
{{-- balance-sheet-report --}}
@endsection
