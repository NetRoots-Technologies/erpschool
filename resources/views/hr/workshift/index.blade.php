@extends('admin.layouts.main')

@section('title')
    WorkShift
@stop

@section('content')
    <style>
        #modal_name {
            margin-right: 500px;
        }
    </style>
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> WorkShift </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
@if (Gate::allows('WorkShift-create'))
            <div class="col-12 text-right">
                <a class="btn btn-primary text-white btn-md" data-toggle="modal" data-target="#createModal1"><b>Create WorkShifts</b></a>
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
                                    <th class="heading_style">No</th>
                                    <th class="heading_style">Name</th>
                                    <th class="heading_style">Start Time</th>
                                    <th class="heading_style">End Time</th>
                                    <th class="heading_style">Action</th>
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
                                    <h4 class="modal-title">Create WorkShift</h4>
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
                                                        <label id="modal_name">Name</label>
                                                        <input type="text" required class="form-control" value=""
                                                               id="name" name="name">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label id="modal_name">Start Time</label>
                                                        <input type="time" required class="form-control start_time" value=""
                                                               id="start_time" name="start_time">
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label id="modal_name">End Time</label>
                                                        <input type="time" required class="form-control end_time" value=""
                                                              id="end_time" name="end_time">
                                                        <span class="error_message" style="color: red"></span>

                                                    </div>
                                                </div>
                                                    <div class="col-lg-12">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>Day</th>
                                                                <th>Shift Status</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>Monday</td>
                                                                <td>
                                                                    <select name="monday">
                                                                        <option value="1">Day On</option>
                                                                        <option value="0">Day Off</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Tuesday</td>
                                                                <td>
                                                                    <select name="tuesday">
                                                                        <option value="1">Day On</option>
                                                                        <option value="0">Day Off</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Wednesday</td>
                                                                <td>
                                                                    <select name="wednesday">
                                                                        <option value="1">Day On</option>
                                                                        <option value="0">Day Off</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Thursday</td>
                                                                <td>
                                                                    <select name="thursday">
                                                                        <option value="1">Day On</option>
                                                                        <option value="0">Day Off</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Friday</td>
                                                                <td>
                                                                    <select name="friday">
                                                                        <option value="1">Day On</option>
                                                                        <option value="0">Day Off</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Saturday</td>
                                                                <td>
                                                                    <select name="saturday">
                                                                        <option value="1">Day On</option>
                                                                        <option value="0">Day Off</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Sunday</td>
                                                                <td>
                                                                    <select name="sunday">
                                                                        <option value="1">Day On</option>
                                                                        <option value="0">Day Off</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>



                                                </div>
                                                <div class="modal-footer">

                                                    <input id="create-form-submit" type="submit" class="btn btn-primary btn btn-md"
                                                           value="Submit">

                                                    <button type="button" class="btn btn-danger btn btn-md modalclose"
                                                            data-dismiss="modal">Close
                                                    </button>
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
                                    <h4 class="modal-title">Edit WorkShift</h4>
                                    <button type="button" id="close" class="close modalclose" data-dismiss="modal1">
                                        &times;
                                    </button>
                                </div>

                                <!-- Modal body  -->

                                <div class="modal-body">
                                    <form id="editform">

                                        @csrf
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label for="name" id="modal_name">Name</label>
                                                    </div>
                                                    <input type="text"  class="form-control" id="name_edit"
                                                           value="" name="name">
                                                </div>

                                                <div class="form-row">
                                                    <div class="col-lg-6">
                                                        <div class="input-label">
                                                            <label for="name" id="modal_name">Start Time</label>
                                                        </div>
                                                        <input type="time"  class="form-control start_time" id="start_time_edit"
                                                               value="" name="start_time">
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="input-label">
                                                            <label for="name" id="modal_name">End Time</label>
                                                        </div>
                                                        <input type="time"  class="form-control end_time" id="end_time_edit"
                                                               value="" name="end_time">
                                                        <span class="error_message"></span>
                                                    </div>

                                                    <div class="col-lg-12" style="margin-top: 10px">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>Day</th>
                                                                <th>Shift Status</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>Monday</td>
                                                                <td>
                                                                    <select id="monday_edit" name="monday_edit">
                                                                        <option value="1">Day On</option>
                                                                        <option value="0">Day Off</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Tuesday</td>
                                                                <td>
                                                                    <select id="tuesday_edit" name="tuesday_edit">
                                                                        <option value="1">Day On</option>
                                                                        <option value="0">Day Off</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Wednesday</td>
                                                                <td>
                                                                    <select id="wednesday_edit" name="wednesday_edit">
                                                                        <option value="1">Day On</option>
                                                                        <option value="0">Day Off</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Thursday</td>
                                                                <td>
                                                                    <select id="thursday_edit" name="thursday_edit">
                                                                        <option value="1">Day On</option>
                                                                        <option value="0">Day Off</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Friday</td>
                                                                <td>
                                                                    <select id="friday_edit" name="friday_edit">
                                                                        <option value="1">Day On</option>
                                                                        <option value="0">Day Off</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Saturday</td>
                                                                <td>
                                                                    <select id="saturday_edit" name="saturday_edit">
                                                                        <option value="1">Day On</option>
                                                                        <option value="0">Day Off</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Sunday</td>
                                                                <td>
                                                                    <select id="sunday_edit" name="sunday_edit">
                                                                        <option value="1">Day On</option>
                                                                        <option value="0">Day Off</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="id" id="edit_id" class="form-control">


                                            <!-- Modal footer -->
                                            <div class="modal-footer">

                                                <input id="tag-form-submit" type="submit" class="btn btn-primary btn btn-sm"
                                                       value="Update">

                                                <button type="button" class="btn btn-danger btn btn-sm modalclose"
                                                        data-dismiss="modal1">Close
                                                </button>
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
                {{--            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">--}}
                {{--            <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>--}}
                {{--            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>--}}
                {{--            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>--}}
                {{--            <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">--}}
                {{--            <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">--}}
                <style>
                    .error
                    {
                        color: red;
                        font-size: 14px;
                        font-weight: 500;
                    }
                </style>
                @endsection
            @section('js')

                <script type="text/javascript">
                    var tableData = null;
                    $(document).ready(function () {

                        $("#createform").validate({

                            rules: {
                                name: {
                                    required: true,
                                },
                                start_time: {
                                    required: true,
                                },
                                end_time: {
                                    required: true,
                                },
                            },
                            messages: {
                                name: {
                                    required: "Please enter name",
                                },
                                start_time: {
                                    required: "Please enter start time",
                                },
                                end_time: {
                                    required: "Please enter end time",
                                },
                            },

                        });

                        $("#editform").validate({

                            rules: {
                                name: {
                                    required: true,
                                },
                                start_time: {
                                    required: true,
                                },
                                end_time: {
                                    required: true,
                                },
                            },
                            messages: {
                                name: {
                                    required: "Please enter name",
                                },
                                start_time: {
                                    required: "Please enter start time",
                                },
                                end_time: {
                                    required: "Please enter end time",
                                },
                            },
                        });

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
                                "url": "{{ route('datatable.workshift.getdata') }}",
                                "type": "POST",
                                "data": {_token: "{{csrf_token()}}"}
                            },
                            "columns": [
                                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                                {data: 'name', name: 'name'},
                                {data: 'start_time', name: 'start_time'},
                                {data: 'end_time', name: 'end_time'},
                                {data: 'action', name: 'action', orderable: false, searchable: false},
                            ]
                        });
                    });


                    //Create Form Submit
                    $('#create-form-submit').on('click', function (e) {
                        e.preventDefault();

                        if (!$("#createform").valid()) {
                            return false;
                        }

                        var url = "{{ route('hr.work_shifts.store') }}";
                        $.ajax({
                            type: "post",
                            "url": url,
                            data:$('#createform').serialize(),
                            success: function (response) {
                                $("#close").trigger("click");
                                $('#name').val('');
                                $('#start_time').val('');
                                $('#end_time').val('');
                                tableData.ajax.reload();
                                toastr.success('WorkShift Created successfully.');
                            },
                            error: function () {
                                toastr.error('Error while adding Workshift');
                            }
                        });
                        return false;
                    });


                    $('#file-datatable tbody').on('click', '.workShift_edit', function () {

                        var WorkShiftData = $(this).data('workshift-edit');

                        console.log(WorkShiftData);
                        $('#myModal').modal('show');
                        $("#edit_id").val(WorkShiftData.id);
                        $("#name_edit").val(WorkShiftData.name);
                        $("#start_time_edit").val(WorkShiftData.start_time);
                        $("#end_time_edit").val(WorkShiftData.end_time);

                        $("#monday_edit").val(WorkShiftData.workdays.Mon);
                        $("#tuesday_edit").val(WorkShiftData.workdays.Tue);
                        $("#wednesday_edit").val(WorkShiftData.workdays.Wed);
                        $("#thursday_edit").val(WorkShiftData.workdays.Thu);
                        $("#friday_edit").val(WorkShiftData.workdays.Fri);
                        $("#saturday_edit").val(WorkShiftData.workdays.Sat);
                        $("#sunday_edit").val(WorkShiftData.workdays.Sun);
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
                        var url = "{{ route('hr.work_shifts.index') }}";
                        if(!$('#editform').valid()){
                            return false;
                        }
                        $.ajax({
                            type: "post",
                            "url": url + '/' + id,
                            data: $('#editform').serialize() + '&_method=PUT',
                            success: function (response) {
                                $('#myModal').modal('hide');
                                tableData.ajax.reload();
                                toastr.success('WorkShift Updated successfully.')
                            },
                            error: function () {
                                toastr.error('Error updating WorkShift');
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


                        Swal.fire({
                        title: "Are you sure to delete?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                                url: route,
                                type: 'DELETE',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    _method: 'DELETE'
                                },
                                success: function (result) {
                                    tableData.ajax.reload();
                                    toastr.success("WorkShift Deleted successfully");
                                },
                                error: function (xhr, status, error) {
                                    toastr.error("An error occurred while deleting the WorkShift: " + xhr.responseText);
                                }
                            });

                        }
                    });
                    });

                    $('#file-datatable tbody').on('click', '.change-status', function () {
                        var id = $(this).data('id');
                        var status = $(this).data('status');
                        var loader = $('<div class="loader"></div>').appendTo('body');

                        $.ajax({
                            type: 'POST',
                            url: '{{route('hr.WorkShift-years.change-status')}}',
                            data: {
                                id: id,
                                status: status,
                                _token: '{{ csrf_token() }}',
                            },
                            success: function (response) {
                                loader.remove();

                                console.log(response);
                                tableData.ajax.reload();
                                toastr.success('status updated successfully')
                            },
                            error: function (xhr, status, error) {
                                loader.remove();
                                toastr.error('status not updated')
                                console.error(xhr.responseText);
                            }
                        });
                    });

                </script>


                <script>
                    $(document).ready(function () {
                        $(".end_time").on("change", function () {
                            var startDate = new Date($(".start_time").val());
                            var endDate = new Date($(this).val());

                            if (endDate < startDate) {
                                $(".error_message").text("End Date should be greater than Start Date");
                                $(this).val("");
                            } else {
                                $("#error_message").text("");
                            }
                        });
                    });
                </script>
@endsection
