@extends('admin.layouts.main')

@section('title')
    Courses
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Courses </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
@if (Gate::allows('students'))
                <div class="col-12 text-right">
                    <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal1">Create Course</a>
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
                                    <th>Course Type</th>
                                    <th>Course Duration</th>
                                    <th>Fee</th>
                                    <th>Status</th>
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
                                                            <label for="course_type">Courses Type</label>
                                                            <select name="course_type_id" class="form-control"
                                                                    id="course_type_id">
                                                                <option selected>Select Courses Type</option>
                                                                @foreach($course_types as $course_type)
                                                                    <option
                                                                        value="{{$course_type->id}}">{{$course_type->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>


                                            <div class="row mt-3 mb-3">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="input-label">
                                                            <label for="course_duration">Duration</label>
                                                            <select name="course_duration" class="form-control"
                                                                    id="course_duration">
                                                                <option selected>Select Duration</option>
                                                                <option value="1 month">1 Month</option>
                                                                <option value="2 months">2 Months</option>
                                                                <option value="3 months">3 Months</option>
                                                                <option value="4 months">4 Months</option>
                                                                <option value="5 months">5 Months</option>
                                                                <option value="6 months">6 Months</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="input-label">
                                                            <label for="fee">Fee</label>
                                                            <input type="number" min="0" required class="form-control"
                                                                   id="fee" value="" name="fee">

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
                                    <h4 class="modal-title">Edit Coure</h4>
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
                                                <label>Select Courses Type</label>
                                            </div>

                                            <select name="course_type_id" class="form-control" id="course_type_id_edit">
                                                <option>Select Courses Type</option>
                                                @foreach($course_types as $key => $course_type)
                                                    <option value="{{$course_type['id']}}"
                                                            selected>{{$course_type['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" name="id" id="edit_id" class="form-control">

                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Course Duration</label>
                                            </div>
                                            <select name="course_duration" class="form-control"
                                                    id="course_duration_edit">
                                                <option>Select Course Duration</option>
                                                <option value="1 month">1 Month</option>
                                                <option value="2 months">2 Months</option>
                                                <option value="3 months">3 Months</option>
                                                <option value="4 months">4 Months</option>
                                                <option value="5 months">5 Months</option>
                                                <option value="6 months">6 Months</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Fee</label>
                                            </div>
                                            <input type="text" name="fee" id="fee_edit" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Status</label>
                                            </div>
                                            <select name="status" class="form-control"
                                                    id="course_status_edit">
                                                <option>Select Status</option>
                                                <option value="0">De-Active</option>
                                                <option value="1">Active</option>

                                            </select>
                                        </div>

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
                            {"visible": false}
                        ],
                        ajax: {
                            "url": "{{ route('datatable.get-data-courses') }}",
                            "type": "POST",
                            "data": {_token: "{{csrf_token()}}"}
                        },
                        "columns": [
                            {data: 'id', name: 'id'},
                            {data: 'name', name: 'name'},
                            {data: 'coursetype_name', name: 'course_type'},
                            {data: 'course_duration', name: 'course_duration'},
                            {data: 'fee', name: 'fee'},
                            {data: 'status', name: 'status'},
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

                    var course_type_id = $('#course_type_id').val();

                    var course_duration = $('#course_duration').val();

                    var fee = $('#fee').val();


                    var url = "{{ route('admin.course.store') }}";
                    $.ajax({
                        type: "post",
                        "url": url,
                        data: $('#createform').serialize(),

                        success: function (response) {

                            $("#close").trigger("click");

                            tableData.ajax.reload();
                            var name = $('#name').val('');

                            var course_type_id = $('#course_type_id').val('Select Courses Type');

                            var course_duration = $('#course_duration').val('Select Duration');

                            var fee = $('#fee').val('');
                        },
                        error: function () {
                            alert('Error');
                        }
                    });
                    return false;
                });


                $('#file-datatable tbody').on('click', '.course_edit', function () {


                    var id = $(this).data('course-edit').id;
                    var name = $(this).data('course-edit').name;
                    var course_duration = $(this).data('course-edit').course_duration;
                    var fee = $(this).data('course-edit').fee;
                    var course_type_id = $(this).data('course-edit').course_type_id;
                    var course_status = $(this).data('course-edit').status;

                    ``
                    $('#myModal').modal('show');

                    $("#edit_id").val(id);
                    $("#name_edit").val(name);
                    $("#course_duration_edit").val(course_duration);
                    $("#fee_edit").val(fee);
                    $("#course_type_id_edit").val(course_type_id);
                    $("#course_status_edit").val(course_status);

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
                    var url = "{{ route('admin.course.index') }}";
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
