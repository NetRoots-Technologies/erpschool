@extends('admin.layouts.main')

@section('title')
    OnezCamp
@stop
<style>
    table.table-bordered.dataTable th:last-child, table.table-bordered.dataTable th:last-child, table.table-bordered.dataTable td:last-child, table.table-bordered.dataTable td:last-child {
        border-right-width: 1px;
    }
</style>

@section('content')

    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> OnezCamp </h3>
            {{--            <h3>   {{ request()->session()->get('message') }}</h3>--}}
        </div>
        <div class="row    mt-4 mb-4 ">

        </div>
        <div class="row    mt-4 mb-4 ">
            {{--            @if (Gate::allows('walk_in_student-create'))--}}
            {{--                <div class="col-12 text-right">--}}
            {{--                    <a href="{{route('admin.walk_in_student_view')}}" class="btn btn-primary btn-sm ">Create Data--}}
            {{--                        Bank </a>--}}
            {{--                </div>--}}
            {{--            @endif--}}
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
                                            <div class="col-lg-4">
                                                <label for="discount"><b>Start Date</b></label>
                                                <input name="date" class="form-control"
                                                       id="datepicker-date" placeholder="MM/DD/YYYY"
                                                       type="text" autocomplete="off">
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="discount"><b>End Date</b></label>
                                                <input name="date_end" class="form-control"
                                                       id="datepicker-date1" placeholder="MM/DD/YYYY"
                                                       type="text" autocomplete="off">
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
                                        <div class="row">

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
            <div class="col-12 ">
                <div class="card basic-form">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table border-top-0 table-bordered border-bottom" id="data_table">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Father Name</th>
                                    <th>Email</th>
                                    <th>Mobile No.</th>
                                    <th>CNIC</th>
                                    <th>Facebook Profile</th>
                                    <th>Shift</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')





@endsection
@section('js')
    <script>
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

        var tableData = null;
        $(document).ready(function () {
            tableData = $('#data_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                order: [0, 'desc'],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
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
                    "url": "{{ route('datatable.onezcamp_form_get_data') }}",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'father_name', name: 'father_name'},
                    {data: 'email', name: 'email'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    {data: 'cnic', name: 'cnic'},
                    {data: 'facebook_link', name: 'facebook_link'},
                    {data: 'shift', name: 'shift'},
                    {data: 'message', name: 'message'},
                    {data: 'created_at', name: 'created_at'},

                ]
            });
        });


        // filterform
        $(document).on("submit", "#filterform", function (event) {
            var formData = new FormData(this);
            event.preventDefault();

            $('#data_table').DataTable().destroy();
            tableData = $('#data_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                order: [0, 'desc'],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
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
                    "url": "{{ route('datatable.onezcamp_form_get_data') }}",
                    "type": "POST",
                    'data': {
                        date: $('#datepicker-date').val(),
                        date_end: $('#datepicker-date1').val(),
                        _token: "{{csrf_token()}}"
                    },

                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'father_name', name: 'father_name'},
                    {data: 'email', name: 'email'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    {data: 'cnic', name: 'cnic'},
                    {data: 'facebook_link', name: 'facebook_link'},
                    {data: 'shift', name: 'shift'},
                    {data: 'message', name: 'message'},
                    {data: 'created_at', name: 'created_at'},

                ]
            });
        });
        $("#apply_filter").click(function () {
            $('#filter').toggle();

        });
        $("#reset").click(function () {

            $('#datepicker-date').val('');
            $('#datepicker-date1').val('');
            $('#data_table').DataTable().destroy();
            tableData = $('#data_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                order: [0, 'desc'],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
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
                    "url": "{{ route('datatable.onezcamp_form_get_data') }}",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'father_name', name: 'father_name'},
                    {data: 'email', name: 'email'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    {data: 'cnic', name: 'cnic'},
                    {data: 'facebook_link', name: 'facebook_link'},
                    {data: 'shift', name: 'shift'},
                    {data: 'message', name: 'message'},
                    {data: 'created_at', name: 'created_at'},


                ]
            });
        });
    </script>


@endsection

