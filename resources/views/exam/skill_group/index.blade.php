@extends('admin.layouts.main')

@section('title')
    Skill Group
@stop

@section('content')
    <style>
        .branch_Style{
            float: left;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-right: 20px;
        }
    </style>
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Skill Group </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
        @if (Gate::allows('SkillGroups-create'))
            <div class="col-12 text-right">
                <a class="btn btn-primary btn-md text-white" data-toggle="modal" data-target="#createModal1"><b>Add
                        Skill Group</b></a>
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
                                    <th class="heading_style">Branch</th>
                                    <th class="heading_style">Skill Group</th>
                                    <th class="heading_style">sort</th>
                                    <th class="heading_style">User</th>
                                    <th class="heading_style">Log</th>
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
                                    <h4 class="modal-title">Create Skill Group</h4>
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
                                                        <select name="company_id" class="form-control select2 select2-selection--single company_select"
                                                                required  >
                                                            <option value=""selected disabled>Select Company</option>
                                                            @foreach($companies as $item)
                                                                <option
                                                                    value="{{$item->id}}">{{ $item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="row mt-3">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="input-label">
                                                                <label class="branch_Style"><b>Branch*</b></label>
                                                            </div>
                                                            <select name="branch_id" class="form-control select2 select2-selection--single branch_select"
                                                                    required  id="branch_id">
                                                                <option selected>Select Branch</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="input-label">
                                                                <label class="branch_Style"><b>Skill Group*</b></label>
                                                            </div>
                                                            <input type="text" required class="form-control " value=""
                                                                   id="title" name="skill_group">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="input-label">
                                                                <label class="branch_Style"><b>Sort*</b></label>
                                                            </div>
                                                            <input type="text" class="form-control" required name="sort_skill">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-5 mb-3">

                                                    <div class="col-12">
                                                        <div class="form-group text-right">
                                                            <input id="create-form-submit" type="submit"
                                                                   class="btn btn-primary"
                                                                   value="Submit">
                                                            <a href="" class=" btn btn-danger modalclose"
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
                                    <h4 class="modal-title">Edit Skill Group</h4>
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
                                                        id="company_edit">
                                                    <option value="" selected disabled>Select Company</option>
                                                    @foreach($companies as $item)
                                                        <option
                                                            value="{{$item->id}}" selected>{{ $item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label class="branch_Style"><b>Branch</b></label>
                                                    </div>
                                                    <select name="branch_id" class="form-control branch_select"
                                                            id="branch_id_edit">

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label class="branch_Style"><b>Skill Group*</b></label>
                                                    </div>
                                                    <input type="text" required class="form-control " value=""
                                                           id="group-edit" name="skill_group">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label class="branch_Style"><b>Sort</b></label>
                                                    </div>
                                                    <input type="text" class="form-control"  name="sort_skill" id="sort_edit">
                                                </div>
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
        <style>
            .error{
                color: red;
                font-size: 14px;
                font-weight: 500;
            }
        </style>
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

                    $("#createform").validate({
                        errorPlacement: function (error, element) {
                            error.insertAfter(element.parent());
                        },
                        rules: {
                            company_id: {
                                required: true
                            },
                            branch_id: {
                                required: true
                            },
                            skill_group: {
                                required: true
                            },
                            sort_skill: {
                                required: true
                            }
                        },
                        messages: {
                            company_id: {
                                required: "Company is required"
                            },
                            branch_id: {
                                required: "Branch is required"
                            },
                            skill_group: {
                                required: "Skill Group is required"
                            },
                            sort_skill: {
                                required: "Sort is required"
                            }
                        },
                    });

                    $("#editform").validate({
                        rules: {
                            company_id: {
                                required: true
                            },
                            branch_id: {
                                required: true
                            },
                            skill_group: {
                                required: true
                            },
                            sort_skill: {
                                required: true
                            }
                        },
                        messages: {
                            company_id: {
                                required: "Company is required"
                            },
                            branch_id: {
                                required: "Branch is required"
                            },
                            skill_group: {
                                required: "Skill Group is required"
                            },
                            sort_skill: {
                                required: "Sort is required"
                            }
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
                                                            url: '{{ route('exam.skill-group-bulk') }}',
                                                            type: 'POST',
                                                            data: {
                                                                ids: selectedIds,
                                                                "_token": "{{ csrf_token() }}",
                                                            },
                                                            dataType: 'json',
                                                            success: function (response) {
                                                                Swal.fire('Deleted!', 'Your data has been deleted.', 'success');
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
                            "url": "{{ route('datatable.skill-group.getData') }}",
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
                            {data: 'branch', name: 'branch'},
                            {data: 'skill_group', name: 'skill_group'},
                            {data: 'sort_skill', name: 'sort_skill'},
                            {data: 'user', name: 'user'},
                            {data: 'status', name: 'status'},
                            {data: 'created_at', name: 'created_at'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ]
                    });
                });

                //Create Form Submit
                $('#create-form-submit').on('click', function (e) {

                    e.preventDefault();
                    var name = $('#title').val();
                    var company_id = $('#company_id').val();

                    if (!$('#createform').valid())
                    {
                        return false;
                    }

                    var url = "{{ route('exam.skill_groups.store') }}";
                    $.ajax({
                        type: "post",
                        "url": url,
                        data: $('#createform').serialize(),
                        success: function (response) {

                            $("#close").trigger("click");
                            $('#createform')[0].reset();
                            // $('#title').val('');
                            // $('#company_id').val('');


                            tableData.ajax.reload();
                            toastr.success('Skill Group Added Successfully');
                        },
                        error: function () {
                            toastr.error('Error while adding Skill Group');
                        }
                    });
                    return false;
                });


                $('#file-datatable tbody').on('click', '.skill_group_edit', function () {


                    var data = $(this).data('skill_group-edit');

                    var id = $(this).data('skill_group-edit').id;
                    var name = $(this).data('skill_group-edit').skill_group;
                    var sort = $(this).data('skill_group-edit').sort_skill;
                    var company_id = $(this).data('skill_group-edit').company_id;
                    var skill_group = $(this).data('skill_group-edit').skill_group;
                    var branch_val = $(this).data('skill_group-edit').branch_id;

                    $('#myModal').modal('show');
                    $("#edit_id").val(id);
                    $("#name_edit").val(name);
                    $("#company_edit").val(company_id);
                    $('#sort_edit').val(sort);
                    $('#group-edit').val(skill_group);

                    // for branch edit
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
                                var selectbranches = branch.id == branch_val ? 'selected' : '';
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
                    var url = "{{ route('exam.skill_groups.index') }}";

                    if (!$('#editform').valid())
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
                            toastr.success('Class Updated successfully.')
                        },
                        error: function () {
                            toastr.error('Error while updating class');
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
                    var a = confirm('Are you sure you want to Delete this?');
                    if (a) {
                        $.ajax({
                            url: route,
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                _method: 'DELETE'
                            },
                            success: function (result) {
                                tableData.ajax.reload();
                                toastr.success("Class Deleted successfully.")
                            },
                            error: function (xhr, status, error) {
                                toastr.error("Error while deleting Class.")
                            }
                        });
                    }
                });

                $('#file-datatable tbody').on('click', '.change-status', function () {
                    var id = $(this).data('id');
                    var status = $(this).data('status');

                    $.ajax({
                        type: 'POST',
                        url: '{{route('exam.skillGroup.change-status')}}',
                        data: {
                            id: id,
                            status: status,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (response) {

                            console.log(response);
                            tableData.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Status updated successfully.',
                                timer: 1000,
                                showConfirmButton: false
                            });

                        },
                        error: function (xhr, status, error) {

                            console.error(xhr.responseText);
                        }
                    });
                });


                function checkAll(source) {
                    var checkboxes = $('.select-checkbox');

                    for (var i = 0; i < checkboxes.length; i++){
                        checkboxes[i].checked = source.checked;
                    }
                }

                $(document).ready(function() {
                    $('.basic-single').select2();
                });
            </script>


            <script>
                $(document).ready(function(){

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
                    }).change();


                    {{--$('.company_select').on('change', function () {--}}
                    {{--    var selectedCompanyId =  $(this).val();--}}
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
                    {{--}).change();--}}

                });


            </script>

            <script>
                $('.branch_select').on('change', function () {
                    var branch_id = $('.branch_select').val();

                    $.ajax({
                        type: 'GET',
                        url: '{{ route('academic.fetch.schools') }}',
                        data: {
                            branch_id: branch_id
                        },
                        success: function (data) {
                            var schoolTypeDropdown = $('.school_types').empty();
                            schoolTypeDropdown.append('<option value="">Select School</option>');

                            data.forEach(function (schoolType) {
                                schoolTypeDropdown.append('<option value="' + schoolType.id + '">' + schoolType.name + '</option>');
                            });
                        },
                        error: function (error) {
                            console.error('Error fetching schoolType:', error);
                        }
                    });
                });
            </script>


@endsection
