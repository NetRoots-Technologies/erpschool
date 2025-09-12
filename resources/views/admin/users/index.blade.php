@extends('admin.layouts.main')
@section('title')
    Users
@stop
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .text-danger {
        color: red;
        font-size: 13px;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row w-100 mt-4">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Users </h3>
        </div>
        <div class="row mt-4 mb-4">
            @if (Gate::allows('Users-create'))
                <div class="col-12 text-right">
                    <a data-toggle="modal" data-target="#createModal1" class="btn btn-primary btn-md text-white">Create Users</a>
                </div>
            @endif
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="file-datatable"
                                   class="border-top-0 table table-bordered text-nowrap key-buttons border-bottom">
                                <thead>
                                <tr>
                                    <th class="heading_style">No</th>
                                    <th class="heading_style">Name</th>
                                    <th class="heading_style">Email</th>
                                    <th class="heading_style">Verified</th>
                                    <th class="heading_style">Status</th>
                                    <th class="heading_style">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal for create --}}
        <div class="modal" id="createModal1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Create</h4>
                        <button type="button" id="close" class="close modalclose" data-dismiss="modal">×</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group">
                            <form action="{!! route('users.store') !!}" enctype="multipart/form-data"
                                  id="form_validation" autocomplete="off" method="post">
                                @csrf
                                <div class="form-group">
                                    <div class="input-label">
                                        <label>Role</label>
                                    </div>
                                    <select required id="role" name="role_id[]" class="form-control select2" multiple>
                                        <option value="">Select Role</option>
                                        @foreach($roles as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label class="department_create_label">Select Company</label>
                                            </div>
                                            <select name="company_id" class="form-control company_id"
                                                    id="company_id" required>
                                                <option selected disabled>Select Company</option>
                                                @foreach($companies as $item)
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
                                                    id="branch_id" required>
                                                <option value="" disabled selected>Select Branch</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label class="department_create_label">Select Department</label>
                                            </div>
                                            <select name="department_id" class="form-control department_id"
                                                    id="department_id">
                                                <option value="" disabled selected>Select Department</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Email</label>
                                            </div>
                                            <input type="email" required class="form-control" value="" name="email">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Name</label>
                                            </div>
                                            <input type="text" required class="form-control" value="" name="name">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Password</label>
                                            </div>
                                            <input type="password" required class="form-control" value=""
                                                   name="password">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Confirm Password</label>
                                            </div>
                                            <input type="password" required class="form-control" value=""
                                                   name="password_confirmation">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Profile</label>
                                            </div>
                                            <input type="file" id="profile" class="form-control dropify" value=""
                                                   name="profile">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5 mb-3">
                                    <div class="col-12">
                                        <div class="form-group text-right">
                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                            <a href="{!! route('users.index') !!}"
                                               class="btn btn-sm btn-danger">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal for edit --}}
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Edit</h4>
                        <button type="button" id="close" class="close modalclose" data-dismiss="modal">×</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group">
                            <form id="editform" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="form-group">
                                    <div class="input-label">
                                        <label>Role</label>
                                    </div>
                                    <select name="role_id[]" id="role_id_edit" multiple class="form-control select2" required>
                                        <option value="">Select Roles</option>
                                        @foreach($roles as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label class="department_create_label">Select Company</label>
                                            </div>
                                            <select name="company_id" class="form-control company_id"
                                                    id="company_id_edit" required>
                                                <option value="" disabled selected>Select Company</option>
                                                @foreach($companies as $item)
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
                                                    id="branch_id_edit" required>
                                                <option value="" disabled selected>Select Branch</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label class="department_create_label">Select Department</label>
                                            </div>
                                            <select name="department_id" class="form-control department_id"
                                                    id="department_id_edit" required>
                                                <option value="" disabled selected>Select Department</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Email</label>
                                            </div>
                                            <input type="email" id="email_edit" required class="form-control" value=""
                                                   name="email">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Name</label>
                                            </div>
                                            <input type="text" id="name_edit" required class="form-control" value=""
                                                   name="name">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Password</label>
                                                <small class="text-muted">(Leave Empty to Keep Old Password)</small>
                                            </div>
                                            <input type="password" class="form-control" value=""
                                                   name="password">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Confirm Password</label>
                                            </div>
                                            <input type="password" class="form-control" value=""
                                                   name="password_confirmation">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id" id="edit_id" class="form-control">
                                <div class="row mt-5 mb-3">
                                    <div class="col-12">
                                        <div class="form-group text-right">
                                            <input id="tag-form-submit" type="submit" class="btn btn-primary btn-sm"
                                                   value="Submit">
                                            <a class="btn btn-sm btn-danger modalclose">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@stop
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
'use strict';

var tableData;
$(document).ready(function () {
    // Initialize Select2 for role fields
    $("#role").select2({
        placeholder: "Select roles",
        allowClear: true,
        width: '100%'
    });
    $("#role_id_edit").select2({
        placeholder: "Select roles",
        allowClear: true,
        width: '100%'
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
            "url": "{{ route('datatable.get-data-user') }}",
            "type": "POST",
            "data": {_token: "{{csrf_token()}}"}
        },
        "columns": [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'email_verified_at', name: 'verified'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});

$(document).on("submit", "#form_validation", function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    // Ensure roles are included in formData
    var selectedRoles = $("#role").val();
    if (selectedRoles) {
        selectedRoles.forEach(function(role) {
            formData.append('role_id[]', role);
        });
    }
    $('.text-danger').remove(); // clear previous errors

    $.ajax({
        url: "{!! route('users.store') !!}",
        type: 'POST',
        data: formData,
        success: function (data) {
            if (data.status === 'success') {
                $("#close").trigger("click");
                tableData.ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    timer: 1000,
                    showConfirmButton: false
                });
            }
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    let input = $('[name="' + key + '"]');
                    input.after('<span class="text-danger">' + value[0] + '</span>');
                });
            } else {
                toastr.error('Something went wrong!');
            }
        },
        cache: false,
        contentType: false,
        processData: false
    });
});

$(".modalclose").click(function () {
    $('#createModal1').modal('hide');
    $('#myModal').modal('hide');
    // Clear Select2 selections
    $("#role").val(null).trigger('change');
    $("#role_id_edit").val(null).trigger('change');
});

$('#file-datatable tbody').on('click', '.user_edit', function () {
    console.log('Edit button clicked');
    var userData = $(this).data('user-edit');
    console.log('User data:', userData);

    var id = userData.id;
    var name = userData.name;
    var email = userData.email;
    var role_id = userData.role_id;
    var company_id = userData.company_id;
    var branch_id = userData.branch_id;
    var department_id = userData.department_id;

    $('#myModal').modal('show');
    $("#edit_id").val(id);
    $("#name_edit").val(name);
    $("#email_edit").val(email);

    // Handle roles
    var roleValues = [];
    if (role_id) {
        console.log('Raw role_id:', role_id, 'Type:', typeof role_id);
        if (Array.isArray(role_id)) {
            roleValues = role_id.map(String);
        } else if (typeof role_id === 'string') {
            try {
                const parsed = JSON.parse(role_id);
                if (Array.isArray(parsed)) {
                    roleValues = parsed.map(String);
                } else {
                    roleValues = [String(parsed)];
                }
            } catch (e) {
                if (role_id.includes(',')) {
                    roleValues = role_id.split(',').map(item => item.trim());
                } else {
                    roleValues = [role_id.trim()];
                }
            }
        } else if (typeof role_id === 'number') {
            roleValues = [String(role_id)];
        } else if (typeof role_id === 'object' && role_id !== null) {
            if (role_id.hasOwnProperty('id')) {
                roleValues = [String(role_id.id)];
            } else {
                roleValues = Object.values(role_id).map(String);
            }
        }
    }

    console.log('Processed role values:', roleValues);

    // Set roles in Select2
    $("#role_id_edit").val(null).trigger('change');
    setTimeout(function() {
        $("#role_id_edit").val(roleValues).trigger('change');
        console.log('Selected roles after setting:', $("#role_id_edit").val());
    }, 100);

    // Load company, branch, and department data
    loadEditFormData(company_id, branch_id, department_id);
});

function loadEditFormData(companyId, branchId, departmentId) {
    // Set company
    $("#company_id_edit").val(companyId);

    // Load branches for the selected company
    if (companyId) {
        $.ajax({
            type: "post",
            url: '{!! route('admin.departments.company.get') !!}',
            data: {
                id: companyId,
                _token: "{{csrf_token()}}"
            },
            success: function (response) {
                $('#branch_id_edit').html(response);
                $("#branch_id_edit").val(branchId);

                // Load departments for the selected branch
                if (branchId) {
                    $.ajax({
                        type: "post",
                        url: '{!! route('admin.departments.branch.get') !!}',
                        data: {
                            branch_id: branchId,
                            company_id: companyId,
                            _token: "{{csrf_token()}}"
                        },
                        success: function (response) {
                            $('#department_id_edit').html(response);
                            $("#department_id_edit").val(departmentId);
                        },
                        error: function () {
                            toastr.error("Error loading departments");
                        }
                    });
                }
            },
            error: function () {
                toastr.error("Error loading branches");
            }
        });
    }
}

$(document).on('change', '#company_id_edit', function (e) {
    var id = $(this).val();
    var loader = $('<div class="loader"></div>').appendTo('body');

    // Reset branch and department dropdowns
    $('#branch_id_edit').html('<option value="" disabled selected>Select Branch</option>');
    $('#department_id_edit').html('<option value="" disabled selected>Select Department</option>');

    $.ajax({
        type: "post",
        url: '{!! route('admin.departments.company.get') !!}',
        data: {
            id: id,
            _token: "{{csrf_token()}}"
        },
        success: function (response) {
            loader.remove();
            $('#branch_id_edit').html(response);
        },
        error: function () {
            loader.remove();
            toastr.error("Error loading branches");
        }
    });
});

$(document).on('change', '#branch_id_edit', function (e) {
    console.log('branch_id_edit changed');
    var branch_id = $(this).val();
    var company_id = $('#company_id_edit').val();
    var loader = $('<div class="loader"></div>').appendTo('body');

    // Reset department dropdown
    $('#department_id_edit').html('<option value="" disabled selected>Select Department</option>');

    $.ajax({
        type: "post",
        url: '{!! route('admin.departments.branch.get') !!}',
        data: {
            branch_id: branch_id,
            company_id: company_id,
            _token: "{{csrf_token()}}"
        },
        success: function (response) {
            loader.remove();
            $('#department_id_edit').html(response);
        },
        error: function () {
            loader.remove();
            toastr.error("Error loading departments");
        }
    });
});

$('#tag-form-submit').on('click', function (e) {
    e.preventDefault();
    const id = $('#edit_id').val();
    const uri = @json(route('users.index'));

    // Get selected roles
    var selectedRoles = $("#role_id_edit").val();
    console.log('Submitting with roles:', selectedRoles);

    // Serialize form data
    var formData = $('#editform').serialize();
    console.log('Form data:', formData);

    $.ajax({
        type: $('#editform').attr('method'),
        url: uri + '/' + id,
        data: formData,
        success: function (response) {
            console.log('Update response:', response);
            $('#myModal').modal('hide');
            tableData.ajax.reload();
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'User Updated successfully.',
                timer: 1000,
                showConfirmButton: false
            });
        },
        error: function (xhr) {
            console.error('Update error:', xhr.responseJSON);
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    let input = $('[name="' + key + '"]');
                    input.after('<span class="text-danger">' + value[0] + '</span>');
                });
            } else {
                toastr.error('Something went wrong!');
            }
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
            },
            success: function (result) {
                tableData.ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'User Deleted successfully.',
                    timer: 1000,
                    showConfirmButton: false
                });
            }
        });
    }
});

$('.company_id').on('change', function (e) {
    var id = $(this).val();
    var loader = $('<div class="loader"></div>').appendTo('body');

    $.ajax({
        type: "post",
        url: '{!! route('admin.departments.company.get') !!}',
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

$('.branch_id').on('change', function (e) {
    console.log('branch_id changed');
    var branch_id = $(this).val();
    var company_id = $('.company_id').val();
    var loader = $('<div class="loader"></div>').appendTo('body');

    $.ajax({
        type: "post",
        url: '{!! route('admin.departments.branch.get') !!}',
        data: {
            branch_id: branch_id,
            company_id: company_id,
            _token: "{{csrf_token()}}"
        },
        success: function (response) {
            loader.remove();
            $('.department_id').html(response);
        },
        error: function () {
            loader.remove();
            toastr.error("Error");
        }
    });
});
</script>
@endsection
