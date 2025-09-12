@extends('admin.layouts.main')

@section('title')
    Teacher
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Teachers </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            <div class="col-12 text-right">
                {{--                <a href="{!! route('hr.agent.create') !!}" class="btn btn-primary btn-sm ">Create Agent</a>--}}
                {{--                <a href="#" class="btn btn-primary btn-sm ">Create Agent</a>--}}
                @if (Gate::allows('students'))

                <a class="btn btn-primary btn-sm " id="create-form" data-toggle="modal" data-target="#createModal1">Create
                    Teacher </a>
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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Mobile No.</th>
                                <th>Salary</th>
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
                                <form action="{!! route('hr.teacher.store') !!}" enctype="multipart/form-data"
                                      id="form_validation" autocomplete="off" method="post">
                                    @csrf
                                    <div class="box-body" style="margin-top:50px;">
                                        <h5>Teacher Data</h5>
                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <label for="name">Teacher Name*</label>
                                                <input name="name" type="text" class="form-control" required/>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="email">Email*</label>
                                                <input name="email" type="email" class="form-control" required/>
                                            </div>
                                        </div>
                                        <div class="row mt-2">

                                            <div class="col-lg-6">
                                                <label for="address">Address*</label>
                                                <input name="address" type="text" class="form-control" required/>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="mobile">Mobile No.*</label>
                                                <input name="mobile" type="text" class="form-control" required/>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <label for="salary">Salary</label>
                                                <input name="salary" type="number" min="0" class="form-control"
                                                       required/>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="image" class="form-label">Image Upload</label>
                                                <input name="image" class="form-control" type="file" id="formFile">
                                            </div>
                                        </div>
                                        <div class=" row mt-5 mb-3">
                                            <div class="col-12">
                                                <div class="form-group text-right">
                                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                    <a href="{!! route('hr.teacher.index') !!}"
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
        <div class="modal modal1" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Teacher</h4>
                        <button type="button" id="close" class="close modalclose" data-dismiss="modal1">&times;</button>
                    </div>

                    <!-- Modal body  -->

                    <div class="modal-body">
                        <form id="editform">

                            @csrf

                            <div class="form-group">
                                <div class="input-label">
                                    <label>Name</label>
                                </div>
                                <input type="text" name="name" id="name_edit" class="form-control">
                            </div>
                            <div class="form-group">
                                <div class="input-label">
                                    <label>Email</label>
                                </div>
                                <input type="text" name="email" id="email_edit" class="form-control">
                            </div>

                            <div class="form-group">
                                <div class="input-label">
                                    <label>Address</label>
                                </div>
                                <input type="text" name="address" id="address_edit" class="form-control">
                            </div>
                            <div class="form-group">
                                <div class="input-label">
                                    <label>Mobile No</label>
                                </div>
                                <input type="text" name="mobile" id="mobile_edit" class="form-control">
                            </div>
                            <div class="form-group">
                                <div class="input-label">
                                    <label>Salary</label>
                                </div>
                                <input type="number" min="0" name="salary" id="salary_edit" class="form-control">
                            </div>
                            <input type="hidden" name="id" id="edit_id" class="form-control">

                    </div>

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
                            "url": "{{ route('datatable.get_data_teacher') }}",
                            "type": "POST",
                            "data": {_token: "{{csrf_token()}}"}
                        },
                        "columns": [
                            {data: 'id', name: 'id'},
                            {data: 'name', name: 'name'},
                            {data: 'email', name: 'email'},
                            {data: 'address', name: 'address'},
                            {data: 'mobile', name: 'mobile'},
                            {data: 'salary', name: 'salary'},
                            {data: 'status', name: 'Status'},
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
                        url: " {!! route('hr.teacher.store') !!}",
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
                    $('#myModal').modal('hide');
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
                $('#data-table tbody').on('click', '.teacher_edit', function () {
                    var id = $(this).data('teacher-edit').id;

                    var name = $(this).data('teacher-edit').name;
                    var email = $(this).data('teacher-edit').email;
                    var address = $(this).data('teacher-edit').address;
                    var mobile_no = $(this).data('teacher-edit').mobile;
                    var salary = $(this).data('teacher-edit').salary;

                    $('#myModal').modal('show');
                    $("#edit_id").val(id);
                    $("#name_edit").val(name);
                    $("#email_edit").val(email);
                    $("#address_edit").val(address);
                    $("#mobile_edit").val(mobile_no);
                    $("#salary_edit").val(salary);
                });

                //Edit Form Submit
                $('#tag-form-submit').on('click', function (e) {
                    e.preventDefault();
                    var id = $('#edit_id').val();
                    var url = "{{ route('hr.teacher.index') }}";
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
