@extends('admin.layouts.main')

@section('css')
    <style>.error {
            color: red;
            font-weight: 500;
            font-size: 14px;
        }</style>
@stop

@section('breadcrumbs')
    <section class="content-header" style="padding: 10px 15px !important;">
        <h1>Account Reports</h1>
    </section>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Trial Balance Report</h3>
        </div>
        <!-- /.box-header -->
        <div class="panel-body pad table-responsive">
            <form action="" method="post" id="trialform">
                @csrf
                <div class="row">

                    <div class="col-md-3">
                        <label>Start Date</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                            </div>
                            <input id="start_range" class="form-control" name="start_range" type="date" required>
                        </div>

                    </div>
                    <div class="col-md-3">
                        <label>End Date</label>
                        <div class="input-group">
                            <div class="input-group-addon">

                            </div>
                            <input id="end_range" class="form-control" name="end_range" type="date" required>
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="name"> Currency <b>*</b> </label>
                        <select class="form-control" name="currency_id" required>
                            {!! \App\Helpers\Currency::currencyList() !!}
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="branch_id">Branch</label>
                        <select class="form-control" name="branch_id">
                            <option value="">Select</option>
                            @foreach($branches as $key => $name)
                                <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-1 mt-4">
                        <button type="button" onclick="loadReport(`web`);" id="load_report" formmethod="post"
                                formtarget="_blank" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    </div>

                </div>


                <div class="clearfix"></div>
                <div class="load_report" id="content"></div>

            </form>
        </div>
    </div>
@stop

@section('js')

    <script>
        var loadReport = function (type = "web") {

            $.validator.addMethod(
                "endDateAfterStartDate",
                function (value, element) {
                    let startDate = new Date($("#start_range").val());
                    let endDate = new Date(value);
                    return this.optional(element) || endDate >= startDate;
                },
                "End Date cannot be earlier than Start Date."
            );

            $("#trialform").validate({
                errorPlacement: function (error, element) {
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

            $('#load_report').html('<i class="fa fa-spin fa-refresh"></i>&nbsp;').attr('disabled', true);
            if (!$("#trialform").valid()) {
                $('#load_report').html('<i class="fa fa-search"></i>').removeAttr('disabled');
                return false;
            }
            $.ajax({
                url: 'trial-balance-report-print',
                type: "GET",
                data: {
                    start_date: $('#start_range').val(),
                    end_date: $('#end_range').val(),
                    currency_id: $('#currency_id').val(),
                    medium_type: type,
                    account_type_id: '7',
                },
                success: function (response) {
                    if (type === 'pdf') {
                        const blob = new Blob([response], {type: 'application/pdf'});
                        const link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = 'TrialBalanceReport.pdf';
                        link.click();
                    } else if (type === 'excel') {
                        // Handle Excel download (if implemented)
                    } else if (type === 'print') {
                        const printWindow = window.open('', '_blank');
                        printWindow.document.write(response);
                    } else {
                        $('#content').html(response);
                    }
                    $('#load_report').html(`<i class="fa fa-search"></i>`).removeAttr('disabled');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $('#load_report').html('<i class="fa fa-search"></i>').removeAttr('disabled');
                    return false;
                }
            });
        }


    </script>
@endsection
