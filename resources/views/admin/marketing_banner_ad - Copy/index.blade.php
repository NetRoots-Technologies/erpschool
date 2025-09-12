@extends('admin.layouts.main')

@section('title')
    Users
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Users </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            @if (Gate::allows('users-create'))
                <div class="col-12 text-right">
                    <a data-toggle="modal" data-target="#createModal1" class="btn btn-primary btn-sm ">Create Users</a>
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
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>verified</th>
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
        </div>
        {{--            <!-- Modal for create -->--}}
        <div class="modal" id="createModal1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Create</h4>
                        <button type="button" id="close" class="close modalclose" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">

                        <div class="form-group">
                            <form action="{!! route('users.store') !!}" enctype="multipart/form-data"
                                  id="form_validation" autocomplete="off" method="post">
                                @csrf


                                <div class="form-group">
                                    <div class="input-label">
                                        <label>Role</label>
                                    </div>
                                    <select required id="role" name="role_id[]" class="form-control">
                                        <option value="">Select Option</option>
                                        @foreach($roles as $item)
                                            <option value="{!! $item->id !!}">{!! $item->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Email</label>
                                            </div>
                                            <input type="email" required class="form-control" value="" name="email">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Name</label>
                                            </div>
                                            <input type="text" required class="form-control" value=" " name="name">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Password</label>
                                            </div>
                                            <input type="password" required class="form-control" value=""
                                                   name="password">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Confirm Password</label>
                                            </div>
                                            <input type="password" required class="form-control" value=""
                                                   name="password_confirmation">
                                        </div>
                                    </div>

                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Profile</label>
                                            </div>
                                            <input type="file" id="profile" class="form-control dropify" value=" "
                                                   name="profile">
                                        </div>
                                    </div>


                                </div>
                                <div class=" row mt-5 mb-3">
                                    <div class="col-12">
                                        <div class="form-group text-right">
                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                            <a href="{!! route('users.index') !!}"
                                               class=" btn btn-sm btn-danger">Cancel </a>
                                        </div>
                                    </div>
                                </div>


                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{--            <!-- Modal for create -->--}}
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Edit</h4>
                        <button type="button" id="close" class="close modalclose" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">

                        <div class="form-group">
                            <form id="editform">
                                @csrf


                                <div class="form-group">
                                    <div class="input-label">
                                        <label>Role</label>
                                    </div>
                                    <select required id="role" name="role_id[]" id="role_id_edit" class="form-control">
                                        <option value="">Select Option</option>
                                        @foreach($roles as $item)
                                            <option selected value="{!! $item->id !!}">{!! $item->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Email</label>
                                            </div>
                                            <input type="email" id="email_edit" required class="form-control" value=""
                                                   name="email">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Name</label>
                                            </div>
                                            <input type="text" id="name_edit" required class="form-control" value=" "
                                                   name="name">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Password</label>
                                            </div>
                                            <input type="password" required class="form-control" value=""
                                                   name="password">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Confirm Password</label>
                                            </div>
                                            <input type="password" required class="form-control" value=""
                                                   name="password_confirmation">
                                        </div>
                                    </div>

                                </div>
                                <input type="hidden" name="id" id="edit_id" class="form-control">
                                {{--                                <div class="row mt-3">--}}
                                {{--                                    <div class="col-12">--}}
                                {{--                                        <div class="form-group">--}}
                                {{--                                            <div class="input-label">--}}
                                {{--                                                <label>Profile</label>--}}
                                {{--                                            </div>--}}
                                {{--                                            <input type="file" id="profile" class="form-control dropify" value=" "--}}
                                {{--                                                   name="profile">--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}


                                {{--                                </div>--}}
                                <div class=" row mt-5 mb-3">
                                    <div class="col-12">
                                        <div class="form-group text-right">
                                            <input id="tag-form-submit" type="submit" class="btn btn-primary btn btn-sm"
                                                   value="Submit">
                                            <a class=" btn btn-sm btn-danger modalclose">Cancel </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


@stop
@section('css')

@endsection
@section('js')
 

            <script type="text/javascript">
                var tableData;
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
                            "url": "{{ route('datatable.get-data-user') }}",
                            "type": "POST",
                            "data": {_token: "{{csrf_token()}}"}
                        },
                        "columns": [
                            {data: 'id', name: 'id'},
                            {data: 'name', name: 'name'},
                            {data: 'email', name: 'email'},
                            {data: 'email_verified_at', name: 'verified'},
                            {data: 'status', name: 'status'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ]
                    });
                });


                $(document).on("submit", "#form_validation", function (event) {

                    event.preventDefault();
                    var formData = new FormData(this);

                    $.ajax({
                        url: " {!! route('users.store') !!}",
                        type: 'POST',
                        data: formData,
                        success: function (data) {

                            $("#close").trigger("click");
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

                $('#file-datatable tbody').on('click', '.user_edit', function () {


                    var id = $(this).data('user-edit').id;
                    var name = $(this).data('user-edit').name;
                    var email = $(this).data('user-edit').email;
                    var role_id = $(this).data('user-edit').role_id;

                    $('#myModal').modal('show');

                    $("#edit_id").val(id);
                    $("#name_edit").val(name);
                    $("#email_edit").val(email);
                    $("#role_id_edit").val(role_id);

                });

                $('#tag-form-submit').on('click', function (e) {

                    e.preventDefault();
                    var id = $('#edit_id').val();
                    var url = "{{ route('users.index') }}";
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

                //Entry Delete Query
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
