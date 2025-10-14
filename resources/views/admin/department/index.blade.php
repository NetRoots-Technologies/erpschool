@extends('admin.layouts.main')

@section('title')
Departments
@stop

@section('content')
<div class="container-fluid">
    <div class="row w-100  mt-4 ">
        <h3 class="text-22 text-center text-bold w-100 mb-4"> Departments </h3>
    </div>

    {{-- <div class="row    mt-4 mb-4 "> --}}
@if (Gate::allows('students'))
        {{-- <div class="col-12 text-right">
            <a class="btn btn-primary btn-md text-white" id="createBtn" data-toggle="modal"
                data-target="#createModal1"><b>Create
                    Department</b></a> --}}
            {{--
        </div> --}}
        @endif
        {{-- </div> --}}



    <div class="row mt-4 mb-4 justify-content-start gap-4" style="padding: 0 0 0 15px;">
        {{-- Add Department --}}
     @if (Gate::allows('Departments-create'))

        <div class="col-auto p-0" >
            <a class="btn btn-primary btn-md text-white" id="createBtn" data-toggle="modal"
                data-target="#createModal1"><b>Create
                    Department</b></a>
              </div>

        {{-- Download Sample Bulk File --}}
        <div class="col-auto p-0" style="display: none;">
            <a href="{{ route('academic.department.export-file') }}" class="btn btn-warning btn-md">
                <b>Download Sample Bulk File</b>
            </a>
        </div>

        {{-- Import Sample Bulk File --}}
        <div class="col-auto" style="display: none;">
            <a href="#" class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#importModal">
                <b>Import Data</b>
            </a>
        </div>

        {{-- print-preview --}}
        <div class="col-auto p-0">
            <a href="{{route('print-preview', 'departments')}}" class="btn btn-info btn-md">
                <b>Print Preview</b>
            </a>

        </div>
        @endif

    </div>

    <!-- Import File Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('academic.department.import-file') }}" method="POST"
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
                                    <th>No</th>
                                    <th class="heading_style">Company</th>
                                    <th class="heading_style">Name</th>
                                    <th class="heading_style">Branch</th>
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
                                <h4 class="modal-title">Create Department</h4>
                                <button type="button" id="close" class="close modalclose" data-dismiss="modal">
                                    &times;
                                </button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">

                                <div class="form-group">
                                    <form id="createform">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="categorySelect" class="department_create_label">Select
                                                    Category</label>
                                                <select class="form-select select2 basic-single" id="categorySelect"
                                                    aria-label="Default select example" name="selectCategory[]"
                                                    multiple="multiple">

                                                    @foreach($categories as $item)
                                                        <option value="{{$item->id}}">{{ $item->name}}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label class="department_create_label">Select Company</label>
                                                    </div>
                                                    <select name="company_id" class="form-control company_id"
                                                        id="company_id">
                                                        <option selected disabled>Select Company</option>
                                                        @foreach($company as $item)
                                                            <option value="{{$item->id}}">{{ $item->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label class="department_create_label">Select Branch</label>
                                                    </div>
                                                    <select name="branch_id" class="form-control branch_id"
                                                        id="branch_id">
                                                        <option value="" disabled selected>Select Branch</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label class="department_create_label">Name</label>
                                                    </div>
                                                    <input type="text" required class="form-control" value="" id="name"
                                                        name="name">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row mt-5 mb-3">

                                            <div class="col-12">
                                                <div class="form-group text-right">
                                                    <input id="create-form-submit" type="submit" class="btn btn-primary"
                                                        value="Submit">
                                                    <a href="" class=" btn btn-danger modalclose ms-5"
                                                        data-dismiss="modal">Cancel </a>
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
                                <h4 class="modal-title">Edit Department</h4>
                                <button type="button" id="close" class="close modalclose" data-dismiss="modal1">
                                    &times;
                                </button>
                            </div>
                            <!-- Modal body  -->

                            <div class="modal-body">
                                <form id="editform">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="otherBranchSelect" class="department_edit_label">Select
                                                Category</label>
                                            <select class="form-select select2 basic-single" id="categorySelect_edit"
                                                aria-label="Default select example" name="selectCategory[]"
                                                multiple="multiple" required>
                                                <option value="" disabled selected>Select Category</option>

                                                @foreach($categories as $item)
                                                    <option value="{{$item->id}}">{{ $item->name}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="input-label">
                                                    <label class="department_edit_label">Name</label>
                                                </div>
                                                <input type="text" required class="form-control" id="name_edit" value=""
                                                    name="name">
                                            </div>
                                        </div>

                                    </div>

                                    {{-- <div class="row">--}}

                                        {{-- </div>--}}

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="input-label">
                                                    <label class="department_edit_label">Company</label>
                                                </div>
                                                <select name="company_id" class="form-control company_id"
                                                    id="company_id_edit">
                                                    <option value="" selected disabled>Select Company</option>
                                                    @foreach($company as $key => $item)
                                                        <option value="{{$item['id']}}" selected>{{$item['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="input-label">
                                                    <label class="department_edit_label">Branch</label>
                                                </div>
                                                <select name="branch_id" class="form-control branch_id"
                                                    id="branch_id_edit">
                                                    <option value="" disabled selected>Select Branch</option>
                                                    <input type="hidden" id="branch_value" name="branch_value">
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="id" id="edit_id" class="form-control">
                                </form>
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer justify-content-center">

                                <input id="tag-form-submit" type="submit" class="btn btn-primary" value="Submit">

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
        <style>
            .error {
                font-weight: 500;
                color: red;
            }

            .department_create_label {
                display: block;
                font-weight: 500;
                text-align: start;
            }

            .department_edit_label {
                display: block;
                font-weight: 500;
                text-align: start;
            }
        </style>
    @endsection
    @section('js')

            <script type="text/javascript">
                var tableData = null;
                $(document).ready(function () {

                    $("#main_check").on("change",function(){
                        var main_check=$(this).val();
                        if(main_check == 1){
                            $("#department_section").hide();
                        }else{
                              $("#department_section").show();
                        }
                    });
                });
                $('.select2').select2();
            </script>
        <script type="text/javascript">
            var tableData = null;
            $(document).ready(function () {
                $('.select2').select2();

                $("#createform").validate({

                    errorPlacement: function (event, element) {
                        if (element.attr("name") == "selectCategory[]") {
                            event.insertAfter("#categorySelect + .select2");
                        } else {
                            event.insertAfter(element);
                        }
                    },
                    rules: {
                        name: {
                            required: true,
                        },
                        company_id: {
                            required: true,
                        },
                        branch_id: {
                            required: true,
                        },
                        'selectCategory[]': {
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
                        branch_id: {
                            required: "Please select branch",
                        },
                        'selectCategory[]': {
                            required: "Please select category",
                        },
                    },
                });

                $("#editform").validate({

                    errorPlacement: function (event, element) {
                        if (element.attr("name") == "selectCategory[]") {
                            event.insertAfter("#categorySelect_edit + .select2");
                        } else {
                            event.insertAfter(element);
                        }
                    },
                    rules: {
                        name: {
                            required: true,
                        },
                        company_id: {
                            required: true,
                        },
                        branch_id: {
                            required: true,
                        },
                        'selectCategory[]': {
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
                        branch_id: {
                            required: "Please select branch",
                        },
                        'selectCategory[]': {
                            required: "Please select category",
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
                        "url": "{{ route('datatable.get-data-department') }}",
                        "type": "POST",
                        "data": { _token: "{{csrf_token()}}" }
                    },
                    "columns": [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'company', name: 'company' },
                        { data: 'name', name: 'name' },
                        { data: 'branch', name: 'branch' },
                        { data: 'status', name: 'status' },
                        { data: 'action', name: 'action', orderable: false, searchable: false },
                    ]
                });
            });
            $('.company_id').on('change', function (e) {

                var id = $(this).val();
                var loader = $('<div class="loader"></div>').appendTo('body');

                $.ajax({
                    type: "post",
                    "url": '{!! route('admin.departments.company.get') !!}',
                    data: {
                        id: id,
                        _token: "{{csrf_token()}}"
                    },
                    success: function (response) {
                        loader.remove();

                        $('.branch_id').html(response);
                    },
                    error: function () {
                        loader.remove();
                        toastr.error("Error");

                    }
                });
            });
            //Create Form Submit
            $('#create-form-submit').on('click', function (e) {
                e.preventDefault();
                var name = $('#name').val();
                var company_id = $('company_id').val();
                var branch_id = $('branch_id').val();
                var category_id = $('#categorySelect').val();
                var url = "{{ route('admin.departments.store') }}";


                if (!$('#createform').valid()) {
                    return false;
                }

                var loader = $('<div class="loader"></div>').appendTo('body');

                $.ajax({
                    type: "POST",
                    "url": url,
                    data: $('#createform').serialize(),
                    success: function (response) {
                        loader.remove();

                        $("#close").trigger("click");
                        $('#name').val('');
                        $('#branch_id').val('');
                        $('#company_id').val('');
                        // $('#categorySelect').val('');
                        $('#categorySelect').val(null).trigger('change');

                        tableData.ajax.reload();
                        toastr.success('Department Added successfully.');
                    },
                    error: function () {
                        loader.remove();
                        toastr.error('Please fill all the fields.');
                    }
                });
                return false;
            });

            $('#file-datatable tbody').on('click', '.department_edit', function () {

                var branch_edit = $(this).data('department-edit').branch_id;
                console.log(branch_edit);
                var id = $(this).data('department-edit').id;

                var name = $(this).data('department-edit').name;
                var company_id_edit = $(this).data('department-edit').company_id;
                var categorySelect_edit_id_edit = $(this).data('department-edit').category_id;
                console.log(categorySelect_edit_id_edit);
                $('#myModal').modal('show');
                $("#edit_id").val(id);
                $("#name_edit").val(name);
                $("#company_id_edit").val(company_id_edit);
                $('#branch_value').val(branch_edit);

                $.ajax({
                    type: "POST",
                    url: '{!! route('admin.departments.company.get') !!}',
                    data: {
                        id: company_id_edit,
                        _token: "{{csrf_token()}}",
                       
                    },
                    success: function (response) {
                        $('.branch_id').html(response);

                        $('.branch_id').val(branch_edit);
                    },
                    error: function () {
                        toastr.error('Error');
                    }
                });

                $('#categorySelect_edit').val(categorySelect_edit_id_edit);
                $('#categorySelect_edit').trigger('change');

            });

            $(".modalclose").click(function () {

                $('#myModal').modal('hide');
            });

            $(".modalclose").click(function () {

                $('#createModal1').modal('hide');
            });

            //this is edit

            $('#tag-form-submit').on('click', function (e) {

                e.preventDefault();
                var id = $('#edit_id').val();
                console.log(id);
                var url = "{{ route('admin.departments.index') }}";

                if (!$('#editform').valid()) {
                    return false;
                }

                var loader = $('<div class="loader"></div>').appendTo('body');

                $.ajax({
                    type: "POST",
                    "url": url + '/' + id,
                    data: $('#editform').serialize() + '&_method=PUT',
                    success: function (response) {


                        $('#myModal').modal('hide');
                        loader.remove();

                        tableData.ajax.reload();
                        toastr.success('Department Updated successfully.');
                    },
                    error: function () {
                        loader.remove();
                        toastr.error('Error while updating Department.');
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
                                toastr.success('Department Deleted successfully.');
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
                    url: '{{route('hr.department.change-status')}}',
                    data: {
                        id: id,
                        status: status,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (response) {
                        loader.remove();

                        console.log(response);
                        tableData.ajax.reload();
                        toastr.success('Status Updated successfully.');

                    },
                    error: function (xhr, status, error) {
                        loader.remove();
                        toastr.error('Error occurred while updating status.');
                        console.error(xhr.responseText);
                    }
                });
            });

        </script>
    @endsection
