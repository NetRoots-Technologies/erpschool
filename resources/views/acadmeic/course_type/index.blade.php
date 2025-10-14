@extends('admin.layouts.main')

@section('title')
    Subject Type
@stop

@section('content')
    <style>
        #modal_name {
            margin-right: 500px;
        }
    </style>
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Subject Type
            </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
@if (Gate::allows('students'))
            <div class="col-12 text-right">
                <a class="btn btn-primary btn-md text-white" id="subjectBtn" data-toggle="modal" data-target="#createModal1"><b>Add Subject Type</b></a>
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
                                    <th class="heading_style">
                                        <input type="checkbox" class="select-all-checkbox" onchange="checkAll(this)">
                                    </th>
                                    <th class="heading_style">No</th>
                                    <th class="heading_style">Name</th>
                                    <th class="heading_style">Description</th>
                                    <th class="heading_style">Status</th>
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
                                    <h4 class="modal-title">Create Subject Type</h4>
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
                                                        <input type="text"  class="form-control" value=""
                                                               id="name" name="name" required>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label id="modal_name">Description</label>
                                                        <textarea name="description"  class="form-control" id="description" cols="4" rows="4" required></textarea>
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
                                    <h4 class="modal-title">Edit Subject Type</h4>
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
                                                           value="" name="name" required>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label id="modal_name">Description</label>
                                                    <textarea name="description" class="form-control" id="description_edit" cols="4" rows="4" required></textarea>
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
                            "pageLength": 10,
                            dom: 'Bfrtip',
                            buttons: [
                                {
                                    extend: 'collection',
                                    className: "btn-light",
                                    text: 'Export',
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
                                        }
                                    ]
                                },
                                {
                                    extend: 'collection',

                                    text: 'Bulk Action',
                                    className: 'btn-light',
                                    buttons: [
                                        {
                                            text: '<i class="fas fa-trash"></i> Delete',
                                            className: 'btn btn-danger delete-button',
                                            action: function () {
                                                var selectedIds = [];

                                                $('#file-datatable').find('.select-checkbox:checked').each(function () {
                                                    selectedIds.push($(this).val());
                                                });

                                                if (selectedIds.length > 0) {
                                                    $ ('.dt-button-collection').hide();

                                                    Swal.fire({
                                                        title: 'Are you sure?',
                                                        text: 'You are about to perform a bulk action!',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#3085d6',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Yes, delete it!',
                                                        cancelButtonText: 'Cancel'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            $.ajax({
                                                                url: '{{ route('academic.courseType-bulk') }}',
                                                                type: 'POST',
                                                                data: {
                                                                    ids: selectedIds,
                                                                    "_token": "{{ csrf_token() }}",
                                                                },
                                                                dataType: 'json',
                                                                success: function (response) {
                                                                    toastr.success('Data Deleted Successfully');
                                                                    tableData.ajax.reload();

                                                                },
                                                                error: function (xhr, status, error) {
                                                                    console.error(xhr.responseText);
                                                                    toastr.error('AJAX request failed: ' + error);
                                                                }
                                                            });
                                                        }
                                                    });
                                                } else {
                                                    toastr.warning('No checkboxes selected.');
                                                }
                                            }
                                        },
                                    ],
                                },

                                {
                                    extend: 'colvis',
                                    columns: ':not(:first-child)'
                                }
                            ],
                            "columnDefs": [
                                { 'visible': false }
                            ],
                            ajax: {
                                "url": "{{ route('datatable.course-type.getdata') }}",
                                "type": "POST",
                                "data": {_token: "{{csrf_token()}}"}
                            },
                            "columns": [

                                {
                                    data: "checkbox",
                                    render : function (data,type,row) {
                                        return '<input type="checkbox" value="' + row.id +'" class="select-checkbox">'
                                    },
                                    orderable: false, searchable: false
                                },
                                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                                {data: 'name', name: 'name'},
                                {data: 'description', name: 'description'},
                                {data: 'status', name: 'status'},
                                {data: 'action', name: 'action', orderable: false, searchable: false},
                            ]
                        });

                    $("#createform").validate({
                    });

                    $("#editform").validate({
                    });

                    //Create Form Submit
                    $('#create-form-submit').on('click', function (e) {

                        e.preventDefault();
                        var name = $('#name').val();

                        if(!$("#createform").valid())
                        {
                            return false;
                        }

                        var url = "{{ route('academic.subject-type.store') }}";
                        $.ajax({
                            type: "post",
                            "url": url,
                            data: $('#createform').serialize(),
                            success: function (response) {
                                $("#close").trigger("click");
                                var title = $('#title').val('');
                                var start_date = $('#start_date').val('');
                                var end_date = $('#end_date').val('');
                                $('#name').val('');
                                tableData.ajax.reload();
                                toastr.success('Subject Type added successfully');
                            },
                            error: function () {
                                toastr.error('Please fill all the fields');
                            }
                        });
                        return false;
                    });


                    $('#file-datatable tbody').on('click', '.course_type_edit', function () {
                        var data = $(this).data('course_type-edit');
                        console.log(data);
                        var id = data.id;
                        var name = data.name;
                        var description = data.description;

                        $("#editform")[0].reset();
                        $("#editform").validate().resetForm();
                        $('.error').removeClass('error');

                        $('#myModal').modal('show');
                        $("#edit_id").val(id);
                        $("#name_edit").val(name);
                        $("#description_edit").val(description);
                    });


                    $(".modalclose").click(function () {

                        $('#myModal').modal('hide');
                    });

                    $(".modalclose").click(function () {

                        $('#createModal1').modal('hide');
                    });

                    $('#tag-form-submit').on('click', function (e) {
                        e.preventDefault();

                        if(!$("#editform").valid())
                        {
                            return false;
                        }

                        var id = $('#edit_id').val();
                        var url = "{{ route('academic.subject-type.index') }}";
                        $.ajax({
                            type: "post",
                            "url": url + '/' + id,
                            data: $('#editform').serialize() + '&_method=PUT',
                            success: function (response) {
                                $('#myModal').modal('hide');
                                tableData.ajax.reload();
                                toastr.success('Subject Type Updated successfully');
                            },
                            error: function () {
                                toastr.error('Please fill all the fields');
                            }
                        });
                        return false;
                    });

                    $('#file-datatable tbody').on('click', '.delete', function () {


                        var data = $(this).data('id');

                        $('#' + data).submit();

                    });

                    $('#file-datatable tbody').on('click', '.change-status', function () {
                        var id = $(this).data('id');
                        var status = $(this).data('status');

                        $.ajax({
                            type: 'POST',
                            url: '{{route('academic.course-type.change-status')}}',
                            data: {
                                id: id,
                                status: status,
                                _token: '{{ csrf_token() }}',
                            },
                            success: function (response) {

                                console.log(response);
                                tableData.ajax.reload();
                                toastr.success("Status Updated successfully.")
                            },
                            error: function (xhr, status, error) {

                                toastr.error(xhr.responseText);
                                console.error(xhr.responseText);
                            }
                        });
                    });

                    // $("#subjectBtn").on("click", function (e) {
                    //     $("#createform")[0].reset();
                    //     $("#createform").validate().resetForm();
                    //     $('.error').removeClass('error');
                    // });

                    // $(".course_type_edit").on("click", function (e) {
                    //     $("#editform")[0].reset();
                    //     $("#editform").validate().resetForm();
                    //     $('.error').removeClass('error');
                    // });

                    $('#createModal1').on('hidden.bs.modal', function () {
                        $("#createform")[0].reset();
                        $("#createform").validate().resetForm();
                        $('.error').removeClass('error');
                    });

                    $('#myModal').on('hidden.bs.modal', function () {
                        $("#editform")[0].reset();
                        $("#editform").validate().resetForm();
                        $('.error').removeClass('error');
                    });

                    $(document).on("click", ".btnDelete", function(e) {

                        e.preventDefault();
                        const id = $(this).data('id');
                        const url = $(this).data('url');

                        confirmDelete(id, url);
                    })

                    function confirmDelete(id, url) {
                        Swal.fire({
                            title: "Are you sure?",
                            text: "You won't be able to revert this!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Yes, delete it!"
                            }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: url,
                                    type: 'POST',
                                    data: {
                                        id: id,
                                        _method: 'DELETE',
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (result) {
                                        toastr.success("Record deleted!");
                                        $("#file-datatable").DataTable().ajax.reload();
                                    },
                                    error: function (xhr, textStatus, errorThrown) {
                                    toastr.error(xhr.responseText);
                                    }

                                // dataTable.row( $(this).parents('tr') ).remove().draw( false );
                                });
                            }
                        });
                    }

                });
                    function checkAll(source) {
                        var checkboxes = $('.select-checkbox');
                        for(var i =0; i < checkboxes.length;i++){
                            checkboxes[i].checked = source.checked;
                        }
                    }

                </script>
@endsection
