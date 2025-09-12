@extends('admin.layouts.main')

@section('title')
    Students DataBank
@stop
<style>
    table.table-bordered.dataTable th:last-child, table.table-bordered.dataTable th:last-child, table.table-bordered.dataTable td:last-child, table.table-bordered.dataTable td:last-child {
        border-right-width: 1px;
    }
</style>

@section('content')

    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Student DataBank </h3>
        </div>
        <div class="row    mt-4 mb-4 ">

        </div>
        <div class="row    mt-4 mb-4 ">
@if (Gate::allows('students'))
                <div class="col-12 text-right">
                    <a href="{{route('admin.walk_in_student_view')}}" class="btn btn-primary btn-sm ">Create Data
                        Bank </a>
                </div>
            @endif
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
                                                <label for="form_type"><b>Form Type</b></label>
                                                <select name="form_type" class="  form-control"
                                                        id="form_type">
                                                    <option value="" selected>Select Form Type</option>
                                                    <option value="CRM">CRM</option>
                                                    <option value="Website">Website</option>

                                                </select>
                                            </div>

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

                                            <div class="col-lg-4">
                                                <label for="course"><b>Course</b></label>
                                                <select name="course" class="  form-control"
                                                        id="course">
                                                    <option value="" selected>Select Course</option>
{{--                                                    @foreach ($courses as $item)--}}
{{--                                                        <option value="{{$item->id}}">{{$item->name}}</option>--}}
{{--                                                    @endforeach--}}
                                                </select>
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
                                    <th>Email</th>
                                    <th>Mobile No.</th>
                                    <th>City</th>
                                    <th>Message</th>
                                    <th>Course</th>
                                    <th>Form Type</th>
                                    <th>Date/Time</th>
                                    <th>Action</th>
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

    <!-- Message Modal -->
    <div class="modal " id="message_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="message_input"></p>
                </div>
            </div>
        </div>
    </div>
    <!-- Message Modal -->

    <!-- Modal for create -->
    <div class="modal" id="courseModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Courses</h4>
                    <button type="button" id="close" class="close modalclose" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    <div class="form-group">
                        <form id="createform">
                            @csrf

                            <div class="row mt-3">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <h5><b>Course Name</b></h5>
                                            <p id="getData"></p>
                                        </div>
                                        <p></p>
                                    </div>
                                </div>

                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for create -->

    <!-- Remarks Modal -->
    <div class="modal fade" id="remarks_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true" style="margin-top: 70px;">
        <div class="modal-dialog" role="document">
            <div class="modal-content container">
                <div class="row mt-3">
                    <div class="alert col-md-8 alert-success alert-dismissible fade " id="successMsg" role="alert"
                         style="margin-left: 80px;">
                        <strong>Remarks Added Successfully!</strong>

                    </div>
                    {{--                    <div class="alert  alert-success col-md-12" role="alert"  style="display: none">--}}

                </div>

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Write Remarks</h5>
                    <button id="close_btn" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="remarks_modal_form">
                    <div class="modal-body">

                        @CSRF

                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Student Name:</label>
                            <input type="text" class="form-control" id="student_name" name="name" value="">
                            <input type="hidden" class="form-control" id="id" name="id" value="">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Remarks:</label>
                            <textarea class="form-control" id="remarks" name="remarks"></textarea>
                            <span class="text-danger" id="remarksErrorMsg"></span>
                        </div>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Remarks Modal -->



@endsection

@section('css')





@endsection
@section('js')
    <script>
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
    </script>
    <script type="text/javascript">


        var tableData = null;
        $(document).ready(function () {
            tableData = $('#data_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                order: [0, 'desc' ],
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
                    "url": "{{ route('datatable.get_data_bank_student') }}",
                    "type": "get",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    {data: 'city', name: 'city'},
                    {data: 'message', name: 'message'},
                    {data: 'courses', name: 'courses'},

                    {data: 'form_type', name: 'form_type'},

                    {data: 'created_at', name: 'created_at'},
                    // {data: 'remarks', name: 'remarks'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},

                ]
            });
        });
    </script>
    <script>
        var tableData = null;
        $('#data_table tbody').on('click', '.message_view', function () {

            var data = $(this).data('message');
            $('#message_modal').modal('show');
            $('#message_input').html(data);


        });
        $(".close").click(function () {

            $('#message_modal').modal('hide');
        });
    </script>


    <script>
        // filterform
        $(document).on("submit", "#filterform", function (event) {
            var formData = new FormData(this);
            event.preventDefault();

            $('#data_table').DataTable().destroy();
            tableData = $('#data_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                order: [0, 'desc' ],
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
                    "url": "{{ route('datatable.get_data_bank_student') }}",
                    "type": "get",
                    'data': {
                        form_type: $('#form_type').val(),
                        date: $('#datepicker-date').val(),
                        date_end: $('#datepicker-date1').val(),
                        course: $('#course').val(),
                    },

                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    // {data: 'view_courses', name: 'view_courses'},
                    {data: 'city', name: 'city'},
                    {data: 'message', name: 'message'},
                    {data: 'courses', name: 'courses'},

                    {data: 'form_type', name: 'form_type'},

                    {data: 'created_at', name: 'created_at'},
                    // {data: 'remarks', name: 'remarks'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},

                ]

            });


        });
        $("#apply_filter").click(function () {
            $('#filter').toggle();

        });
        $("#reset").click(function () {

            $('#data_table').DataTable().destroy();
            $('#form_type').val(null);
            $('#datepicker-date').val('');
            $('#datepicker-date1').val('');
            $('#course').val(null);


            tableData = $('#data_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                order: [0, 'desc' ],
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
                    "url": "{{ route('datatable.get_data_bank_student') }}",
                    "type": "get",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    // {data: 'view_courses', name: 'view_courses'},
                    {data: 'city', name: 'city'},
                    {data: 'message', name: 'message'},
                    {data: 'courses', name: 'courses'},

                    {data: 'form_type', name: 'form_type'},

                    {data: 'created_at', name: 'created_at'},
                    // {data: 'remarks', name: 'remarks'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},

                ]
            });
        });
    </script>

    <script>


        $('#tag-form-submit').on('click', function (e) {
            e.preventDefault();
            var id = $('#edit_id').val();
            var url = "{{ route('admin.coursetype.index') }}";
            $.ajax({
                type: "put",
                "url": url + '/' + id,
                data: $('#editform').serialize(),
                success: function (response) {
                    // alert(response['response']);
                    $('#myModal').modal('hide');


                    tableData.ajax.reload();
                },
                error: function () {
                    alert('Error');
                }
            });
            return false;
        });

        $('#data_table tbody').on('click', '.view_course', function () {


            var id = $(this).data('id');
            var url = $(this).data('route');

            $.ajax({

                type: "POST",
                "url": url,
                data: {
                    "_token": "{{ csrf_token() }}",

                },
                success: function (response) {
                    $("#getData").html(response);
                    $('#courseModal').modal('show');
                },
                error: function () {
                    alert('Error');
                }
            });
            return false;


        });


        $('#data_table tbody').on('click', '.remarks_write', function () {

            var name = $(this).data('name');
            var id = $(this).data('id');
            // let remarks = $(this).data('remarks');


            $('#student_name').val(name);
            $('#id').val(id);
            $('#remarks').text($(this).data('remarks'));
            $('#remarks').val($(this).data('remarks'));
            $('#remarks_modal').modal('show');


        });
        $(".close").click(function () {

            $('#remarks_modal').modal('hide');
        });
    </script>

    <script>

        $('#remarks_modal_form').on('submit', function (e) {
            e.preventDefault();
            let id = $('#id').val();
            let remarks = $('#remarks').val();

            $.ajax({
                url: "{!! route('student.databank.remarks') !!}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    remarks: remarks,
                    id: id,
                },

                success: function (response) {
                    $('#successMsg').show();
                    $('#successMsg').addClass('show');
                    setTimeout(function () {
                        $("#close_btn")
                            .trigger("click")
                        $('#remarks').html(' ');
                        $('#remarks').val('');
                        $('#successMsg').removeClass('show');
                        tableData.ajax.reload();
                    }, 2000);


                },

                error: function (response) {
                    $('#remarksErrorMsg').text(response.responseJSON.errors.remarks);

                },
            });
        });

    </script>

    <script>
        $('#data_table tbody').on('change', '.status', function () {

            var id = $(this).data('id');
            var status = $('#status_' + id).val();

            $.ajax({
                url: "{!! route('student.databank.status') !!}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                    status: status
                }
            });
        });

        $(".modalclose").click(function () {
            $('#courseModal').modal('hide');
        });
    </script>










    //  deleteing walking user
    <script>
        $('#data_table tbody').on('click', '.delete', function () {
            var route = $(this).data('route');
            if (confirm('Are you sure ,you want to delete walk in student?')) {
                $.ajax({
                    url: route,
                    type: "delete",
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (result) {
                        tableData.ajax.reload();
                    }
                });
            }
        });
    </script>


@endsection

