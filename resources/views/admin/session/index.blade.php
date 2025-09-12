@extends('admin.layouts.main')

@section('title')
    Session
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Session </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            @if (Gate::allows('session-create'))
                <div class="col-12 text-right">
                    <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal1">Create Session</a>
                </div>
            @endif
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="file-datatable"
                                   class="border-top-0  table table-bordered text-nowrap key-buttons border-bottom">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Course</th>
                                    <th>Title</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal for create -->
                    <div class="modal" id="createModal1">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Create Session</h4>
                                    <button type="button" id="close" class="close modalclose" data-dismiss="modal">
                                        &times;
                                    </button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">

                                    <div class="form-group">
                                        <form id="createform">
                                            @csrf

                                            <div class="row mt-3">
                                                <div class="row mt-3">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <div class="input-label">
                                                                <label>Title</label>
                                                            </div>
                                                            <input type="text" required class="form-control" value=""
                                                                   id="title" name="title">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <div class="input-label">
                                                                <label>Select Course</label>
                                                            </div>
                                                            <select name="course_id" class="form-control"
                                                                    id="course_id">
                                                                <option selected>Select Courses</option>
                                                                @foreach($courses as $course)
                                                                    <option
                                                                        value="{{$course->id}}">{{$course->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <div class="input-label">
                                                                <label>Start Date</label>
                                                            </div>
                                                            <input type="date" required class="form-control"
                                                                   id="start_date" value=" " name="start_date">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <div class="input-label">
                                                                <label>End Date</label>
                                                            </div>
                                                            <input type="date" required class="form-control"
                                                                   id="end_date" value="" name="end_date">
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <div class="input-label">
                                                                <label> Class start Time</label>
                                                            </div>
                                                            <input type="time" required class="form-control"
                                                                   id="start_time" value="" name="start_time">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <div class="input-label">
                                                                <label> Class end Time</label>
                                                            </div>
                                                            <input type="time" required class="form-control"
                                                                   id="end_time" value="" name="end_time">
                                                        </div>
                                                    </div>

                                                </div>


                                            </div>


                                            <div class="row mt-5 mb-3">

                                                <div class="col-12">
                                                    <div class="form-group text-right">
                                                        <input id="create-form-submit" type="submit"
                                                               class="btn btn-primary"
                                                               value="Submit">
                                                        <a href="" class=" btn btn-sm btn-danger modalclose"
                                                           data-dismiss="modal">Cancel </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- The Modal for Edit -->
                    <div class="modal modal1" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Edit Session</h4>
                                    <button type="button" id="close" class="close modalclose" data-dismiss="modal1">
                                        &times;
                                    </button>
                                </div>

                                <!-- Modal body  -->

                                <div class="modal-body">
                                    <form id="editform">

                                        @csrf

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label>Title</label>
                                                    </div>
                                                    <input type="text" required class="form-control" id="title_edit"
                                                           value="" name="title">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label>Start Date</label>
                                                    </div>
                                                    <input type="text" required class="form-control"
                                                           id="start_date_edit" value="" name="start_date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label>End Date</label>
                                                    </div>
                                                    <input type="text" required class="form-control" id="end_date_edit"
                                                           value="" name="end_date">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label>Start Time</label>
                                                    </div>
                                                    <input type="time" required class="form-control"
                                                           id="start_time_edit"
                                                           value="" name="start_time">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label>End Time</label>
                                                    </div>
                                                    <input type="time" required class="form-control" id="end_time_edit"
                                                           value="" name="end_time">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label>Course</label>
                                                    </div>
                                                    <select name="course_id" class="form-control" id="course_id_edit">
                                                        <option>Select Courses Type</option>
                                                        @foreach($courses as $key => $course)
                                                            <option value="{{$course['id']}}"
                                                                    selected>{{$course['name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                        <input type="hidden" name="id" id="edit_id" class="form-control">

                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer">

                                    <input id="tag-form-submit" type="submit" class="btn btn-primary btn btn-sm"
                                           value="Submit">

                                    <button type="button" class="btn btn-danger btn btn-sm modalclose"
                                            data-dismiss="modal1">Close
                                    </button>
                                </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @stop
        @section('css')
{{--            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">--}}
{{--            <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>--}}
{{--            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>--}}
{{--            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>--}}
{{--            <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">--}}
{{--            <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">--}}
        @endsection
        @section('js')

            <script type="text/javascript">
                var tableData = null;
                $(document).ready(function () {
                    tableData = $('#file-datatable').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "pageLength": 100,
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
                            "url": "{{ route('datatable.get-data-session') }}",
                            "type": "POST",
                            "data": {_token: "{{csrf_token()}}"}
                        },
                        "columns": [
                            {data: 'id', name: 'id'},
                            {data: 'course_name', name: 'course_name'},
                            {data: 'title', name: 'title'},
                            {data: 'start_date', name: 'start_date'},
                            {data: 'end_date', name: 'end_date'},
                            {data: 'start_time', name: 'start_time'},
                            {data: 'end_time', name: 'end_time'},
                            {data: 'status', name: 'status'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ]
                    });
                });

                //Create Form Submit
                $('#create-form-submit').on('click', function (e) {

                    e.preventDefault();
                    var title = $('#title').val();
                    var start_date = $('#start_date').val();
                    var end_date = $('#end_date').val();


                    var url = "{{ route('admin.session.store') }}";
                    $.ajax({
                        type: "post",
                        "url": url,
                        data: $('#createform').serialize(),
                        success: function (response) {

                            $("#close").trigger("click");

                            var title = $('#title').val('');
                            var start_date = $('#start_date').val('');
                            var end_date = $('#end_date').val('');

                            tableData.ajax.reload();
                        },
                        error: function () {
                            alert('Error');
                        }
                    });
                    return false;
                });


                $('#file-datatable tbody').on('click', '.session_edit', function () {


                    var id = $(this).data('session-edit').id;
                    var title = $(this).data('session-edit').title;
                    var start_date = $(this).data('session-edit').start_date;
                    var end_date = $(this).data('session-edit').end_date;
                    var start_time = $(this).data('session-edit').start_time;
                    var end_time = $(this).data('session-edit').end_time;
                    var course_id = $(this).data('session-edit').course_id;


                    ``
                    $('#myModal').modal('show');

                    $("#edit_id").val(id);
                    $("#title_edit").val(title);
                    $("#start_date_edit").val(start_date);
                    $("#end_date_edit").val(end_date);
                    $("#start_time_edit").val(start_time);
                    $("#end_time_edit").val(end_time);
                    $("#course_id_edit").val(course_id);
                });

                $(".modalclose").click(function () {

                    $('#myModal').modal('hide');
                });

                $(".modalclose").click(function () {

                    $('#createModal1').modal('hide');
                });


                $('#tag-form-submit').on('click', function (e) {


                    e.preventDefault();
                    var id = $('#edit_id').val();
                    var url = "{{ route('admin.session.index') }}";
                    $.ajax({
                        type: "put",
                        "url": url + '/' + id,
                        data: $('#editform').serialize(),
                        success: function (response) {


                            $('#myModal').modal('hide');

                            tableData.ajax.reload();
                        },
                        error: function () {
                            alert('Error');
                        }
                    });
                    return false;
                });

                $('#file-datatable tbody').on('click', '.delete', function () {


                    var data = $(this).data('id');

                    $('#' + data).submit();

                });

                $(document).on("submit", ".delete_form", function (event) {
                    event.preventDefault();
                    var route = $(this).data('route');
                    var a = confirm('Are you sure you want to Delete this?');
                    if (a) {
                        $.ajax({
                            url: route,
                            type: 'DELETE',
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
