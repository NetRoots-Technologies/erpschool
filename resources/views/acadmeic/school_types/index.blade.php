@extends('admin.layouts.main')

@section('title')
    Schools
@stop

@section('content')
    <style>
        #modal_name {
            margin-right: 500px;
        }
    </style>
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> School Types</h3>
        </div>
        <div class="row    mt-4 mb-4 ">
         @if (Gate::allows('SchoolType-create'))
            <div class="col-12 text-right">
                <a class="btn btn-primary btn-md text-white" data-toggle="modal" data-target="#createModal1"><b>Add
                        School Type</b></a>
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
{{--                                    <th class="heading_style">Company</th>--}}
{{--                                    <th class="heading_style">Branch</th>--}}
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
                                    <h4 class="modal-title">Add School Type</h4>
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
                                                <div class="col-md-6">
                                                    <label id="modal_name"><b>Company*</b></label>
                                                    <select required name="company_id"
                                                            class="form-select company_select" id="companySelect"
                                                            aria-label=".form-select-lg example">
                                                        <option value="">Select Company</option>
                                                        @foreach($companies as $item)
                                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-label">
                                                        <label id="modal_name"><b>Branch*</b></label>
                                                    </div>
                                                    <select name="branch_id" class="form-control branch_select"
                                                            required id="branch_id">
                                                        <option selected>Select Branch</option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="name" id="modal_name">Name</label>
                                                        <input type="text" class="form-control" value=""
                                                               id="name" name="name">
                                                    </div>
                                                </div>

                                                <div class="modal-footer justify-content-center">

                                                    <input id="create-form-submit" type="submit"
                                                           class="btn btn-primary btn btn-md"
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
                                    <h4 class="modal-title">Edit School</h4>
                                    <button type="button" id="close" class="close modalclose" data-dismiss="modal1">
                                        &times;
                                    </button>
                                </div>

                                <!-- Modal body  -->

                                <div class="modal-body">
                                    <form id="editform">

                                        @csrf

                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <label id="modal_name"><b>Company</b></label>
                                                <select name="company_id"
                                                        class="form-select company_select" id="company_id"
                                                        aria-label=".form-select-lg example">
                                                    <option value="">Select Company</option>
                                                    @foreach($companies as $item)
                                                        <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-label">
                                                    <label class="modal_name"><b>Branch</b></label>
                                                </div>
                                                <select name="branch_id" class="form-control branch_select"
                                                        id="branch_id">
                                                    <option selected>Select Branch</option>

                                                </select>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label for="name" id="modal_name">Name</label>
                                                    </div>
                                                    <input type="text" class="form-control" id="name_edit"
                                                           value="" name="name">
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
                <style>
                    #modal_name
                    {
                           font-size: 14px;
                           font-weight: 600;
                      }
                      .error
                      {
                            color: red;
                            font-size: 14px;
                            font-weight: 600;
                      }
                 </style>
                @endsection
            @section('js')

                <script type="text/javascript">
                    var tableData = null;
                    $(document).ready(function () {

                        $("#editform").validate({
                            rules: {
                                name: {
                                    required: true
                                }
                            },
                            messages: {
                                name: {
                                    required: "Name is required."
                                }
                            }
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
                                                                url: '{{ route('academic.school-type-bulk') }}',
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

                                {
                                    extend: 'colvis',
                                    columns: ':not(:first-child)'
                                }
                            ],
                            "columnDefs": [
                                {'visible': false}
                            ],
                            ajax: {
                                "url": "{{ route('datatable.schoolTypes.getdata') }}",
                                "type": "POST",
                                "data": {_token: "{{csrf_token()}}"}
                            },
                            "columns": [

                                {
                                    data: "checkbox",
                                    render: function (data, type, row) {
                                        return '<input type="checkbox" value="' + row.id + '" class="select-checkbox">'
                                    },
                                    orderable: false, searchable: false
                                },
                                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                                // {data: 'company', name: 'company'},
                                // {data: 'branch', name: 'branch'},
                                {data: 'name', name: 'name'},
                                {data: 'status', name: 'status'},
                                {data: 'action', name: 'action', orderable: false, searchable: false},
                            ]
                        });
                    });

                    //Create Form Submit
                    $('#create-form-submit').on('click', function (e) {

                        e.preventDefault();
                        var name = $('#name').val();

                        if (!name)
                        {
                            toastr.error("Please enter school type name");
                            return false;
                        }

                        var url = "{{ route('academic.schools.store') }}";
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
                               toastr.success('School Added successfully.');
                            },
                            error: function () {
                                toastr.error('Please fill the school name');
                            }
                        });
                        return false;
                    });


                    $('#file-datatable tbody').on('click', '.school_edit', function () {

                        let id = $(this).data('school-edit').id;
                        let name = $(this).data('school-edit').name;
                        let company_id = $(this).data('school-edit').company_id;
                        let branch_id = $(this).data('school-edit').branch_id;
                        console.log(company_id);
                        $('#myModal').modal('show');
                        $("#edit_id").val(id);
                        $("#name_edit").val(name);
                        $("#company_id").val(company_id);

                        $.ajax({
                            type: 'GET',
                            url: '{{ route('hr.fetch.branches') }}',
                            data: {
                                companyid: company_id
                            },
                            success: function (data) {
                                var branchesDropdown = $('.branch_select').empty();

                                branchesDropdown.append('<option value="">Select Branch</option>');

                                data.forEach(function (branch) {
                                    var selectbranches = branch.id == branch_id ? 'selected' : '';
                                    branchesDropdown.append('<option value="' + branch.id + '" ' + selectbranches + '>' + branch.name + '</option>');
                                });
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
                        var url = "{{ route('academic.schools.index') }}";

                        if(!$("#editform").valid())
                        {
                            return false;
                        }

                        $.ajax({
                            type: "post",
                            "url": url + '/' + id,
                            data: $('#editform').serialize() + '&_method=PUT',
                            success: function (response) {
                                $('#myModal').modal('hide');
                                tableData.ajax.reload();
                                toastr.success('School Updated successfully.');
                            },
                            error: function () {
                                toastr.error('Error while updating school');
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
                                    toastr.success('School Deleted successfully.')
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
                            url: '{{route('academic.school.change-status')}}',
                            data: {
                                id: id,
                                status: status,
                                _token: '{{ csrf_token() }}',
                            },
                            success: function (response) {

                                console.log(response);
                                tableData.ajax.reload();
                                toastr.success('Status Updated successfully.')
                            },
                            error: function (xhr, status, error) {
                                toastr.error(xhr.responseText);
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

                        $('.company_select').on('change', function () {
                            var selectedCompanyId = $(this).val();
                            $.ajax({
                                type: 'GET',
                                url: '{{ route('hr.fetch.branches') }}',
                                data: {
                                    companyid: selectedCompanyId
                                },
                                success: function (data) {
                                    var branchesDropdown = $('.branch_select').empty();

                                    branchesDropdown.append('<option value="">Select Branch</option>');

                                    data.forEach(function (branch) {
                                        branchesDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                                    });
                                },
                                error: function (error) {
                                    console.error('Error fetching branches:', error);
                                }
                            });
                        });
                    });
                </script>
@endsection
