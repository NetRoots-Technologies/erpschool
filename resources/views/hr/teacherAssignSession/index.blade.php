@extends('admin.layouts.main')

@section('title')
  Assign Teacher Session
 @stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Assign Teacher Session </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            @if (Gate::allows('students'))

            <div class="col-12 text-right">
               <a class="btn btn-primary btn-sm " id="create-form" data-toggle="modal" data-target="#createModal1">Assign Teacher Session</a>
            </div>
            @endif
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="data-table">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Session</th>
                                <th>Teacher</th>
                                <th>Status</th>
                                <th width="200px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal-lg" id="createModal1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Create</h4>
                        <button type="button" id="close" class="close modalclose" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body" id="modal-body-create">
                        <div class="form-group">
                            <div class="container" id="modal-body-create">
                                <form action="{!! route('hr.teacher_assign_session.store') !!}" enctype="multipart/form-data"
                                      id="form_validation" autocomplete="off" method="post">
                                    @csrf
                                    <div class="box-body" style="margin-top:50px;">
                                        <h5>Teacher Assign Session</h5>
                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <label for="name">Teacher Name*</label>
                                                <select name="teacher_id" class="form-control"
                                                        id="teacher_id">
                                                    <option selected>Select Teacher</option>
                                                    @foreach($teachers as $teacher)
                                                        <option
                                                            value="{{$teacher->id}}">{{$teacher->name}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                            <div class="col-lg-6">
                                                <label for="email">Session*</label>
                                                <select name="session_id" class="form-control"
                                                        id="session_id">
                                                    <option selected>Select Session</option>
                                                    @foreach($sessions as $session)
                                                        <option
                                                            value="{{$session->id}}">{{$session->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class=" row mt-5 mb-3">
                                            <div class="col-12">
                                                <div class="form-group text-right">
                                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                    <a href="{!! route('hr.teacher_assign_session.index') !!}"
                                                       class=" btn btn-sm btn-danger">Cancel </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- The Modal for Edit -->
        <div class="modal modal1" id="myModal1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Teacher Assign Session</h4>
                        <button type="button" id="close" class="close modalclose" data-dismiss="modal1">&times;</button>
                    </div>

                    <!-- Modal body  -->

                    <div class="modal-body">
                        <form id="editform">

                            @csrf

                            <div class="form-group">
                                <div class="input-label">
                                    <label>Select Teacher</label>
                                </div>
                                <select name="teacher_id" class="form-control" id="teacher_name_edit">
                                    <option>Select Teacher</option>
                                    @foreach($teachers as $key => $teacher)
                                        <option value="{{$teacher->id}}"
                                                selected="selected">{{$teacher->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="input-label">
                                    <label>Select Session</label>
                                </div>
                                <select name="session_id" class="form-control" id="session_title_edit">
                                    <option>Select Session</option>
                                    @foreach($sessions as $key => $session)
                                        <option value="{{$session->id}}"
                                                selected="selected">{{$session->title}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <input type="hidden" name="id" id="edit_id" class="form-control">



                    <!-- Modal footer -->
                    <div class="modal-footer">

                        <input id="tag-form-submit" type="submit" class="btn btn-primary btn btn-sm" value="Submit">

                        <button type="button" class="btn btn-danger btn btn-sm modalclose" data-dismiss="modal1">Close
                        </button>
                    </div>
                    </form>

                </div>
            </div>
        </div>
        @stop
        @section('css')
            <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        @endsection
        @section('js')

            <script type="text/javascript">
                var tableData = null;
                $(document).ready(function () {
                    tableData = $('#data-table').DataTable({
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
                    { "visible": false }
                ],
                        ajax: {
                            "url": "{{ route('datatable.get_data_teacher_session') }}",
                            "type": "POST",
                            "data": {_token: "{{csrf_token()}}"}
                        },
                        "columns": [
                            {data: 'id', name: 'id'},
                            {data: 'sessions', name: 'sessions'},
                            {data: 'users', name: 'users'},
                            {data: 'status', name: 'status'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ]
                    });
                });


                $('#create-form').on('click', function (e) {


                    $('#createModal1').modal('show');


                });
                $(document).on("submit", "#form_validation", function (event) {

                    event.preventDefault();
                    var formData = new FormData(this);

                    $.ajax({
                        url: " {!! route('hr.teacher_assign_session.store') !!}",
                        type: 'POST',
                        data: formData,
                        success: function (data) {

                            $('#createModal1').modal('hide');
                            tableData.ajax.reload();
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });

                });

                $(".modalclose").click(function () {
                    $('#createModal1').modal('hide');
                    $('#myModal1').modal('hide');
                });

                //Entry Delete Query
                $('#data-table tbody').on('click', '.delete', function () {

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


                //Entry Edit Button Click
                $('#data-table tbody').on('click', '.teacher_assign_session_edit', function () {


                    var id = $(this).data('teacher_assign_session-edit').id;

                     var session_title = $(this).data('teacher_assign_session-edit').session_title;

                    var teacher_name = $(this).data('teacher_assign_session-edit').teacher_name;


                    $('#myModal1').modal('show');
                    $("#edit_id").val(id);
                    $("#session_title_edit").val(session_title);
                    $("#teacher_name_edit").val(teacher_name);

                });

                //Edit Form Submit
                $('#tag-form-submit').on('click', function (e) {
                    e.preventDefault();
                    var id = $('#edit_id').val();
                    var url = "{{ route('hr.teacher_assign_session.index') }}";
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

            </script>
@endsection
