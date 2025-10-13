@extends('admin.layouts.main')

@section('title')
Branches
@stop

@section('content')
<style>
    .branch_Style {
        float: left;
    }

    .select-checkbox {
        margin-right: 13px !important;
    }
</style>
<div class="container-fluid">
    <div class="row w-100  mt-4 ">
        <h3 class="text-22 text-center text-bold w-100 mb-4"> Branches </h3>
    </div>


    {{-- <div class="row    mt-4 mb-4 "> --}}
@if (Gate::allows('students'))
        {{-- <div class="col-12 text-right">
            <a class="btn btn-primary btn-md text-white" data-toggle="modal" data-target="#createModal1">
                <b>Add Branches</b>
            </a>
        </div> --}}
        @endif
        {{-- </div> --}}


    <div class="row mt-4 mb-4 justify-content-start gap-4">
        {{-- Add branch --}}
        {{-- @if (Gate::allows('branch-create'))--}}

        <div class="col-auto p-0">
            <a class="btn btn-primary btn-md text-white" style="margin-left: 15px;" data-toggle="modal"
                data-target="#createModal1">
                <b>Add Branches</b>
            </a>
        </div>
        {{--@endif--}}

        {{-- Download Sample Bulk File --}}
        <div class="col-auto p-0" style="display: none;">
            <a href="{{ route('academic.branch.export-file') }}" class="btn btn-warning btn-md">
                <b>Download Sample Bulk File</b>
            </a>
        </div>


        {{-- Import Sample Bulk File --}}
        <div class="col-auto" style="display: none;">
            <a href="#" class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#importModal">
                <b>Import Data</b>
            </a>
        </div>

        <div class="col-auto p-0">
            <a href="{{route('print-preview', 'branches')}}" class="btn btn-info btn-md">
                <b>Print Preview</b>
            </a>
        </div>

    </div>

    <!-- Import File Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('academic.branch.import-file') }}" method="POST" enctype="multipart/form-data">
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
                                    <th class="heading_style">
                                        <input type="checkbox" class="select-all-checkbox" onchange="checkAll(this)">
                                    </th>
                                    <th class="heading_style">No</th>
                                    <th class="heading_style">Company</th>
                                    <th class="heading_style">Name</th>
                                    <th class="heading_style">Ip Config</th>
                                    <th class="heading_style">Sync Data</th>
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
                                <h4 class="modal-title">Create Branch</h4>
                                <button type="button" id="close" class="close modalclose" data-dismiss="modal">
                                    &times;
                                </button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">

                                <div class="form-group">
                                    <form id="createform">
                                        @csrf

                                        <div class="container">
                                            <div class="row mt-3">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="input-label">
                                                            <label class="branch_Style"><b>Name*</b></label>
                                                        </div>
                                                        <input type="text" required class="form-control" value=""
                                                            id="title" name="name">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="input-label">
                                                            <label class="branch_Style"><b>Company*</b></label>
                                                        </div>
                                                        <select name="company_id" class="form-control" id="company_id"
                                                            required>
                                                            <option value="" selected disabled>Select Company</option>
                                                            @foreach ($company as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <label for="ip" class="branch_Style"><b>IP</b></label>
                                                        <input type="text" name="ip_config" class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="port" class="branch_Style"><b>Port</b></label>
                                                        <input type="number" name="port" class="form-control">
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label for="otherBranchSelect" class="branch_Style"><b>Select
                                                            School Type</b></label>
                                                    <select class="form-select select2 basic-single" id="schoolType"
                                                        aria-label="Default select example" name="selectSchool[]"
                                                        multiple="multiple">

                                                        @foreach ($schoolTypes as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <label for="branch_code" class="branch_Style">
                                                        <b>Student Branch Code*</b>
                                                    </label>
                                                    <input type="text" class="form-control" name="branch_code">
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label for="address"
                                                        class="col-sm-2 col-form-label branch_Style"><b>Address</b></label>
                                                    <textarea class="form-control" id="address" name="address"
                                                        rows="4"></textarea>
                                                </div>
                                            </div>


                                            <div class="row mt-5 mb-3">

                                                <div class="col-12">
                                                    <div class="form-group text-right">
                                                        <input id="create-form-submit" type="submit"
                                                            class="btn btn-primary" value="Submit">
                                                        <a href="" class=" btn btn-danger modalclose ms-5"
                                                            data-dismiss="modal">Cancel </a>
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


                <!-- The Modal for Edit -->
                <div class="modal modal1" id="myModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Branch</h4>
                                <button type="button" id="close" class="close modalclose" data-dismiss="modal1">
                                    &times;
                                </button>
                            </div>
                            <!-- Modal body  -->

                            <div class="modal-body">
                                <form id="editform">
                                    @csrf

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="input-label">
                                                <label class="branch_Style"><b>Name*</b></label>
                                            </div>
                                            <input type="text" required class="form-control" id="name_edit" value=""
                                                name="name">
                                        </div>


                                        <div class="col-6">

                                            <div class="input-label">
                                                <label class="branch_Style"><b>Company*</b></label>
                                            </div>
                                            <select name="company_id" class="form-control" id="company_id_edit">
                                                <option value="" disabled><b>Select Company</b></option>
                                                @foreach ($company as $key => $item)
                                                    <option value="{{ $item['id'] }}" selected>{{ $item['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-8">
                                            <label for="ip" class="branch_Style"><b>IP</b></label>
                                            <input type="text" name="ip_config" id="ip_config_edit"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label for="port" class="branch_Style"><b>Port</b></label>
                                            <input type="text" name="port" id="port_edit" class="form-control">
                                        </div>
                                    </div>


                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <label for="otherBranchSelect">Select School Type</label>
                                            <select class="form-select select2 basic-single" id="schoolType_edit"
                                                aria-label="Default select example" name="selectSchool[]"
                                                multiple="multiple">
                                                <option value="" selected disabled>Select School Type</option>
                                                @foreach ($schoolTypes as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>


                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <label for="branch_code" class="branch_Style">
                                                <b>Student Branch Code*</b></label>
                                            <input type="text" class="form-control branch_code" id="branch_code"
                                                name="branch_code">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <label for="address" class="col-sm-2 col-form-label">Address</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="address_edit" name="address"
                                                rows="4"></textarea>
                                        </div>
                                    </div>


                                    <input type="hidden" name="id" id="edit_id" class="form-control">
                                </form>
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">

                                <input id="tag-form-submit" type="submit" class="btn btn-primary btn btn-sm"
                                    value="Submit">

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
        {{--
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"> --}}
        {{--
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script> --}}
        {{--
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script> --}}
        {{--
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script> --}}
        {{--
        <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet"> --}}
        {{--
        <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet"> --}}
        <style>
            .error {
                display: flex !important;
                justify-content: start !important;
                color: red !important;
            }
        </style>

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
                    buttons: [{
                        extend: 'collection',
                        className: "btn-light",
                        text: 'Export',
                        buttons: [{
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
                        buttons: [{
                            text: '<i class="fas fa-trash"></i> Delete',
                            className: 'btn btn-danger delete-button',
                            action: function () {
                                var selectedIds = [];

                                $('#file-datatable').find('.select-checkbox:checked').each(
                                    function () {
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
                                                url: '{{ route('hr.branches-bulk') }}',
                                                type: 'POST',
                                                data: {
                                                    ids: selectedIds,
                                                    "_token": "{{ csrf_token() }}",
                                                },
                                                dataType: 'json',
                                                success: function (
                                                    response) {
                                                    Swal.fire(
                                                        'Deleted!',
                                                        'Your data has been deleted.',
                                                        'success');
                                                    tableData.ajax
                                                        .reload();

                                                },
                                                error: function (xhr, status,
                                                    error) {
                                                    console.error(xhr
                                                        .responseText
                                                    );
                                                    alert('AJAX request failed: ' +
                                                        error);
                                                }
                                            });
                                        }
                                    });
                                } else {
                                    toastr.warning("No checkboxes selected");
                                }
                            }
                        },],
                    },

                    {
                        extend: 'colvis',
                        columns: ':not(:first-child)'
                    }
                    ],
                    "columnDefs": [{
                        'visible': false
                    }],
                    ajax: {
                        "url": "{{ route('datatable.get-data-branch') }}",
                        "type": "POST",
                        "data": {
                            _token: "{{ csrf_token() }}"
                        }
                    },
                    "columns": [{
                        "data": "checkbox",
                        "render": function (data, type, row) {
                            return '<input type="checkbox" value="' + row.id +
                                '" class="select-checkbox">';
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'company',
                        name: 'company'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'ip_config',
                        name: 'ip_config'
                    },
                    {
                        data: 'sync_Data',
                        name: 'sync_Data'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    ]
                });

                $.validator.addMethod("ipv4", function (value, element) {
                    return this.optional(element) ||
                        /^(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)$/.test(value);
                }, "Please enter a valid IPv4 address.");

                $("#createform").validate({
                    rules: {
                        name: {
                            required: true,
                            minlength: 3
                        },
                        company_id: {
                            required: true
                        },
                        ip_config: {
                            required: true,
                            ipv4: true
                        },
                        port: {
                            required: true,
                            digits: true,
                            min: 1,
                            max: 65535
                        },
                        selectSchool: {
                            required: true,
                            minlength: 1  // At least one school should be selected
                        },
                        branch_code: {
                            required: true,
                            minlength: 3
                        },
                        address: {
                            required: true,
                            minlength: 10
                        }
                    },
                    messages: {
                        name: {
                            required: "Please enter branch name.",
                            minlength: "Branch name should be at least 3 characters long."
                        },
                        company_id: {
                            required: "Please select a company."
                        },
                        ip_config: {
                            required: "Please enter IP configuration.",
                            minlength: "IP configuration should be at least 7 characters long."
                        },
                        port: {
                            required: "Please enter a valid port number.",
                            digits: "Port number should be a digit.",
                            min: "Port number should be at least 1.",
                            max: "Port number should not be more than 65535."
                        },
                        selectSchool: {
                            required: "Please select at least one school.",
                            minlength: "You need to select at least one school."
                        },
                        branch_code: {
                            required: "Please enter a branch code.",
                            minlength: "Branch code should be at least 3 characters long."
                        },
                        address: {
                            required: "Please enter an address.",
                            minlength: "Address should be at least 10 characters long."
                        }
                    },
                    submitHandler: function (form) {
                        // If form is valid, you can proceed with form submission or AJAX request
                        var loader = $('<div class="loader"></div>').appendTo('.createModal1');

                        var url = "{{ route('admin.branches.store') }}";
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: $('#createform').serialize(),
                            success: function (response) {
                                loader.remove();
                                toastr.success(response.message);
                                $("#close").trigger("click");
                                $('#createform')[0].reset();
                                $('#schoolType').val(null).trigger('change');
                                tableData.ajax.reload();
                            },
                            error: function (xhr) {
                                loader.remove();
                                toastr.error(xhr.responseJSON.error);
                            }
                        });
                    }
                });

                $("#editform").validate({
                    rules: {
                        name: {
                            required: true,
                            minlength: 3
                        },
                        company_id: {
                            required: true
                        },
                        ip_config: {
                            required: true,
                            ipv4: true
                        },
                        port: {
                            required: true,
                            digits: true,
                            min: 1,
                            max: 65535
                        },
                        selectSchool: {
                            required: true,
                            minlength: 1
                        },
                        branch_code: {
                            required: true,
                            minlength: 3
                        },
                        address: {
                            required: true,
                            minlength: 10
                        }
                    },
                    messages: {
                        name: {
                            required: "Please enter branch name.",
                            minlength: "Branch name should be at least 3 characters long."
                        },
                        company_id: {
                            required: "Please select a company."
                        },
                        ip_config: {
                            required: "Please enter IP configuration.",
                            minlength: "IP configuration should be at least 7 characters long."
                        },
                        port: {
                            required: "Please enter a valid port number.",
                            digits: "Port number should be a digit.",
                            min: "Port number should be at least 1.",
                            max: "Port number should not be more than 65535."
                        },
                        selectSchool: {
                            required: "Please select at least one school.",
                            minlength: "You need to select at least one school."
                        },
                        branch_code: {
                            required: "Please enter a branch code.",
                            minlength: "Branch code should be at least 3 characters long."
                        },
                        address: {
                            required: "Please enter an address.",
                            minlength: "Address should be at least 10 characters long."
                        }
                    },
                    submitHandler: function (form) {
                        var loader = $('<div class="loader"></div>').appendTo('.createModal1');

                        var url = "{{ route('admin.branches.store') }}";
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: $('#createform').serialize(),
                            success: function (response) {
                                loader.remove();
                                toastr.success(response.message);
                                $("#close").trigger("click");
                                $('#createform')[0].reset();
                                $('#schoolType').val(null).trigger('change');
                                tableData.ajax.reload();
                            },
                            error: function (xhr) {
                                loader.remove();
                                toastr.error(xhr.responseJSON.error);
                            }
                        });
                    }
                });

                //Create Form Submit
                $('#create-form-submit').on('click', function (e) {

                    var loader = $('<div class="loader"></div>').appendTo('.createModal1');


                    e.preventDefault();
                    if ($('#createform').valid()) {

                        var url = "{{ route('admin.branches.store') }}";
                        $.ajax({
                            type: "post",
                            "url": url,
                            data: $('#createform').serialize(),
                            success: function (response) {

                                $("#close").trigger("click");
                                $('#createform')[0].reset();
                                // $('#title').val('');
                                // $('#company_id').val('');
                                $('#schoolType').val(null).trigger('change');
                                loader.remove();


                                tableData.ajax.reload();
                                toastr.success(response.message);
                            },
                            error: function (xhr) {
                                loader.remove();
                                toastr.error(xhr.responseJSON.error);
                            }
                        });
                    }
                    else {
                        loader.remove();
                        toastr.error("Please fill all the fields");
                    }
                    return false;
                });

                $('#file-datatable tbody').on('click', '.branches_edit', function () {
                    var id = $(this).data('branch-edit').id;
                    var name = $(this).data('branch-edit').name;
                    var company_id_edit = $(this).data('branch-edit').company_id;
                    var ip_config = $(this).data('branch-edit').ip_config;
                    var port = $(this).data('branch-edit').port;
                    var school_edit_id = $(this).data('branch-edit').school_branch;
                    var address = $(this).data('branch-edit').address;
                    var branch_code = $(this).data('branch-edit').branch_code;
                    var emp_branch_code = $(this).data('branch-edit').emp_branch_code;

                    console.log(emp_branch_code);
                    $('#myModal').modal('show');
                    $("#edit_id").val(id);
                    $("#name_edit").val(name);
                    $("#company_id_edit").val(company_id_edit);
                    $('#ip_config_edit').val(ip_config);
                    $('#port_edit').val(port);
                    $('#address_edit').val(address);

                    if (branch_code == null || branch_code == '') {
                        $('.branch_code').val('');

                    } else {
                        $('.branch_code').val(branch_code);
                        $('.branch_code').prop('disabled', true)
                    }

                    if (emp_branch_code == null || emp_branch_code == '') {
                        $('.emp_branch_code_edit').val('');

                    } else {
                        $('.emp_branch_code_edit').val(emp_branch_code);
                        $('.emp_branch_code_edit').prop('disabled', true)
                    }

                    $('#schoolType_edit').val(null).trigger('change');

                    $.each(school_edit_id, function (key, value) {
                        $('#schoolType_edit option[value="' + value.school_type_id + '"]').prop('selected', true);
                    });

                    $('#schoolType_edit').trigger('change');
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
                    var url = "{{ route('admin.branches.index') }}";
                    var loader = $('<div class="loader"></div>').appendTo('.myModal');
                    
                    if (!$("#editform").valid()) {
                        return false;
                    }
                    $.ajax({
                        type: "POST",
                        "url": url + '/' + id,
                        data: $('#editform').serialize() + '&_method=PUT',
                        success: function (response) {

                            loader.remove();

                            $('#myModal').modal('hide');

                            tableData.ajax.reload();
                            toastr.success("Branch Updated successfully");
                        },
                        error: function () {
                            loader.remove();

                            alert('Error');
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
                                type: 'POST',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                     _method: 'DELETE' 
                                },
                                success: function (result) {

                                    tableData.ajax.reload();
                                    toastr.success("Branch Deleted successfully")
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
                        url: '{{ route('hr.branch.change-status') }}',
                        data: {
                            id: id,
                            status: status,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (response) {
                            loader.remove();

                            console.log(response);
                            tableData.ajax.reload();
                            toastr.success("Status updated successfully")

                        },
                        error: function (xhr, status, error) {

                            console.error(xhr.responseText);
                        }
                    });
                });


                $('#file-datatable tbody').on('click', '.sync_data', function () {
                    var id = $(this).data('id');

                    var loader = $('<div class="loader"></div>').appendTo('body');

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('hr.branch.sync-data') }}',
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (response) {
                            console.log(response);
                            loader.remove();
                            toastrsuccess("Branch Attendance Added in Machine successfully.");
                            tableData.ajax.reload();

                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                            loader.remove();
                            toastr.error("An error occurred while processing the request.");
                        }
                    });
                });







            });
            function checkAll(source) {
                // alert('hello');
                $('.select-checkbox').prop('checked', source.checked);
            }
        </script>
    @endsection