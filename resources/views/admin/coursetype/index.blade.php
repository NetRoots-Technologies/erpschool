@extends('admin.layouts.main')

@section('title')
    Course Type
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Course Type </h3>
        </div>

        <div class="row    mt-4 mb-4 ">
@if (Gate::allows('students'))
                <div class="col-12 text-right">
                    <button type="button" id="create-form" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#createModal1">
                        Create CourseType
                    </button>
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
                                    <th>Description</th>
                                    <th>Status</th>
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
        <!-- Modal for create -->
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
                            <form id="createform">
                                @csrf

                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Name</label>
                                            </div>
                                            <input type="text" id="name" required class="form-control" value=""
                                                   name="name">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Description</label>
                                            </div>
                                            <input type="text" id="description" required class="form-control" value=""
                                                   name="description">
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="row mt-5 mb-3">

                                <div class="col-12">
                                    <div class="form-group text-right">
                                        <input id="create-form-submit" type="submit" class="btn btn-primary"
                                               value="Submit">

                                        <a href="" class=" btn btn-sm btn-danger modalclose"
                                           data-dismiss="modal">Cancel </a>

                                    </div>
                                </div>
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
                        <h4 class="modal-title">Edit CoureType</h4>
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
                                    <label>Description</label>
                                </div>
                                <input type="text" name="description" id="description_edit" class="form-control">
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

        @endsection
        @section('js')

            <script type="text/javascript">
                    {{--                Datatable Ajax--}}
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
                            "url": "{{ route('datatable.get-data-coursetype') }}",
                            "type": "POST",
                            "data": {_token: "{{csrf_token()}}"}
                        },
                        "columns": [
                            {data: 'id', name: 'id'},
                            {data: 'name', name: 'name'},
                            {data: 'description', name: 'description'},
                            {data: 'status', name: 'status'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ]
                    });
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

                //Entry Edit Button Click
                $('#file-datatable tbody').on('click', '.course_type_edit', function () {
                    var id = $(this).data('course-type-edit').id;
                    var name = $(this).data('course-type-edit').name;
                    var description = $(this).data('course-type-edit').description;
                    $('#myModal').modal('show');
                    $("#edit_id").val(id);
                    $("#name_edit").val(name);
                    $("#description_edit").val(description);
                });

                //Edit Form Submit
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

                //Create Form Submit
                $('#create-form-submit').on('click', function (e) {

                    e.preventDefault();
                    var name = $('#name').val();
                    var description = $('#description').val();
                    var url = "{{ route('admin.coursetype.store') }}";
                    $.ajax({
                        type: "post",
                        "url": url,
                        data: $('#createform').serialize(),
                        success: function (response) {

                            $("#close").trigger("click");

                            var name = $('#name').val('');
                            var description = $('#description').val('');

                            tableData.ajax.reload();
                        },
                        error: function () {
                            alert('Error');
                        }
                    });
                    return false;
                });

                //Modal CLose
                $(".modalclose").click(function () {

                    $('#createModal1').modal('hide');
                });
                $(".modalclose").click(function () {

                    $('#myModal').modal('hide');
                });
            </script>
@endsection
