@extends('admin.layouts.main')

@section('title')
Fee Categories
@stop

@section('content')
<style>
    .fee-category-style {
        float: left;
    }

    .select-checkbox {
        margin-right: 13px !important;
    }
</style>
<div class="container-fluid">
    <div class="row w-100 mt-4">
        <h3 class="text-22 text-center text-bold w-100 mb-4">Fee Categories</h3>
    </div>

    <div class="row mt-4 mb-4 justify-content-start gap-4">
        {{-- Add Fee Category --}}
        @if (Gate::allows('FeeCategory-create'))
        <div class="col-auto p-0">
            <a class="btn btn-primary btn-md text-white" style="margin-left: 15px;" data-toggle="modal"
                data-target="#createModal">
                <b>Add Fee Category</b>
            </a>
        </div>
        @endif

        {{-- Bulk Actions --}}
        <div class="col-auto p-0">
            <button type="button" class="btn btn-danger btn-md" id="bulk-delete-btn" style="display: none;">
                <b>Delete Selected</b>
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="fee-categories-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" id="select-all" class="select-checkbox">
                                    </th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Create Modal --}}
@if (Gate::allows('FeeCategory-create'))
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Add Fee Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="create-form" method="POST" action="{{ route('admin.fee.fee-categories.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Edit Modal --}}
@if (Gate::allows('FeeCategory-edit'))
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Fee Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-description">Description</label>
                        <textarea class="form-control" id="edit-description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit-status">Status</label>
                        <select class="form-control" id="edit-status" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#fee-categories-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.fee.fee-categories.index') }}",
            type: 'GET'
        },
        columns: [
            {
                data: 'id',
                name: 'id',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return '<input type="checkbox" class="row-checkbox" value="' + data + '">';
                }
            },
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
            {
                data: 'status',
                name: 'status',
                render: function(data, type, row) {
                    return data == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
                }
            },
            {data: 'created_at', name: 'created_at'},
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    var actions = '';
                    @if (Gate::allows('FeeCategory-edit'))
                    actions += '<button class="btn btn-sm btn-primary edit-btn" data-id="' + row.id + '">Edit</button> ';
                    @endif
                    @if (Gate::allows('FeeCategory-delete'))
                    actions += '<button class="btn btn-sm btn-danger delete-btn" data-id="' + row.id + '">Delete</button>';
                    @endif
                    return actions;
                }
            }
        ]
    });

    // Select all checkbox
    $('#select-all').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
        toggleBulkActions();
    });

    // Individual checkbox
    $(document).on('change', '.row-checkbox', function() {
        toggleBulkActions();
    });

    function toggleBulkActions() {
        var checkedCount = $('.row-checkbox:checked').length;
        if (checkedCount > 0) {
            $('#bulk-delete-btn').show();
        } else {
            $('#bulk-delete-btn').hide();
        }
    }

    // Edit button click
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.get("{{ route('admin.fee.fee-categories.index') }}/" + id, function(data) {
            $('#edit-name').val(data.name);
            $('#edit-description').val(data.description);
            $('#edit-status').val(data.status);
            $('#edit-form').attr('action', "{{ route('admin.fee.fee-categories.index') }}/" + id);
            $('#editModal').modal('show');
        });
    });

    // Delete button click
    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this fee category?')) {
            $.ajax({
                url: "{{ route('admin.fee.fee-categories.index') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    table.ajax.reload();
                    alert('Fee category deleted successfully');
                },
                error: function(xhr) {
                    alert('Error deleting fee category');
                }
            });
        }
    });

    // Bulk delete
    $('#bulk-delete-btn').on('click', function() {
        var selectedIds = [];
        $('.row-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length > 0 && confirm('Are you sure you want to delete selected fee categories?')) {
            $.ajax({
                url: "{{ route('admin.fee.fee-categories.bulk-action') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    action: 'delete',
                    ids: selectedIds
                },
                success: function(response) {
                    table.ajax.reload();
                    $('#select-all').prop('checked', false);
                    toggleBulkActions();
                    alert('Selected fee categories deleted successfully');
                },
                error: function(xhr) {
                    alert('Error deleting fee categories');
                }
            });
        }
    });

    // Form submissions
    $('#create-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#createModal').modal('hide');
                table.ajax.reload();
                $('#create-form')[0].reset();
                alert('Fee category created successfully');
            },
            error: function(xhr) {
                alert('Error creating fee category');
            }
        });
    });

    $('#edit-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                $('#editModal').modal('hide');
                table.ajax.reload();
                alert('Fee category updated successfully');
            },
            error: function(xhr) {
                alert('Error updating fee category');
            }
        });
    });
});
</script>
@endsection