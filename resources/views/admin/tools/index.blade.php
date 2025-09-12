@extends('admin.layouts.main')

@section('title')
    Tools
@stop

@section('content')

    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Tools </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            <div class="col-12 text-right">
                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal1">Create Tools</a>
            </div>
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
                                    <th width="200px">Action</th>
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
                                    <h4 class="modal-title">Create</h4>
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
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="input-label">
                                                            <label>Name</label>
                                                        </div>
                                                        <input type="text" required class="form-control" id="name"
                                                               value="" name="name">
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="input-label">
                                                            <label for="description">Description</label>
                                                            <input type="text" required class="form-control" id="description"
                                                                   value="" name="description">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="row mt-3">
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
                                    <h4 class="modal-title">Edit Video Category</h4>
                                    <button type="button" class="close modalclose" data-dismiss="modal1">&times;
                                    </button>
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
                                            <label for="description">Description</label>
                                            <input type="text" required class="form-control" id="description_edit"
                                                   value="" name="description">
                                        </div>
                                        <input type="hidden" name="id" id="tool_edit_id" class="form-control">
                                    </form>
                                </div>


                                <!-- Modal footer -->
                                <div class="modal-footer">

                                    <input id="tag-form-submit" type="submit" class="btn btn-primary btn btn-sm"
                                           value="Submit">
                                    <button type="button" class="btn btn-danger modalclose btn btn-sm"
                                            data-dismiss="modal1">
                                        Close
                                    </button>
                                </div>

                            </div>
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
                            { "visible": false }
                        ],
                        ajax: {
                            "url": "{{ route('datatable.get_data_tools_tool') }}",
                            "type": "POST",
                            "data": {_token: "{{csrf_token()}}"}
                        },
                        "columns": [
                            {data: 'id', name: 'id'},
                            {data: 'name', name: 'name'},
                            {data: 'description', name: 'description'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ]
                    });


                    $('#file-datatable tbody').on('click', '.delete', function () {

                        var data = $(this).data('id');

                        $('#' + data).submit();

                    });


                });


                $('#create-form-submit').on('click', function (e) {


                    e.preventDefault();


                    var name = $('#name').val();

                    var description = $('#description').val();

                    var url = "{{ route('admin.tools.store') }}";
                    $.ajax({
                        type: "post",
                        "url": url,
                        data: $('#createform').serialize(),

                        success: function (response) {


                            tableData.ajax.reload();
                            var name = $('#name').val('');

                            var description = $('#description').val(' ');
                            $(".modalclose").trigger("click");

                        },
                        error: function () {
                            alert('Error');
                        }
                    });

                });


                $('#file-datatable tbody').on('click', '.tools_edit', function () {

                    // console.log($(this).data('tools_edit'));
                    var id = $(this).data('tools_edit').id;
                    var name = $(this).data('tools_edit').name;
                    var description_edit = $(this).data('tools_edit').description;


                    ``
                    $('#myModal').modal('show');

                    $("#tool_edit_id").val(id);
                    $("#name_edit").val(name);
                    $("#description_edit").val(description_edit);

                });



                $('#tag-form-submit').on('click', function (e) {


                    e.preventDefault();
                    var id = $('#tool_edit_id').val();
                    var url = "{{ route('admin.tools.index') }}";
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
