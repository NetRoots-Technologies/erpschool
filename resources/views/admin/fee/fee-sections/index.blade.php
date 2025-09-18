@extends('admin.layouts.main')

@section('title')
Fee Sections
@stop

@section('content')
<style>
    .fee-section-style {
        float: left;
    }

    .select-checkbox {
        margin-right: 13px !important;
    }
</style>
<div class="container-fluid">
    <div class="row w-100 mt-4">
        <h3 class="text-22 text-center text-bold w-100 mb-4">Fee Sections</h3>
    </div>

    <div class="row mt-4 mb-4 justify-content-start gap-4">
        {{-- Add Fee Section --}}
        @if (Gate::allows('FeeSection-create'))
        <div class="col-auto p-0">
            <a class="btn btn-primary btn-md text-white" style="margin-left: 15px;" data-toggle="modal"
                data-target="#createModal">
                <b>Add Fee Section</b>
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
                        <table id="fee-sections-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" id="select-all" class="select-checkbox">
                                    </th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Fee Category</th>
                                    <th>Company</th>
                                    <th>Branch</th>
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
@if (Gate::allows('FeeSection-create'))
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Add Fee Section</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="create-form" method="POST" action="{{ route('admin.fee.fee-sections.store') }}">
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
                        <label for="fee_category_id">Fee Category <span class="text-danger">*</span></label>
                        <select class="form-control" id="fee_category_id" name="fee_category_id" required>
                            <option value="">Select Fee Category</option>
                            @foreach($feeCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="company_id">Company <span class="text-danger">*</span></label>
                        <select class="form-control" id="company_id" name="company_id" required>
                            <option value="">Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="branch_id">Branch <span class="text-danger">*</span></label>
                        <select class="form-control" id="branch_id" name="branch_id" required>
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
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
@if (Gate::allows('FeeSection-edit'))
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Fee Section</h5>
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
                        <label for="edit-fee_category_id">Fee Category <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit-fee_category_id" name="fee_category_id" required>
                            <option value="">Select Fee Category</option>
                            @foreach($feeCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-company_id">Company <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit-company_id" name="company_id" required>
                            <option value="">Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-branch_id">Branch <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit-branch_id" name="branch_id" required>
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
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
    var table = $('#fee-sections-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.fee.fee-sections.data') }}",
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
            {data: 'fee_category', name: 'fee_category'},
            {data: 'company', name: 'company'},
            {data: 'branch', name: 'branch'},
            {data: 'status', name: 'status'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
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
    $(document).on('click', '.fee-section-edit', function() {
        var feeSectionData = $(this).data('fee-section-edit');
        $('#edit-name').val(feeSectionData.name);
        $('#edit-description').val(feeSectionData.description);
        $('#edit-fee_category_id').val(feeSectionData.fee_category_id);
        $('#edit-company_id').val(feeSectionData.company_id);
        $('#edit-branch_id').val(feeSectionData.branch_id);
        $('#edit-form').attr('action', "{{ route('admin.fee.fee-sections.update', '') }}/" + feeSectionData.id);
        $('#editModal').modal('show');
    });

    // Status change
    $(document).on('click', '.change-status', function() {
        var id = $(this).data('id');
        var status = $(this).data('status');
        
        $.ajax({
            url: "{{ route('admin.fee.fee-sections.change-status') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                status: status
            },
            success: function(response) {
                table.ajax.reload();
                alert('Status updated successfully');
            },
            error: function(xhr) {
                alert('Error updating status');
            }
        });
    });

    // Delete form submission
    $(document).on('submit', '.delete_form', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this fee section?')) {
            var form = $(this);
            $.ajax({
                url: form.data('route'),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    table.ajax.reload();
                    alert('Fee section deleted successfully');
                },
                error: function(xhr) {
                    alert('Error deleting fee section');
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

        if (selectedIds.length > 0 && confirm('Are you sure you want to delete selected fee sections?')) {
            $.ajax({
                url: "{{ route('admin.fee.fee-sections.bulk-action') }}",
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
                    alert('Selected fee sections deleted successfully');
                },
                error: function(xhr) {
                    alert('Error deleting fee sections');
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
                alert('Fee section created successfully');
            },
            error: function(xhr) {
                alert('Error creating fee section');
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
                alert('Fee section updated successfully');
            },
            error: function(xhr) {
                alert('Error updating fee section');
            }
        });
    });
});
</script>
@endsection