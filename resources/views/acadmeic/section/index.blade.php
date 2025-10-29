@extends('admin.layouts.main')

@section('title')
    Sections
@stop

@section('content')
    <style>
        .branch_Style {
            float: left;
        }
    </style>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Import Failed:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Sections
            </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
        @if (Gate::allows('Section-create'))
            <div class="col-auto">
                <a class="btn btn-primary btn-md text-white" data-toggle="modal" data-target="#createModal1"><b>Add
                        Section</b></a>
            </div>

              {{-- Download Sample Bulk File --}}
            <div class="col-auto">
                {{-- <a href="{{ config('google_sheet_links.section_file_link') }}" class="btn btn-warning btn-md" target="_blank"> --}}
                <a href="{{ route('academic.section.export-file') }}" class="btn btn-warning btn-md">
                    <b>Export Section File</b>
                </a>
            </div>

            {{-- Import Sample Bulk File --}}
            <div class="col-auto">
                <a href="#" class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#importModal">
                    <b>Import Bluk Data</b>
                </a>
            </div>
            @endif
        </div>
        <!-- Import File Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('academic.section.import-file') }}" method="POST" enctype="multipart/form-data">
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
                                    <th class="heading_style">Session</th>
                                    <th class="heading_style">Branch</th>
                                    <th class="heading_style">Class</th>
                                    <th class="heading_style">Name</th>
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
                                    <h4 class="modal-title">Create Section</h4>
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

                                                <div class="row">
                                                    <div class="form-group">
                                                        <div class="input-label">
                                                            <label class="branch_Style"><b>Company*</b></label>
                                                        </div>
                                                        <select name="company_id" class="form-control company_select"
                                                                required>
                                                            <option value="" selected disabled>Select Company</option>
                                                            @foreach($companies as $item)
                                                                <option
                                                                    value="{{$item->id}}">{{ $item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">

                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <div class="input-label">
                                                                <label class="branch_Style"><b>Academic
                                                                        Session*</b></label>
                                                            </div>
                                                            <select name="session_id"
                                                                    class="form-control session_select"
                                                                    required id="session_edit">
                                                                <option value="" selected disabled>Select Academic Session</option>
                                                                @foreach($sessions as $key => $item)
                                                                    <option
                                                                        value="{{$key}}">{{ $item}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="input-label">
                                                                <label class="branch_Style"><b>Branch*</b></label>
                                                            </div>
                                                            <select name="branch_id" class="form-control branch_select"
                                                                    required>
                                                                <option value="" selected disabled>Select Branch</option>

                                                            </select>
                                                        </div>
                                                    </div>


                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">

                                                        <div class="input-label">
                                                            <label class="branch_Style"><b>Class</b></label>
                                                        </div>
                                                        <select name="class_id" class="form-control select_class"
                                                                id="class_edit">
                                                            <option value="" selected disabled><b>Select Class</b></option>

                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">

                                                        <div class="input-label">
                                                            <label class="branch_Style"><b>Active Session</b></label>
                                                        </div>
                                                        <select name="active_session_id" class="form-control select_active_session"
                                                                id="active_session">
                                                            <option value="" selected disabled><b>Select Class</b></option>

                                                        </select>
                                                    </div>

                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-12">
                                                        <div class="input-label">
                                                            <label class="branch_Style"><b>Name</b></label>
                                                        </div>
                                                        <input type="text" required class="form-control"
                                                               value="" name="name">
                                                    </div>
                                                </div>


                                                <div class="row mt-5 mb-3">

                                                    <div class="col-12 justify-content-center">
                                                        <div class="form-group text-right">
                                                            <input id="create-form-submit" type="submit"
                                                                   class="btn btn-primary"
                                                                   value="Submit">
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
                                    <h4 class="modal-title">Edit Section</h4>
                                    <button type="button" id="close" class="close modalclose" data-dismiss="modal1">
                                        &times;
                                    </button>
                                </div>
                                <!-- Modal body  -->

                                <div class="modal-body">
                                    <form id="editform">
                                        @csrf

                                        <div class="row">
                                            <div class="form-group">
                                                <div class="input-label">
                                                    <label class="branch_Style"><b>Company*</b></label>
                                                </div>
                                                <select name="company_id" class="form-control company_select"
                                                        required id="company_edit">
                                                    <option value="" selected disabled>Select Company</option>
                                                    @foreach($companies as $item)
                                                        <option
                                                            value="{{$item->id}}" selected>{{ $item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label class="branch_Style"><b>Academic Session</b></label>
                                                    </div>
                                                    <select name="session_id" class="form-control">
                                                        @foreach($sessions as $key => $item)
                                                            <option
                                                                value="{{$key}}">{{ $item}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label class="branch_Style"><b>Branch</b></label>
                                                    </div>
                                                    <select name="branch_id" class="form-control branch_select"
                                                            required id="branch_id_edit">

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">

                                                <div class="input-label">
                                                    <label class="branch_Style"><b>Class</b></label>
                                                </div>
                                                <select name="class_id" class="form-control select_class"
                                                        id="class_edit">
                                                    <option value="" selected disabled><b>Select Class</b></option>

                                                </select>
                                            </div>

                                            <div class="col-md-6">

                                                <div class="input-label">
                                                    <label class="branch_Style"><b>Active Session</b></label>
                                                </div>
                                                <select name="active_session_id" class="form-control select_active_session"
                                                        id="active_session_edit">
                                                    <option value="" selected disabled><b>Select Class</b></option>

                                                </select>
                                            </div>

                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <div class="input-label">
                                                    <label class="branch_Style"><b>Name</b></label>
                                                </div>
                                                <input type="text" required class="form-control" id="name_edit"
                                                       value="" name="name">
                                            </div>
                                        </div>


                                        <input type="hidden" name="id" id="edit_id" class="form-control">
                                    </form>
                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer justify-content-center">

                                    <input id="tag-form-submit" type="submit" class="btn btn-primary"
                                           value="Submit">

                                    <button type="button" class="btn btn-danger modalclose ms-5" data-dismiss="modal1">Close
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

            <style>
                .error
                {
                    color: red;
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
                            company_id: {
                                required: true,
                            },
                            session_id: {
                                required: true,
                            },
                            branch_id: {
                                required: true,
                            },
                            class_id: {
                                required: true,
                            },
                            active_session_id: {
                                required: true,
                            },
                        },
                        messages: {
                            name: {
                                required: "Please enter name",
                            },
                            company_id: {
                                required: "Please select company",
                            },
                            session_id: {
                                required: "Please select session",
                            },
                            branch_id: {
                                required: "Please select branch",
                            },
                            class_id: {
                                required: "Please select class",
                            },
                            active_session_id: {
                                required: "Please select active session",
                            },
                        },
                    });

                    $("#editform").validate({
                        rules: {
                            name: {
                                required: true,
                            },
                            company_id: {
                                required: true,
                            },
                            session_id: {
                                required: true,
                            },
                            branch_id: {
                                required: true,
                            },
                            class_id: {
                                required: true,
                            },
                            active_session_id: {
                                required: true,
                            },
                        },
                        messages: {
                            name: {
                                required: "Please enter name",
                            },
                            company_id: {
                                required: "Please select company",
                            },
                            session_id: {
                                required: "Please select session",
                            },
                            branch_id: {
                                required: "Please select branch",
                            },
                            class_id: {
                                required: "Please select class",
                            },
                            active_session_id: {
                                required: "Please select active session",
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
                                                            url: '{{ route('academic.section-bulk') }}',
                                                            type: 'POST',
                                                            data: {
                                                                ids: selectedIds,
                                                                "_token": "{{ csrf_token() }}",
                                                            },
                                                            dataType: 'json',
                                                            success: function (response) {
                                                                toastr.success('Deleted successfully.');
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
                            {'visible': false}
                        ],
                        ajax: {
                            "url": "{{ route('datatable.get-data-section') }}",
                            "type": "POST",
                            "data": {_token: "{{csrf_token()}}"}
                        },
                        "columns": [
                            {
                                "data": "checkbox",
                                "render": function (data, type, row) {
                                    return '<input type="checkbox" value="' + row.id + '" class="select-checkbox">';
                                },
                                orderable: false, searchable: false
                            },
                            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                            {data: 'session', name: 'session'},
                            {data: 'branch', name: 'branch'},
                            {data: 'class', name: 'class'},
                            {data: 'name', name: 'name'},
                            {data: 'status', name: 'status'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ]
                    });
                });

                //Create Form Submit
                $('#create-form-submit').on('click', function (e) {

                    e.preventDefault();
                    var name = $('#title').val();
                    var company_id = $('#company_id').val();

                    if (!$("#createform").valid())
                    {
                        return false;
                    }

                    var url = "{{ route('academic.sections.store') }}";
                    $.ajax({
                        type: "post",
                        "url": url,
                        data: $('#createform').serialize(),
                        success: function (response) {

                            $("#close").trigger("click");
                            $('#createform')[0].reset();
                            // $('#title').val('');
                            // $('#company_id').val('');
                            $('.branch_select').val(null).trigger('change');


                            tableData.ajax.reload();
                            toastr.success('Section Added successfully.');
                        },
                        error: function () {
                            toastr.error('Please fill all the fields');
                        }
                    });
                    return false;
                });


                $('#file-datatable tbody').on('click', '.sections_edit', function () {

                    var id = $(this).data('section-edit').id;
                    var name = $(this).data('section-edit').name;
                    var selected_class_id = $(this).data('section-edit').class_id;
                    var session_val = $(this).data('section-edit').session_id;
                    var branch_val = $(this).data('section-edit').branch_id;
                    var company_id = $(this).data('section-edit').company_id;
                    var selected_active_session = $(this).data('section-edit').active_session_id;

                    console.log(company_id);
                    $('#myModal').modal('show');
                    $("#edit_id").val(id);
                    $("#name_edit").val(name);
                    // $("#class_edit").val(class_edit);

                    $.ajax({
                        type: 'GET',
                        url: '{{ route('hr.fetch.branches') }}',
                        data: {
                            companyid: company_id
                        },
                        success: function (data) {
                            var branchesDropdown = $('.branch_select').empty();

                            branchesDropdown.append('<option value="" selected disabled>Select Branch</option>');

                            data.forEach(function (branch) {
                                var selectbranches = branch.id == branch_val ? 'selected' : '';
                                branchesDropdown.append('<option value="' + branch.id + '" ' + selectbranches + '>' + branch.name + '</option>');
                            });
                        },
                        error: function (error) {
                            console.error('Error fetching branches:', error);
                        }
                    });


                    $.ajax({
                        type: 'GET',
                        url: '{{ route('academic.fetchClass') }}',
                        data: {
                            branch_id: branch_val
                        },
                        success: function (data) {
                            var sectionDropdown = $('.select_class').empty();

                            data.forEach(function (academic_class) {
                                var selectTimetable = academic_class.id == selected_class_id ? 'selected' : '';
                                sectionDropdown.append('<option value="' + academic_class.id + '" ' + selectTimetable + '>' + academic_class.name + '</option>');

                            });
                        },
                        error: function (error) {
                            console.error('Error fetching branches:', error);
                        }
                    });



                    $.ajax({
                        type: 'GET',
                        url: '{{ route('academic.fetch.active_sessions') }}',
                        data: {
                            class_id: selected_class_id
                        },
                        success: function (data) {
                            console.log(data);
                            let ActiveSessionDropdown = $('.select_active_session').empty();

                            var startDate = new Date(data.start_date);
                            var endDate = new Date(data.end_date);

                            var startYear = startDate.getFullYear().toString().slice(-2);
                            var endYear = endDate.getFullYear().toString().slice(-2);

                            var sessionNameWithDate = data.name + ' ' + startYear + '-' + endYear;

                            if (selected_active_session == data.id) {
                                ActiveSessionDropdown.append('<option value="' + data.id + '" selected>' + sessionNameWithDate + '</option>');
                            } else {
                                ActiveSessionDropdown.append('<option value="' + data.id + '">' + sessionNameWithDate + '</option>');
                            }
                        },
                        error: function (error) {
                            console.error('Error fetching branches:', error);
                        }
                    });



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

                    if (!$("#editform").valid()) {
                        return false;
                    }


                    var url = "{{ route('academic.sections.index') }}";
                    $.ajax({
                        type: "post",
                        "url": url + '/' + id,
                        data: $('#editform').serialize() + '&_method=PUT',
                        success: function (response) {


                            $('#myModal').modal('hide');

                            tableData.ajax.reload();
                            toastr.success('Section Updated successfully.');
                        },
                        error: function () {
                            toastr.error('Error while updating section');
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
                                toastr.success("Section Deleted successfully.");

                            },
                            error: function (xhr, status, error) {
                                toastr.error(xhr.responseText);
                                console.error(xhr.responseText);
                            }
                        });
                    }
                    });



                });

                $('#file-datatable tbody').on('click', '.change-status', function () {
                    var id = $(this).data('id');
                    var status = $(this).data('status');

                    $.ajax({
                        type: 'POST',
                        url: '{{route('academic.section.change-status')}}',
                        data: {
                            id: id,
                            status: status,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (response) {

                            console.log(response);
                            tableData.ajax.reload();
                            toastr.success('Status updated successfully.')
                        },
                        error: function (xhr, status, error) {

                            toastr.error(xhr.reponseText);
                            console.error(xhr.responseText);
                        }
                    });
                });


                function checkAll(source) {
                    var checkboxes = $('.select-checkbox');

                    for (var i = 0; i < checkboxes.length; i++) {
                        checkboxes[i].checked = source.checked;
                    }
                }
            </script>



            <script>
                $(document).ready(function () {
                    $('.branch_select').on('change', function () {

                        var branch_id = $(this).val();
                        $.ajax({
                            type: 'GET',
                            url: '{{ route('academic.fetchClass') }}',
                            data: {
                                branch_id: branch_id
                            },
                            success: function (data) {
                                var classDropdown = $('.select_class').empty();

                                classDropdown.append('<option value="" selected disabled>Select Class</option>');


                                data.forEach(function (academic_class) {
                                    classDropdown.append('<option value="' + academic_class.id + '">' + academic_class.name + '</option>');
                                });
                            },
                            error: function (error) {
                                console.error('Error fetching branches:', error);
                            }
                        });

                    });


                    $('.company_select').on('change', function () {
                        var selectedCompanyId = $('.company_select').val();
                        $.ajax({
                            type: 'GET',
                            url: '{{ route('hr.fetch.branches') }}',
                            data: {
                                companyid: selectedCompanyId
                            },
                            success: function (data) {
                                var branchesDropdown = $('.branch_select').empty();

                                branchesDropdown.append('<option value="" selected disabled>Select Branch</option>');

                                data.forEach(function (branch) {
                                    branchesDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                                });
                            },
                            error: function (error) {
                                console.error('Error fetching branches:', error);
                            }
                        });
                    });



                    {{--$('.company_select').on('change', function () {--}}
                    {{--    var selectedCompanyId = $('.company_select').val();--}}
                    {{--    $.ajax({--}}
                    {{--        type: 'GET',--}}
                    {{--        url: '{{ route('academic.fetch.sessions') }}',--}}
                    {{--        data: {--}}
                    {{--            companyid: selectedCompanyId--}}
                    {{--        },--}}
                    {{--        success: function (data) {--}}
                    {{--            var sessionDropdown = $('.session_select').empty();--}}

                    {{--            sessionDropdown.append('<option value="">Select Session</option>');--}}

                    {{--            data.forEach(function (session) {--}}
                    {{--                sessionDropdown.append('<option value="' + session.id + '">' + session.name + '</option>');--}}
                    {{--            });--}}
                    {{--        },--}}
                    {{--        error: function (error) {--}}
                    {{--            console.error('Error fetching branches:', error);--}}
                    {{--        }--}}
                    {{--    });--}}
                    {{--});--}}
                })
            </script>
    <script>
        $(document).ready(function () {
            $('.select_class').on('change', function () {
                var Class_id = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetch.active_sessions') }}',
                    data: {
                        class_id: Class_id
                    },

                    success: function (data) {
                        console.log(data)
                        let ActiveSessionDropdown = $('.select_active_session').empty();
                        ActiveSessionDropdown.append('<option value="">Select Active Session</option>');

                        var startDate = new Date(data.start_date);
                        var endDate = new Date(data.end_date);

                        var startYear = startDate.getFullYear().toString().slice(-2);

                        var endYear = endDate.getFullYear().toString().slice(-2);

                        var sessionNameWithDate = data.name + ' ' + startYear + '-' + endYear;

                        ActiveSessionDropdown.append('<option value="' + data.id + '">' + sessionNameWithDate + '</option>');

                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            });
        })
    </script>
@endsection
