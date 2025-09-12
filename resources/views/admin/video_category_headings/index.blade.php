@extends('admin.layouts.main')

@section('title')
    Video Category
@stop

@section('content')

    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Video Category </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            <div class="col-12 text-right">
                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal1">Create Video
                    Category</a>
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
                                    <th>Session</th>
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
                                                            <label for="course_id">Session</label>
                                                            <select name="session_id" class="form-control"
                                                                    id="course_id">
                                                                <option selected>Select Session</option>
                                                                @foreach($sessions as $item)
                                                                    <option
                                                                        value="{{$item->id}}">{{$item->title}}</option>
                                                                @endforeach
                                                            </select>
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
                                            <div class="input-label">
                                                <label>Select Session </label>
                                            </div>

                                            <select name="session_id" class="form-control" id="course_edit">
                                                <option>Select Session </option>
                                                @foreach($sessions as $key => $item)
                                                    <option  value="{{$item['id']}}"
                                                            selected>{{$item['title']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" name="id" id="videoedit_id" class="form-control">
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
                            "url": "{{ route('datatable.get-video-category') }}",
                            "type": "POST",
                            "data": {_token: "{{csrf_token()}}"}
                        },
                        "columns": [
                            {data: 'id', name: 'id'},
                            {data: 'name', name: 'name'},
                            {data: 'session', name: 'session'},
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

                    var course_id = $('#course_id').val();

                    var url = "{{ route('admin.video_category.store') }}";
                    $.ajax({
                        type: "post",
                        "url": url,
                        data: $('#createform').serialize(),

                        success: function (response) {

                            $("#close").trigger("click");

                            tableData.ajax.reload();
                            var name = $('#name').val('');

                            var course_id = $('#course_id').val('Select Course');

                        },
                        error: function () {
                            alert('Error');
                        }
                    });
                    return false;
                });


                $('#file-datatable tbody').on('click', '.videoCategory_edit', function () {

                    // console.log($(this).data('videocategory_edit'));
                    var id = $(this).data('videocategory_edit').id;
                    var name = $(this).data('videocategory_edit').name;
                    var course_id = $(this).data('videocategory_edit').course_id;


                    ``
                    $('#myModal').modal('show');

                    $("#videoedit_id").val(id);
                    $("#name_edit").val(name);
                    $("#course_edit").val(course_id);

                });

                $(".modalclose").click(function () {

                    $('#myModal').modal('hide');
                });

                $(".modalclose").click(function () {

                    $('#createModal1').modal('hide');
                });


                $('#tag-form-submit').on('click', function (e) {


                    e.preventDefault();
                    var id = $('#videoedit_id').val();
                    var url = "{{ route('admin.video_category.index') }}";
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
