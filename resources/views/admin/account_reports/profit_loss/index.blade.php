@extends('admin.layouts.main')

@section('stylesheet')
<!-- Pace style -->
<link rel="stylesheet" href="{{ url('public/adminlte') }}/plugins/pace/pace.min.css">
<!-- Select2 -->
{{--
<link rel="stylesheet" href="{{ url('adminlte') }}/bower_components/select2/dist/css/select2.min.css">--}}
<!-- daterange picker -->
<link rel="stylesheet"
    href="{{ url('public/adminlte') }}/bower_components/bootstrap-daterangepicker/daterangepicker.css">
@stop

@section('css')
<style>
    .error{
        color: red;
        font-weight: bold;
        font-size: 14px;
    }
</style>
@stop

@section('breadcrumbs')
<section class="content-header" style="padding: 10px 15px !important;">
    <h1>Account Reports</h1>
</section>
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Profit and Loss Report</h3>
    </div>
    <div class="panel-body pad table-responsive">
        <form action="" method="post" id="profit_lossForm">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <label>Start Date</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                        </div>
                        <input id="start_range" class="form-control" name="start_range" type="date">
                    </div>
                </div>
                <div class="col-md-3">
                    <label>End Date</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                        </div>
                        <input id="end_range" class="form-control" name="end_range" type="date">
                    </div>
                </div>
                <div class="form-group col-md-1 mt-4">
                    <button type="button" onclick="loadReport('web');" id="load_report" formmethod="post"
                        formtarget="_blank" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
    </div> <!-- Close panel-body pad table-responsive -->
    <div class="clear clearfix"></div>
    <div id="content"></div>
</div> <!-- Close box box-primary -->
@stop


@section('js')
<script>
    var loadReport = function (type) {

        $.validator.addMethod(
        "endDateAfterStartDate",
        function (value, element) {
            let startDate = new Date($("#start_range").val());
            let endDate = new Date(value);
            return this.optional(element) || endDate >= startDate;
        },
        "End Date cannot be earlier than Start Date."
    );


        $("#profit_lossForm").validate({
            errorPlacement: function (error,element) {
                error.insertAfter(element.parent());
            },
            rules: {
                start_range: {
                    required: true,
                    date: true,
                },
                end_range: {
                    required: true,
                    date: true,
                    endDateAfterStartDate: true,

                },
                currency_id: {
                    required: true,
                }
            },
            messages: {
                start_range: {
                    required: "Please select a start date.",
                    date: "Please enter a valid date.",
                },
                end_range: {
                    required: "Please select an end date.",
                    date: "Please enter a valid date.",
                    endDateAfterStartDate: "End Date cannot be earlier than Start Date.",
                },
                currency_id: {
                    required: "Please select a currency.",
                }
            }

        });

        if (!$("#profit_lossForm").valid()) {
            return false;
        }
        $('#load_report').html('<i class="fa fa-spin fa-refresh"></i>&nbsp;Load Report').attr('disabled',true);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: 'profit-loss-report',
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
</script>
<!-- PACE -->
{{-- <script src="{{ url('public/adminlte') }}/bower_components/PACE/pace.min.js"></script> --}}
<!-- date-range-picker -->
{{-- <script src="{{ url('public/adminlte') }}/bower_components/moment/min/moment.min.js"></script> --}}
{{-- <script src="{{ url('public/adminlte') }}/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
--}}
<!-- Select2 -->
{{--<script src="{{ url('adminlte') }}/bower_components/select2/dist/js/select2.full.min.js"></script>--}}
{{-- <script src="{{ url('public/js/admin/account_reports/profit_loss.js') }}" type="text/javascript"></script> --}}
@endsection
