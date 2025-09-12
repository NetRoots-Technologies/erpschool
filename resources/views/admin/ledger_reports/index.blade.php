@extends('admin.layouts.main')

@section('css')
@stop
@section('content')
    <div class="box box-primary">
        <br>
        <form id="ledger-form">
            <div class="row">
                @csrf
                <div class="col-md-2">
                    <label>Start Date</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                        </div>
                        <input id=" " class="form-control" name="start_range" type="date">
                    </div>

                </div>
                <div class="col-md-2">
                    <label>End Date</label>
                    <div class="input-group">
                        <div class="input-group-addon">

                        </div>
                        <input id=" " class="form-control" name="end_range" type="date">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Select Ledger</label>
                        <select name='leadger_id' class="form-control input-sm  select2  " id=" ">
                            <option value="">---Select---</option>
                            @foreach($ledger as $item)
                                <option value="{!!$item->id  !!}">{!! $item->group_number !!}  {!! $item->number !!}   {!! $item->name !!} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Select Currency</label>
                        <select name='currency' class="form-control input-sm">
                            <option value="">---Currency---</option>
                            @foreach($currencies as $key=>$val)
                                <option value="{{$val}}">{{$key}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label style="visibility: hidden">End Date</label>
                        <button type="button" class="btn btn-sm btn-primary" onclick="fetch_ledger()"><i
                                class="fa fa-search"></i></button>
                    </div>
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
                    <th style="text-align: right;">{{__('messages.ledger_report.dr')}}</th>
                    <th style="text-align: right;">{{__('messages.ledger_report.cr')}}</th>
                    <th>{{__('messages.ledger_report.balance')}}</th>
                </tr>
                </thead>
                <tr id="fetch_ob"></tr>
                <tbody id="getData"></tbody>
            </table>
        </div>
    </div>
@stop
@section('js')
    <script>

        $('#search_ledger_id').select2({
            placeholder: 'Select an option',
            allowClear: true,
            "language": {
                "noResults": function () {
                    return 'Please Enter 2 or more character';
                }
            },
            ajax: {
                url: 'voucher/gjv/search',
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        item: params.term,
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
            }
        });

        // get ledger's
        function fetch_ledger() {

            $.ajax({
                url: '{!! route('get_ledger_rep') !!}',
                data: $("#ledger-form").serialize(),
                type: "POST",
                success: function (data) {
                    var htmlData = "";
                    for (i in data.data) {
                        //console.log(data.data[i].vt);
                        htmlData += '<tr>';
                        htmlData += '<td>' + data.data[i].voucher_date + '</td>';
                        htmlData += '<td>' + data.data[i].number + '</td>';
                        htmlData += '<td>' + data.data[i].vt + '</td>';
                        htmlData += '<td>' + data.data[i].narration + '</td>';
                        htmlData += '<td>' + data.data[i].dr_amount + '</td>';
                        htmlData += '<td>' + data.data[i].cr_amount + '</td>';
                        htmlData += '<td style="text-align: right;">' + data.data[i].balance + '</td>';
                        htmlData += '</tr>';
                    }
                    $("#getData").html(htmlData);
                    $('#fetch_ob').html(data.ob);
                }
            })
        }
    </script>
    <script src="{{ url('public/adminlte') }}/bower_components/PACE/pace.min.js"></script>
    <!-- date-range-picker -->
    <script src="{{ url('public/adminlte') }}/bower_components/moment/min/moment.min.js"></script>
    <script src="{{ url('public/adminlte') }}/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="{{ url('public/js/admin/entries/voucher/journal_voucher/create_modify.js') }}"
            type="text/javascript"></script>
@endsection
