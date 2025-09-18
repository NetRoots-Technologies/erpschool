@extends('admin.layouts.main')

@section('title')
Fee Structures
@stop

@section('content')
<style>
    .fee-structure-style {
        float: left;
    }

    .select-checkbox {
        margin-right: 13px !important;
    }
</style>
<div class="container-fluid">
    <div class="row w-100 mt-4">
        <h3 class="text-22 text-center text-bold w-100 mb-4">Fee Structures</h3>
    </div>

    <div class="row mt-4 mb-4 justify-content-start gap-4">
        {{-- Add Fee Structure --}}
        @if (Gate::allows('FeeStructure-create'))
        <div class="col-auto p-0">
            <a class="btn btn-primary btn-md text-white" style="margin-left: 15px;" data-toggle="modal"
                data-target="#createModal">
                <b>Add Fee Structure</b>
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
                        <table id="fee-structures-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" id="select-all" class="select-checkbox">
                                    </th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Term</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
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
@if (Gate::allows('FeeStructure-create'))
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Add Fee Structure</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="create-form" method="POST" action="{{ route('admin.fee.fee-structures.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fee_category_id">Fee Category <span class="text-danger">*</span></label>
                                <select class="form-control" id="fee_category_id" name="fee_category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fee_term_id">Fee Term <span class="text-danger">*</span></label>
                                <select class="form-control" id="fee_term_id" name="fee_term_id" required>
                                    <option value="">Select Term</option>
                                    @foreach($feeTerms as $term)
                                    <option value="{{ $term->id }}">{{ $term->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="due_date">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
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
@if (Gate::allows('FeeStructure-edit'))
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Fee Structure</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-name">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit-name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-fee_category_id">Fee Category <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit-fee_category_id" name="fee_category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-fee_term_id">Fee Term <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit-fee_term_id" name="fee_term_id" required>
                                    <option value="">Select Term</option>
                                    @foreach($feeTerms as $term)
                                    <option value="{{ $term->id }}">{{ $term->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-amount">Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit-amount" name="amount" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-due_date">Due Date</label>
                                <input type="date" class="form-control" id="edit-due_date" name="due_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-status">Status</label>
                                <select class="form-control" id="edit-status" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-description">Description</label>
                        <textarea class="form-control" id="edit-description" name="description" rows="3"></textarea>
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
    var table = $('#fee-structures-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.fee.fee-structures.index') }}",
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
            {data: 'fee_category.name', name: 'feeCategory.name'},
            {data: 'fee_term.name', name: 'feeTerm.name'},
            {
                data: 'amount',
                name: 'amount',
                render: function(data, type, row) {
                    return '$' + parseFloat(data).toFixed(2);
                }
            },
            {data: 'due_date', name: 'due_date'},
            {
                data: 'is_active',
                name: 'is_active',
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
                    @if (Gate::allows('FeeStructure-edit'))
                    actions += '<button class="btn btn-sm btn-primary edit-btn" data-id="' + row.id + '">Edit</button> ';
                    @endif
                    @if (Gate::allows('FeeStructure-delete'))
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
        $.get("{{ route('admin.fee.fee-structures.index') }}/" + id, function(data) {
            $('#edit-name').val(data.name);
            $('#edit-fee_category_id').val(data.fee_category_id);
            $('#edit-fee_term_id').val(data.fee_term_id);
            $('#edit-amount').val(data.amount);
            $('#edit-due_date').val(data.due_date);
            $('#edit-status').val(data.status);
            $('#edit-description').val(data.description);
            $('#edit-form').attr('action', "{{ route('admin.fee.fee-structures.index') }}/" + id);
            $('#editModal').modal('show');
        });
    });

    // Delete button click
    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this fee structure?')) {
            $.ajax({
                url: "{{ route('admin.fee.fee-structures.index') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    table.ajax.reload();
                    alert('Fee structure deleted successfully');
                },
                error: function(xhr) {
                    alert('Error deleting fee structure');
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

        if (selectedIds.length > 0 && confirm('Are you sure you want to delete selected fee structures?')) {
            $.ajax({
                url: "{{ route('admin.fee.fee-structures.bulk-action') }}",
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
                    alert('Selected fee structures deleted successfully');
                },
                error: function(xhr) {
                    alert('Error deleting fee structures');
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
                alert('Fee structure created successfully');
            },
            error: function(xhr) {
                alert('Error creating fee structure');
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
                alert('Fee structure updated successfully');
            },
            error: function(xhr) {
                alert('Error updating fee structure');
            }
        });
    });
});
</script>
@endsection