@extends('admin.layouts.main')

@section('title')
  Assign Student Session
 @stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Assign Student Session </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            <div class="col-12 text-right">
                @if (Gate::allows('students'))

               <a class="btn btn-primary btn-sm " id="create-form" data-toggle="modal" data-target="#createModal1">Assign Teacher Session</a>
               @endif
            </div>
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
                                <th>Student</th>
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
                                <form action="{!! route('hr.student_assign_session.store') !!}" enctype="multipart/form-data"
                                      id="form_validation" autocomplete="off" method="post">
                                    @csrf
                                    <div class="box-body" style="margin-top:50px;">
                                        <h5>Student Assign Session</h5>
                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <label for="name">Student Name*</label>
                                                <select name="student_id" class="form-control"
                                                        id="student_id">
                                                    <option selected>Select Student</option>
                                                    @foreach($students as $student)
                                                        <option
                                                            value="{{$student->id}}">{{$student->name}}</option>
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
                                                    <a href="{!! route('hr.student_assign_session.index') !!}"
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
                            {data: 'session_title', name: 'session_title'},
                            {data: 'teacher_name', name: 'teacher_name'},
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
                        url: " {!! route('hr.student_assign_session.store') !!}",
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
                // $('#data-table tbody').on('click', '.delete', function () {
                //
                //     var data = $(this).data('id');
                //
                //     $('#' + data).submit();
                // });
                {{--$(document).on("submit", ".delete_form", function (event) {--}}

                {{--    event.preventDefault();--}}
                {{--    var route = $(this).data('route');--}}


                {{--    var a = confirm('Are you sure you want to Delete this?');--}}
                {{--    if (a) {--}}
                {{--        $.ajax({--}}
                {{--            url: route,--}}
                {{--            type: 'DELETE',--}}
                {{--            data: {--}}
                {{--                "_token": "{{ csrf_token() }}",--}}
                {{--            },--}}
                {{--            success: function (result) {--}}
                {{--                tableData.ajax.reload();--}}
                {{--            }--}}
                {{--        });--}}
                {{--    }--}}
                {{--});--}}



            </script>
@endsection
