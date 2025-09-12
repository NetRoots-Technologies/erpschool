@extends('admin.layouts.main')

@section('title')
Designations
@stop

@section('content')
<style>
    .modal_name {
        margin-right: 500px;
    }
</style>
<div class="container-fluid">
    <div class="row w-100  mt-4 ">
        <h3 class="text-22 text-center text-bold w-100 mb-4"> Designation </h3>
    </div>


    <div class="row mt-4 mb-4 justify-content-start gap-4" style="padding: 0 0 0 17px;">
        {{-- Add Designation --}}
     @if (Gate::allows('Designations-create'))

        <div class="col-auto p-0">
            <a class="btn btn-primary btn-md text-white" data-toggle="modal" data-target="#createModal1"><b>Create
                    Designation</b></a>

        </div>

        {{-- Download Sample Bulk File --}}
        <div class="col-auto p-0">
            <a href="{{ route('academic.designation.export-file') }}" class="btn btn-warning btn-md">
                <b>Download Sample Bulk File</b>
            </a>
        </div>

        {{-- Import Sample Bulk File --}}
        <div class="col-auto">
            <a href="#" class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#importModal">
                <b>Import Data</b>
            </a>
        </div>
        <div class="col-auto p-0">
            <a href="{{route('print-preview', 'designations')}}" class="btn btn-info btn-md">
                <b>Print Preview</b>
            </a>
        </div>
        @endif

    </div>

    <!-- Import File Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('academic.designation.import-file') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Excel File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="import_file" class="form-label">Select File</label>
                            <input type="file" name="import_file" id="import_file" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Upload</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
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
                                    <th style="text-align: center">
                                        <input type="checkbox" class="select-all-checkbox" onchange="checkAll(this)">
                                    </th>
                                    <th style="text-align: center">No</th>
                                    <th style="text-align: center">Name</th>

                                    <th style="text-align: center">Status</th>
                                    <th style="text-align: center">Action</th>
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
                                <h4 class="modal-title">Create Designation </h4>
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
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="modal_name">Name</label>
                                                    <input type="text" class="form-control" value="" id="name"
                                                        name="name" required>
                                                </div>
                                            </div>


                                            <div class="col-md-12">
                                                <label for="otherBranchSelect" class="department_name">Select
                                                    Department</label>
                                                <select class="form-select select2 basic-single" id="selectDepartment"
                                                    aria-label="Default select example" name="selectDepartment"
                                                    required>
                                                    <option value="" disabled selected>Select Department</option>
                                                    @foreach($departments as $item)
                                                        <option value="{{$item->id}}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="modal-footer justify-content-center">

                                                <input id="create-form-submit" type="submit"
                                                    class="btn btn-primary btn btn-md" value="Submit">

                                                <button type="button" class="btn btn-danger btn btn-md modalclose ms-5"
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
                                <h4 class="modal-title">Edit Designation</h4>
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
                                                    <label for="name" class="department_name"
                                                        id="modal_name">Name</label>
                                                </div>
                                                <input type="text" class="form-control" id="name_edit" value=""
                                                    name="name">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="otherBranchSelect" class="department_name">Select
                                                Department</label>
                                            <select class="form-select select2 basic-single" id="selectDepartment_edit"
                                                aria-label="Default select example" name="selectDepartment" required>
                                                <option value="">Select Department</option>

                                                @foreach($departments as $item)
                                                    <option value="{{$item->id}}">{{ $item->name}}</option>
                                                @endforeach

                                            </select>
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
            <style>
                .error {
                    color: red;
                    font-weight: 500;
                }

                .modal_name {
                    font-weight: 500;
                }

                .department_name {
                    display: block;
                    font-weight: 500;
                    text-align: start;
                }

                .select2-selection__rendered {
                    text-align: start !important;
                }
            </style>
        @endsection
        @section('js')

            <script type="text/javascript">
                var tableData = null;
                $(document).ready(function () {


                    $('#createform').validate({
                        errorPlacement: function (error, element) {
                            error.insertAfter(element.parent());
                        },
                        rules: {
                            name: {
                                required: true,
                            },
                            selectDepartment: {
                                required: true,
                            },
                        },
                        messages: {
                            name: {
                                required: "Please enter name",
                            },
                            selectDepartment: {
                                required: "Please select department",
                            },
                        },
                    });

                    $('#editform').validate({
                        errorPlacement: function (error, element) {
                            error.insertAfter(element.parent());
                        },
                        rules: {
                            name: {
                                required: true,
                            },
                            selectDepartment: {
                                required: true,
                            },
                        },
                        messages: {
                            name: {
                                required: "Please enter name",
                            },
                            selectDepartment: {
                                required: "Please select department",
                            },
                        },
                    });


                    tableData = $('#file-datatable').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "pageLength": 10,
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'collection',
                                className: "btn-dark",
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
                                ],

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
                                                $('.dt-button-collection').hide();

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
                                                            url: '{{ route('hr.designation-bulk') }}',
                                                            type: 'POST',
                                                            data: {
                                                                ids: selectedIds,
                                                                "_token": "{{ csrf_token() }}",
                                                            },
                                                            dataType: 'json',
                                                            success: function (response) {
                                                                tableData.ajax.reload();
                                                                toastr.success('Deleted.');
                                                            },
                                                            error: function (xhr, status, error) {
                                                                console.error(xhr.responseText);
                                                                toastr.error('AJAX request failed.', error);
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
                            'colvis'

                        ],



                        "columnDefs": [
                            { "visible": false }
                        ],
                        ajax: {
                            "url": "{{ route('datatable.designations.getdata') }}",
                            "type": "POST",
                            "data": { _token: "{{csrf_token()}}" }
                        },
                        "columns": [
                            {
                                "data": "checkbox",
                                "render": function (data, type, row) {
                                    return '<input type="checkbox" value="' + row.id + '" class="select-checkbox">';
                                },
                                orderable: false, searchable: false
                            },
                            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                            { data: 'name', name: 'name' },
                            { data: 'status', name: 'status' },
                            { data: 'action', name: 'action', orderable: false, searchable: false },
                        ],
                        order: [[2, 'desc']]
                    });
                });

                //Create Form Submit
                $('#create-form-submit').on('click', function (e) {

                    e.preventDefault();
                    var name = $('#name').val();


                    var url = "{{ route('hr.designations.store') }}";


                    if (!$('#createform').valid()) {
                        return false;
                    }

                    var loader = $('<div class="loader"></div>').appendTo('body');

                    $.ajax({
                        type: "post",
                        "url": url,
                        data: $('#createform').serialize(),
                        success: function (response) {
                            loader.remove();

                            $("#close").trigger("click");

                            $('#name').val('');
                            $('#selectDepartment').val('');
                            tableData.ajax.reload();
                            toastr.success('Designation Created successfully.');
                        },
                        error: function () {
                            toastr.error('Error while creating Designation.');
                        }
                    });
                    return false;
                });


                $('#file-datatable tbody').on('click', '.designation_edit', function () {

                    var id = $(this).data('designation-edit').id;
                    var name = $(this).data('designation-edit').name;
                    var department_edit_value = $(this).data('designation-edit').department_id;

                    $('#myModal').modal('show');
                    $("#edit_id").val(id);
                    $("#name_edit").val(name);
                    $('#selectDepartment_edit').val(department_edit_value).trigger('change');
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
                    var url = "{{ route('hr.designations.index') }}";
                    if (!$('#editform').valid()) {
                        return false;
                    }
                    var loader = $('<div class="loader"></div>').appendTo('body');

                    $.ajax({
                        type: "put",
                        "url": url + '/' + id,
                        data: $('#editform').serialize(),
                        success: function (response) {
                            $('#myModal').modal('hide');
                            loader.remove();
                            toastr.success('Designation Updated successfully.');

                            tableData.ajax.reload();
                        },
                        error: function () {
                            loader.remove();
                            toastr.error('Error while updating Designation.');
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
                    var loader = $('<div class="loader"></div>').appendTo('body');

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
                                },
                                success: function (result) {
                                    loader.remove();
                                    tableData.ajax.reload();
                                    toastr.success('Designation Deleted successfully.');
                                },
                                error: function (xhr, status, error) {
                                    loader.remove();
                                    toastr.error('Error while deleting Designation.');
                                    console.error(xhr.responseText);
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
                        url: '{{route('hr.designation.change-status')}}',
                        data: {
                            id: id,
                            status: status,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (response) {
                            loader.remove();

                            console.log(response);
                            tableData.ajax.reload();
                            toastr.success('status Updated successfully.');
                        },
                        error: function (xhr, status, error) {
                            loader.remove();
                            toastr.error('Error while updating status.', xhr.responseText);
                            console.error(xhr.responseText);
                        }
                    });
                });
            </script>

            <script>
                function checkAll(source) {
                    var checkboxes = document.querySelectorAll('.select-checkbox');
                    for (var i = 0; i < checkboxes.length; i++) {
                        checkboxes[i].checked = source.checked;
                    }
                }
            </script>

        @endsection
