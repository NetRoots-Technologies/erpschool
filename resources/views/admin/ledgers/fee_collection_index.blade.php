@extends('admin.layouts.main')

@section('title')
    Fee Collection
@stop
@section('css')
    <style>
        .bg-info {
            background-color: #525252 !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Fee Collection </h3>
        </div>

        <div class="row w-100 text-center">
            <div class="card basic-form table-responsive">
                <div class="card-body">
                    <div class="row mb-5 d-flex flex-row-reverse" id="apply_filter">
                        <div class="col-lg-3 p-2">
                            <button class="btn btn-outline-primary btn">Filters</button>

                        </div>
                    </div>
                    <div class="row">
                        <div class="filter   mb-5" id="filter" style="display: none">
                            <div class="card">
                                <div class="card-body">
                                    <form id="filterform" method="post">

                                        <div class="row">
                                            <div class="col-lg-4 mb-5">
                                                <label for="sessions">Student Name</label>
                                                <select name="student_name" class="select2 form-control"
                                                        id="student_id" required>
                                                    <option value="" selected disabled>Select Student</option>
                                                    @foreach($students as $student )
                                                        @if(isset($student->student))
                                                            <option
                                                                value="{{$student->student->id}}">{{$student->student->name .' - '. $student->student->mobile_no}}</option> @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-4 ">
                                                <label for="discount"><b>Start Date</b></label>
                                                <input name="date" class="form-control"
                                                       id="datepicker-date" placeholder="MM/DD/YYYY"
                                                       type="text" autocomplete="off" required>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="discount"><b>End Date</b></label>
                                                <input name="date_end" class="form-control"
                                                       id="datepicker-date1" placeholder="MM/DD/YYYY"
                                                       type="text" autocomplete="off" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="sessions">Session</label>
                                                    <select name="session" class="select2 form-control" id="session_id" required>
                                                        <option value="" disabled selected>Select Session</option>
                                                        @foreach($sessions as $session)

                                                            <option
                                                                value="{{$session->id}}">{{$session->title}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="sessions">Payment Source</label>
                                                    <select name="payment_source" class="select2 form-control"
                                                            id="payment_source_id" required>
                                                        <option value="" disabled selected>Select Source</option>

                                                        <option value="cash">Cash</option>
                                                        <option value="bank">Bank</option>
                                                        <option value="jazzcash">JazzCash</option>
                                                        <option value="easypaisa">Easy Paisa</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="payment_type">Payment Type</label>
                                                    <select name="payment_type" class="select2 form-control"
                                                            id="payment_type_id" required>
                                                        <option value="" disabled selected>Select Type</option>

                                                        <option value="advance">Advance</option>
                                                        <option value="installment">Instalment</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mt-4">
                                                <button type="submit" class="btn btn-sm btn-primary"
                                                >Apply Filter
                                                </button>
                                                <button type="button" id="reset" class="mr-4 btn-sm btn btn-primary"
                                                >Reset
                                                </button>
                                            </div>
                                            @csrf

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row w-100 text-center">

            <div class="col-12">
                <div class="card basic-form">

                    <div class="card-body ">

                        <div class=" row table-responsive">

                            <table class="w-100 table border-top-0 table-bordered  border-bottom "
                                   id="data_table">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Student Name</th>
                                    <th>Sessions</th>
                                    <th>Instalment Amount</th>
                                    <th>Advance</th>
                                    <th>Instalment</th>
                                    <th>Payment Source</th>
                                    <th>Payment Type</th>
                                    <th>Cash</th>
                                    <th>Jazz Cash</th>
                                    <th>Bank</th>
                                    <th>EasyPaisa</th>
                                    <th>Paid Date</th>


                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot align="right">
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>

                                </tr>
                                </tfoot>
                                <tr class="bg-info">
                                    <th>No</th>
                                    <th>Student Name</th>
                                    <th>Sessions</th>
                                    <th>Instalment Amount</th>
                                    <th>Advance</th>
                                    <th>Instalment</th>
                                    <th>Payment Source</th>
                                    <th>Payment Type</th>
                                    <th>Cash</th>
                                    <th>Jazz Cash</th>
                                    <th>Bank</th>
                                    <th>EasyPaisa</th>
                                    <th>Paid Date</th>


                                </tr>
                            </table>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('css')
    <style>
        :root {
            --color: #009c8e;
            --boxSize: 8px;
            --gutter: 8px;
        }

        .loader {
            width: calc((var(--boxSize) + var(--gutter)) * 5);
            height: 64px;
            margin: 50px auto;
            position: relative;
        }

        .loader .box {
            background: var(--color);
            width: var(--boxSize);
            height: 100%;
            margin: 0 2px;
            border-radius: 8px;
            box-shadow: 0px 0px 5px 0px var(--color);
            display: inline-block;
            transform: scaleY(.4);
            animation: quiet 1.2s ease-in-out infinite;
        }

        .loader .box:nth-child(2) {
            animation: animate 1.2s ease-in-out infinite;
        }

        .loader .box:nth-child(4) {
            animation: loud 1.2s ease-in-out infinite;
        }

        @keyframes quiet {
            25% {
                transform: scaleY(.6);
            }
            50% {
                transform: scaleY(.4);
            }
            75% {
                transform: scaleY(.8);
            }
        }

        @keyframes animate {
            25% {
                transform: scaleY(1);
            }
            50% {
                transform: scaleY(.4);
            }
            75% {
                transform: scaleY(.6);
            }
        }

        @keyframes loud {
            25% {
                transform: scaleY(1);
            }
            50% {
                transform: scaleY(.4);
            }
            75% {
                transform: scaleY(1.2);
            }
        }
    </style>
@endsection
@section('js')



    <script type="text/javascript">

        var tableData;
        $('#datepicker-date').bootstrapdatepicker({
            format: "yyyy-mm-dd",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });
        $('#datepicker-date1').bootstrapdatepicker({
            format: "yyyy-mm-dd",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });

        // filterform
        $(document).on("submit", "#filterform", function (event) {
            var formData = new FormData(this);
            event.preventDefault();


            $('#data_table').DataTable().destroy();
            {{--tableData = $('#data_table').DataTable({--}}
            {{--    "footerCallback": function (row, data, start, end, display) {--}}
            {{--        var api = this.api(), data;--}}
            {{--        console.log(api)--}}
            {{--        // converting to interger to find total--}}
            {{--        var intVal = function (i) {--}}
            {{--            return typeof i === 'string' ?--}}
            {{--                i.replace(/[\$,]/g, '') * 1 :--}}
            {{--                typeof i === 'number' ?--}}
            {{--                    i : 0;--}}
            {{--        };--}}

            {{--        // computing column Total of the complete result--}}
            {{--        var monTotal = api--}}
            {{--            .column(1)--}}
            {{--            .data()--}}
            {{--            .reduce(function (a, b) {--}}
            {{--                return intVal(a) + intVal(b);--}}
            {{--            }, 0);--}}

            {{--        var tueTotal = api--}}
            {{--            .column(2)--}}
            {{--            .data()--}}
            {{--            .reduce(function (a, b) {--}}
            {{--                return intVal(a) + intVal(b);--}}
            {{--            }, 0);--}}

            {{--        var wedTotal = api--}}
            {{--            .column(3)--}}
            {{--            .data()--}}
            {{--            .reduce(function (a, b) {--}}
            {{--                return intVal(a) + intVal(b);--}}
            {{--            }, 0);--}}

            {{--        var thuTotal = api--}}
            {{--            .column(4)--}}
            {{--            .data()--}}
            {{--            .reduce(function (a, b) {--}}
            {{--                return intVal(a) + intVal(b);--}}
            {{--            }, 0);--}}

            {{--        var friTotal = api--}}
            {{--            .column(5)--}}
            {{--            .data()--}}
            {{--            .reduce(function (a, b) {--}}
            {{--                return intVal(a) + intVal(b);--}}
            {{--            }, 0);--}}
            {{--        var sixTotal = api--}}
            {{--            .column(6)--}}
            {{--            .data()--}}
            {{--            .reduce(function (a, b) {--}}
            {{--                return intVal(a) + intVal(b);--}}
            {{--            }, 0);--}}
            {{--        var sevnTotal = api--}}
            {{--            .column(7)--}}
            {{--            .data()--}}
            {{--            .reduce(function (a, b) {--}}
            {{--                return intVal(a) + intVal(b);--}}
            {{--            }, 0);--}}
            {{--        var eightTotal = api--}}
            {{--            .column(8)--}}
            {{--            .data()--}}
            {{--            .reduce(function (a, b) {--}}
            {{--                return intVal(a) + intVal(b);--}}
            {{--            }, 0);--}}

            {{--        // Update footer by showing the total with the reference of the column index--}}
            {{--        $(api.column(0).footer()).html('Total');--}}
            {{--        $(api.column(1).footer()).html(monTotal);--}}
            {{--        $(api.column(2).footer()).html(tueTotal);--}}
            {{--        $(api.column(3).footer()).html(wedTotal);--}}
            {{--        $(api.column(4).footer()).html(thuTotal);--}}
            {{--        $(api.column(5).footer()).html(friTotal);--}}
            {{--        $(api.column(7).footer()).html(sevnTotal);--}}
            {{--        $(api.column(8).footer()).html(eightTotal);--}}
            {{--    },--}}
            {{--    "serverSide": true,--}}
            {{--    "pageLength": 100,--}}
            {{--    dom: 'Bfrtip',--}}
            {{--    buttons: [--}}
            {{--        {--}}
            {{--            extend: 'excel',--}}
            {{--            footer: true,--}}
            {{--            exportOptions: {--}}
            {{--                columns: ':visible'--}}
            {{--            }--}}
            {{--        },--}}
            {{--        {--}}
            {{--            extend: 'pdf',--}}
            {{--            footer: true,--}}
            {{--            exportOptions: {--}}
            {{--                columns: ':visible'--}}
            {{--            }--}}
            {{--        },--}}
            {{--        {--}}
            {{--            extend: 'print',--}}
            {{--            footer: true,--}}
            {{--            exportOptions: {--}}
            {{--                columns: ':visible'--}}
            {{--            }--}}
            {{--        },--}}
            {{--        'colvis'--}}
            {{--    ],--}}
            {{--    "columnDefs": [--}}
            {{--        {"visible": false}--}}
            {{--    ],--}}

            {{--    ajax: {--}}
            {{--        "url": "{{ route('datatable.get_data_fee_collection') }}",--}}
            {{--        "type": "get",--}}
            {{--        'data': {--}}
            {{--            session: $('#session_id').val(),--}}
            {{--            student_id: $('#student_id').val(),--}}
            {{--            date: $('#datepicker-date').val(),--}}
            {{--            date_end: $('#datepicker-date1').val(),--}}
            {{--            payment_source: $('#payment_source_id').val(),--}}
            {{--            payment_type: $('#payment_type_id').val(),--}}
            {{--        },--}}

            {{--    },--}}
            {{--    "columns": [--}}
            {{--        {data: 'id', name: 'id'},--}}
            {{--        {data: 'student_name', name: 'student_name'},--}}

            {{--        {data: 'sessions', name: 'sessions'},--}}

            {{--        {data: 'installement_amount', name: 'installement_amount'},--}}
            {{--        {data: 'advance', name: 'advance'},--}}
            {{--        {data: 'instalment', name: 'instalment'},--}}
            {{--        {data: 'source', name: 'source'},--}}
            {{--        {data: 'type', name: 'type'},--}}
            {{--        {data: 'Cash', name: 'Cash'},--}}
            {{--        {data: 'jazz_cash', name: 'jazz_cash'},--}}
            {{--        {data: 'bank', name: 'bank'},--}}
            {{--        {data: 'paid_date', name: 'paid_date'},--}}

            {{--        // <th>Cash</th>--}}
            {{--        // <th>Jazz Cash</th>--}}
            {{--        // <th>Bank</th>--}}
            {{--    ]--}}

            {{--});--}}
                tableData = $('#data_table').DataTable({
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api(), data;
                    console.log(api)
                    // converting to interger to find total
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // computing column Total of the complete result
                    var monTotal = api
                        .column(1)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var tueTotal = api
                        .column(2)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var wedTotal = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var thuTotal = api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var friTotal = api
                        .column(5)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var sixTotal = api
                        .column(6)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var sevnTotal = api
                        .column(7)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var eightTotal = api
                        .column(8)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var nineTotal = api
                        .column(9)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var tenTotal = api
                        .column(10)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var elevenTotal = api
                        .column(11)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer by showing the total with the reference of the column index
                    $(api.column(0).footer()).html('Total');
                    $(api.column(1).footer()).html(monTotal);
                    $(api.column(2).footer()).html(tueTotal);
                    $(api.column(3).footer()).html(wedTotal);
                    $(api.column(4).footer()).html(thuTotal);
                    $(api.column(5).footer()).html(friTotal);
                    $(api.column(7).footer()).html(sevnTotal);
                    $(api.column(8).footer()).html(eightTotal);
                    $(api.column(9).footer()).html(nineTotal);
                    $(api.column(10).footer()).html(tenTotal);
                    $(api.column(11).footer()).html(elevenTotal);
                },
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                dom: 'Bfrtip',

                buttons: [
                    {
                        extend: 'excel',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis'
                ],
                "columnDefs": [
                    {"visible": false}
                ],
                ajax: {
                    "url": "{{ route('datatable.get_data_fee_collection') }}",
                    "type": "get",
                    'data': {
                        session: $('#session_id').val(),
                        student_id: $('#student_id').val(),
                        date: $('#datepicker-date').val(),
                        date_end: $('#datepicker-date1').val(),
                        payment_source: $('#payment_source_id').val(),
                        payment_type: $('#payment_type_id').val(),
                    },
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'student_name', name: 'student_name'},
                    {data: 'sessions', name: 'sessions'},
                    {data: 'installement_amount', name: 'installement_amount'},
                    {data: 'advance', name: 'advance'},
                    {data: 'instalment', name: 'instalment'},
                    {data: 'source', name: 'source'},
                    {data: 'type', name: 'type'},
                    {data: 'cash', name: 'cash'},
                    {data: 'jazz_cash', name: 'jazz_cash'},
                    {data: 'bank', name: 'bank'},
                    {data: 'easypaisa', name: 'easypaisa'},

                    {data: 'paid_date', name: 'paid_date'},


                ],

            });

        });
        $("#apply_filter").click(function () {
            $('#filter').toggle();

        });

        $("#reset").click(function () {

            $('#data_table').DataTable().destroy();

            $('#datepicker-date').val('').trigger("change");
            $('#datepicker-date1').val('');
            $('#student_id').val(null).trigger("change");
            $('#session_id').val(null).trigger("change");
            $('#payment_source_id').val('').trigger("change");
            $('#payment_type_id').val('').trigger("change");


            tableData = $('#data_table').DataTable({
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api(), data;
                    console.log(api)
                    // converting to interger to find total
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // computing column Total of the complete result
                    var monTotal = api
                        .column(1)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var tueTotal = api
                        .column(2)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var wedTotal = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var thuTotal = api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var friTotal = api
                        .column(5)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var sixTotal = api
                        .column(6)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var sevnTotal = api
                        .column(7)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var eightTotal = api
                        .column(8)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var nineTotal = api
                        .column(9)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var tenTotal = api
                        .column(10)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var elevenTotal = api
                        .column(11)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer by showing the total with the reference of the column index
                    $(api.column(0).footer()).html('Total');
                    $(api.column(1).footer()).html(monTotal);
                    $(api.column(2).footer()).html(tueTotal);
                    $(api.column(3).footer()).html(wedTotal);
                    $(api.column(4).footer()).html(thuTotal);
                    $(api.column(5).footer()).html(friTotal);
                    $(api.column(7).footer()).html(sevnTotal);
                    $(api.column(8).footer()).html(eightTotal);
                    $(api.column(9).footer()).html(nineTotal);
                    $(api.column(10).footer()).html(tenTotal);
                    $(api.column(11).footer()).html(elevenTotal);

                },
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                dom: 'Bfrtip',

                buttons: [
                    {
                        extend: 'excel',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis'
                ],
                "columnDefs": [
                    {"visible": false}
                ],
                ajax: {
                    "url": "{{ route('datatable.get_data_fee_collection') }}",
                    "type": "get",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'student_name', name: 'student_name'},
                    {data: 'sessions', name: 'sessions'},
                    {data: 'installement_amount', name: 'installement_amount'},
                    {data: 'advance', name: 'advance'},
                    {data: 'instalment', name: 'instalment'},
                    {data: 'source', name: 'source'},
                    {data: 'type', name: 'type'},
                    {data: 'cash', name: 'cash'},
                    {data: 'jazz_cash', name: 'jazz_cash'},
                    {data: 'bank', name: 'bank'},
                    {data: 'easypaisa', name: 'easypaisa'},

                    {data: 'paid_date', name: 'paid_date'},


                ],

            });

        });

        $(document).ready(function () {

            tableData = $('#data_table').DataTable({
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api(), data;
                    console.log(api)
                    // converting to interger to find total
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // computing column Total of the complete result
                    var monTotal = api
                        .column(1)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var tueTotal = api
                        .column(2)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var wedTotal = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var thuTotal = api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var friTotal = api
                        .column(5)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var sixTotal = api
                        .column(6)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var sevnTotal = api
                        .column(7)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var eightTotal = api
                        .column(8)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var nineTotal = api
                        .column(9)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var tenTotal = api
                        .column(10)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var elevenTotal = api
                        .column(11)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer by showing the total with the reference of the column index
                    $(api.column(0).footer()).html('Total');
                    $(api.column(1).footer()).html(monTotal);
                    $(api.column(2).footer()).html(tueTotal);
                    $(api.column(3).footer()).html(wedTotal);
                    $(api.column(4).footer()).html(thuTotal);
                    $(api.column(5).footer()).html(friTotal);
                    $(api.column(7).footer()).html(sevnTotal);
                    $(api.column(8).footer()).html(eightTotal);
                    $(api.column(9).footer()).html(nineTotal);
                    $(api.column(10).footer()).html(tenTotal);
                    $(api.column(11).footer()).html(elevenTotal);

                },
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                dom: 'Bfrtip',

                buttons: [
                    {
                        extend: 'excel',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis'
                ],
                "columnDefs": [
                    {"visible": false}
                ],
                ajax: {
                    "url": "{{ route('datatable.get_data_fee_collection') }}",
                    "type": "get",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'student_name', name: 'student_name'},
                    {data: 'sessions', name: 'sessions'},
                    {data: 'installement_amount', name: 'installement_amount'},
                    {data: 'advance', name: 'advance'},
                    {data: 'instalment', name: 'instalment'},
                    {data: 'source', name: 'source'},
                    {data: 'type', name: 'type'},
                    {data: 'cash', name: 'cash'},
                    {data: 'jazz_cash', name: 'jazz_cash'},
                    {data: 'bank', name: 'bank'},
                    {data: 'easypaisa', name: 'easypaisa'},
                    {data: 'paid_date', name: 'paid_date'},


                ],

            });


        });


    </script>
@endsection
