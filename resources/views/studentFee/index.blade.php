@extends('admin.layouts.main')

@section('title')
    Students Fee
@stop

@section('css')
    <style>

        .bg-dark {
            background-color: #6c6c6c !important;
        }
        .bg-info {
            background-color: #366fff !important;
        }
    </style>

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Students Fee </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            <div class="col-12 text-right">
                <a href="{!! route('admin.student_fee.create') !!}" class="btn btn-primary btn-sm ">Create Student
                    Fee</a>
            </div>
        </div>
        {{--        @dd($student_fee)--}}

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
                                            <div class="col-lg-3">
                                                <label for="course"><b>Course</b></label>
                                                <select name="course" class="  form-control"
                                                        id="course">
                                                    <option value="" selected>Select Course</option>
                                                    @foreach ($courses as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 mb-5">
                                                <label for="session"><b>Session</b></label>
                                                <select data-column="0" name="session" class="  form-control"
                                                        id="session">
                                                    <option value="" selected>Select Option</option>

                                                </select>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="student_fee"><b>Remaining</b></label>
                                                <select name="remaining" class="  form-control"
                                                        id="remaining">
                                                    <option value="" selected>Select Remaining</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                    <option value="30">Greater than 30K</option>
                                                </select>
                                            </div>


                                            <div class="col-lg-3">
                                                <label for="discount"><b>Discount</b></label>
                                                <select name="discount" class="  form-control"
                                                        id="discount">
                                                    <option value="" selected>Select Discount</option>
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <label for="discount"><b>Start Date</b></label>
                                                <input name="date" class="form-control"
                                                       id="datepicker-date" placeholder="MM/DD/YYYY"
                                                       type="text" autocomplete="off">
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="discount"><b>End Date</b></label>
                                                <input name="date_end" class="form-control"
                                                       id="datepicker-date1" placeholder="MM/DD/YYYY"
                                                       type="text" autocomplete="off">
                                            </div>

                                            <div class="col-lg-3">
                                                <label for="tools"><b>Defaulter</b></label>
                                                <select name="defaulter" class="  form-control"
                                                        id="defaulter">
                                                    <option value="" selected>Select Option</option>
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>

                                                </select>
                                            </div>


                                                <div class="col-lg-3">
                                                    <label for="discount"><b>Certificate Provided</b></label>
                                                    <select name="certificates" class="  form-control"
                                                            id="certificates">
                                                        <option value="" selected>Select Option</option>
                                                        <option value="yes">Yes</option>
                                                        <option value="no">No</option>

                                                    </select>
                                                </div>
                                                </div>


                                            <div class="row mt-3">
                                                <div class="col-lg-3">
                                                    <label for="discount"><b>Tool Date From</b></label>
                                                    <input name="tools_date" class="form-control"
                                                           id="datepicker-date-tools" placeholder="MM/DD/YYYY"
                                                           type="text" autocomplete="off" multiple>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="discount"><b>Tool Date To</b></label>
                                                    <input name="tools_date_to" class="form-control"
                                                           id="datepicker-date-tools_to" placeholder="MM/DD/YYYY"
                                                           type="text" autocomplete="off" multiple>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="tools"><b>Tools Provided</b></label>
                                                    <select name="tools" class="  form-control"
                                                            id="tools">
                                                        <option value="" selected>Select Option</option>
                                                        <option value="yes">Yes</option>
                                                        <option value="no">No</option>

                                                    </select>
                                                </div>
                                            </div>



                                        </div>
                                            <div class="col-lg-6" style="    margin: 34px 0px 0px -138px;">
                                                <button type="submit" class="btn btn-sm btn-primary"
                                                >Apply Filter
                                                </button>
                                                <button type="button" id="reset" class="mr-4 btn-sm btn btn-primary"
                                                >Reset
                                                </button>
                                            </div>
                                            <div class="col-lg-3 mt-3">

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
                <div class="card basic-form table-responsive">
                    <div class="card-body">


                        <table class="table border-top-0 table-bordered   border-bottom" id="data_table">

                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Session</th>
                                <th>Student Name</th>
                                <th>Student Status</th>
                                <th>Course Name</th>
                                <th>Student Fee</th>
                                <th>Course Fee</th>
                                <th>Discount Amount</th>
                                <th>Total Fee Paid</th>
                                <th>Remaining Amount</th>
                                <th>Tools Provided</th>
                                <th>Tools Provided Date</th>
                                <th>Certificate Provided</th>
                                <th>Date</th>
                                <th>Defaulter</th>
                                <th width="100px">Action</th>
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
                                <th></th>
                                <th></th>
                                <th></th>

                            </tr>
                            </tfoot>

                            <tr >
                                <th> </th>
                                <th> </th>
                                <th> </th>
                                <th> </th>
                                <th> </th>
                                <th> </th>
                                <th> </th>
                                <th> </th>
                                <th> </th>
                                <th> </th>
                                <th> </th>
                                <th></th>
                                <th> </th>
                                <th></th>
                                <th></th>
                                <th width="100px"></th>
                            </tr>
                            <tr class="bg-info">
                                <th> </th>
                                <th>Session</th>
                                <th>Student Name</th>
                                <th>Student Status</th>
                                <th>Course Name</th>
                                <th>Student Fee</th>
                                <th>Course Fee</th>
                                <th>Discount Amount</th>
                                <th>Total Fee Paid</th>
                                <th>Remaining Amount</th>
                                <th>Tools Provided</th>
                                <th>Tools Provided Date</th>
                                <th>Certificate Provided</th>
                                <th>Date</th>
                                <th>Defaulter</th>
                                <th width="100px">Action</th>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('css')
    <style>
        .bg-danger {
            background-color: #c93658 !important;
        }
    </style>
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
        $('#datepicker-date-tools').bootstrapdatepicker({
            format: "yyyy-mm-dd",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        }); $('#datepicker-date-tools_to').bootstrapdatepicker({
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
                    var tweleTotal = api
                        .column(12)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var thirtyeenTotal = api
                        .column(13)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var fourteenTotal = api
                        .column(14)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var fifteenTotal = api
                        .column(15)
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
                    $(api.column(12).footer()).html(tweleTotal);
                    $(api.column(13).footer()).html(thirtyeenTotal);
                    $(api.column(14).footer()).html(fourteenTotal);
                    $(api.column(15).footer()).html(fifteenTotal);

                },
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel', footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf', footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print', footer: true,
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
                    "url": "{{ route('datatable.get_data_student_fee') }}",
                    "type": "get",
                    'data': {
                        session: $('#session').val(),
                        course: $('#course').val(),
                        discount: $('#discount').val(),
                        date: $('#datepicker-date').val(),
                        date_end: $('#datepicker-date1').val(),
                        remaining: $('#remaining').val(),
                        defaulter: $('#defaulter').val(),
                        tools: $('#tools').val(),
                        tools_date: $('#datepicker-date-tools').val(),
                        tools_date_to: $('#datepicker-date-tools_to').val(),
                        certificates: $('#certificates').val(),
                    },

                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'session', name: 'session'},
                    {data: 'student_name', name: 'student_name'},
                    {data: 'student_status', name: 'student_status'},
                    {data: 'course_name', name: 'course_name'},
                    {data: 'student_fee', name: 'student_fee'},
                    {data: 'course_fee', name: 'course_fee'},
                    {data: 'discount_amount', name: 'discount_amount'},
                    {data: 'total_paid_fee', name: 'total_paid_fee'},
                    {data: 'remaining_amount', name: 'remaining_amount'},
                    {data: 'tools_provided', name: 'tools_provided'},
                    {data: 'tools_date', name: 'tools_date'},
                    {data: 'certificate', name: 'certificate'},
                    {data: 'date', name: 'date'},
                    {data: 'defaulter', name: 'defaulter'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]

            });


        });

        $("#apply_filter").click(function () {
            $('#filter').toggle();

        });


        $("#reset").click(function () {
            $('#data_table').DataTable().destroy();
            $('#course').val(null);
            $('#session').val(null);
            $('#discount').val(null);
            $('#datepicker-date').val('');
            $('#datepicker-date1').val('');
            $('#remaining').val('');
            $('#tools').val('');
            $('#datepicker-date-tools').val('');
            $('#datepicker-date-tools_to').val('');
            $('#certificates').val('');
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
                    var tweleTotal = api
                        .column(12)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var thirtyeenTotal = api
                        .column(13)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var fourteenTotal = api
                        .column(14)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var fifteenTotal = api
                        .column(15)
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
                    $(api.column(12).footer()).html(tweleTotal);
                    $(api.column(13).footer()).html(thirtyeenTotal);
                    $(api.column(14).footer()).html(fourteenTotal);
                    $(api.column(15).footer()).html(fifteenTotal);

                },

                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel', footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf', footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print', footer: true,
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
                    "url": "{{ route('datatable.get_data_student_fee') }}",
                    "type": "get",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'session', name: 'session'},
                    {data: 'student_name', name: 'student_name'},
                    {data: 'student_status', name: 'student_status'},
                    {data: 'course_name', name: 'course_name'},
                    {data: 'student_fee', name: 'student_fee'},
                    {data: 'course_fee', name: 'course_fee'},
                    {data: 'discount_amount', name: 'discount_amount'},
                    {data: 'total_paid_fee', name: 'total_paid_fee'},
                    {data: 'remaining_amount', name: 'remaining_amount'},
                    {data: 'tools_provided', name: 'tools_provided'},
                    {data: 'tools_date', name: 'tools_date'},
                    {data: 'certificate', name: 'certificate'},

                    {data: 'date', name: 'date'},
                    {data: 'defaulter', name: 'defaulter'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });

        $('#course').on('change', function () {
            var items = $(this).val();
            $.ajax({
                method: 'GET',
                url: "{{ route('admin.get_course_session') }}",
                data: {
                    "id": items,
                },
                success: function (response) {
                    $('#session').html(response);

                }
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

                    var eightTotal_new = api
                        .column(8)
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
                    var tweleTotal = api
                        .column(12)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var thirtyeenTotal = api
                        .column(13)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var fourteenTotal = api
                        .column(14)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var fifteenTotal = api
                        .column(15)
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
                    $(api.column(6).footer()).html(sixTotal);
                    $(api.column(7).footer()).html(sevnTotal);
                    $(api.column(8).footer()).html(eightTotal);
                    $(api.column(9).footer()).html(nineTotal);
                    $(api.column(10).footer()).html(tenTotal);
                    $(api.column(11).footer()).html(elevenTotal);
                    $(api.column(12).footer()).html(tweleTotal);
                    $(api.column(13).footer()).html(thirtyeenTotal);
                    $(api.column(14).footer()).html(fourteenTotal);
                    $(api.column(15).footer()).html(fifteenTotal);

                },
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel', footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf', footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print', footer: true,
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
                    "url": "{{ route('datatable.get_data_student_fee') }}",
                    "type": "get",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'session', name: 'session'},
                    {data: 'student_name', name: 'student_name'},
                    {data: 'student_status', name: 'student_status'},
                    {data: 'course_name', name: 'course_name'},
                    {data: 'student_fee', name: 'student_fee'},
                    {data: 'course_fee', name: 'course_fee'},
                    {data: 'discount_amount', name: 'discount_amount'},
                    {data: 'total_paid_fee', name: 'total_paid_fee'},
                    {data: 'remaining_amount', name: 'remaining_amount'},
                    {data: 'tools_provided', name: 'tools_provided'},
                    {data: 'tools_date', name: 'tools_date'},
                    {data: 'certificate', name: 'certificate'},

                    {data: 'date', name: 'date'},
                    {data: 'defaulter', name: 'defaulter'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]


            });


        });
    </script>
@endsection
