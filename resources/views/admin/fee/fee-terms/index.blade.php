@extends('admin.layouts.main')

@section('title')
Fee Terms
@stop

@section('content')
<style>
    .fee-term-style {
        float: left;
    }

    .select-checkbox {
        margin-right: 13px !important;
    }

    .term-status {
        font-weight: bold;
    }
</style>
<div class="container-fluid">
    <div class="row w-100 mt-4">
        <h3 class="text-22 text-center text-bold w-100 mb-4">Fee Terms</h3>
    </div>

    <div class="row mt-4 mb-4 justify-content-start gap-4">
        {{-- Add Fee Term --}}
        @if (Gate::allows('FeeTerm-create'))
        <div class="col-auto p-0">
            <a class="btn btn-primary btn-md text-white" style="margin-left: 15px;" data-toggle="modal"
                data-target="#createModal">
                <b>Add Fee Term</b>
            </a>
        </div>
        @endif

        {{-- Bulk Actions --}}
        <div class="col-auto p-0">
            <button type="button" class="btn btn-danger btn-md" id="bulk-delete-btn" style="display: none;">
                <b>Delete Selected</b>
            </button>
        </div>

        {{-- Activate/Deactivate Terms --}}
        <div class="col-auto p-0">
            <button type="button" class="btn btn-success btn-md" id="bulk-activate-btn" style="display: none;">
                <b>Activate Selected</b>
            </button>
        </div>
        <div class="col-auto p-0">
            <button type="button" class="btn btn-warning btn-md" id="bulk-deactivate-btn" style="display: none;">
                <b>Deactivate Selected</b>
            </button>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Filters</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter-academic-year">Academic Year</label>
                                <select class="form-control" id="filter-academic-year">
                                    <option value="">All Years</option>
                                    @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter-status">Status</label>
                                <select class="form-control" id="filter-status">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter-term-type">Term Type</label>
                                <select class="form-control" id="filter-term-type">
                                    <option value="">All Types</option>
                                    <option value="semester">Semester</option>
                                    <option value="quarter">Quarter</option>
                                    <option value="trimester">Trimester</option>
                                    <option value="annual">Annual</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="button" class="btn btn-primary" id="apply-filters">Apply Filters</button>
                                    <button type="button" class="btn btn-secondary" id="clear-filters">Clear Filters</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="fee-terms-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" id="select-all" class="select-checkbox">
                                    </th>
                                    <th>Name</th>
                                    <th>Academic Year</th>
                                    <th>Term Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Description</th>
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
@if (Gate::allows('FeeTerm-create'))
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Add Fee Term</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="create-form" method="POST" action="{{ route('admin.fee.fee-terms.store') }}">
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
                                <label for="academic_year_id">Academic Year <span class="text-danger">*</span></label>
                                <select class="form-control" id="academic_year_id" name="academic_year_id" required>
                                    <option value="">Select Academic Year</option>
                                    @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="term_type">Term Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="term_type" name="term_type" required>
                                    <option value="">Select Term Type</option>
                                    <option value="semester">Semester</option>
                                    <option value="quarter">Quarter</option>
                                    <option value="trimester">Trimester</option>
                                    <option value="annual">Annual</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_date">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="due_date">Due Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="due_date" name="due_date" required>
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
@if (Gate::allows('FeeTerm-edit'))
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Fee Term</h5>
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
                                <label for="edit-academic_year_id">Academic Year <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit-academic_year_id" name="academic_year_id" required>
                                    <option value="">Select Academic Year</option>
                                    @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-term_type">Term Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit-term_type" name="term_type" required>
                                    <option value="">Select Term Type</option>
                                    <option value="semester">Semester</option>
                                    <option value="quarter">Quarter</option>
                                    <option value="trimester">Trimester</option>
                                    <option value="annual">Annual</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-status">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit-status" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit-start_date">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit-start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit-end_date">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit-end_date" name="end_date" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit-due_date">Due Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit-due_date" name="due_date" required>
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
    var table = $('#fee-terms-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.fee.fee-terms.index') }}",
            type: 'GET',
            data: function(d) {
                d.academic_year_id = $('#filter-academic-year').val();
                d.status = $('#filter-status').val();
                d.term_type = $('#filter-term-type').val();
            }
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
            {data: 'academic_year.name', name: 'academicYear.name'},
            {
                data: 'term_type',
                name: 'term_type',
                render: function(data, type, row) {
                    return data.charAt(0).toUpperCase() + data.slice(1);
                }
            },
            {data: 'start_date', name: 'start_date'},
            {data: 'end_date', name: 'end_date'},
            {data: 'due_date', name: 'due_date'},
            {
                data: 'status',
                name: 'status',
                render: function(data, type, row) {
                    var badgeClass = data === 'active' ? 'badge-success' : 'badge-secondary';
                    return '<span class="badge ' + badgeClass + ' term-status">' + data.toUpperCase() + '</span>';
                }
            },
            {
                data: 'description',
                name: 'description',
                render: function(data, type, row) {
                    return data ? (data.length > 50 ? data.substring(0, 50) + '...' : data) : '';
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    var actions = '';
                    @if (Gate::allows('FeeTerm-edit'))
                    actions += '<button class="btn btn-sm btn-primary edit-btn" data-id="' + row.id + '">Edit</button> ';
                    @endif
                    @if (Gate::allows('FeeTerm-delete'))
                    actions += '<button class="btn btn-sm btn-danger delete-btn" data-id="' + row.id + '">Delete</button>';
                    @endif
                    return actions;
                }
            }
        ]
    });

    // Filter functionality
    $('#apply-filters').on('click', function() {
        table.ajax.reload();
    });

    $('#clear-filters').on('click', function() {
        $('#filter-academic-year, #filter-status, #filter-term-type').val('');
        table.ajax.reload();
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
            $('#bulk-delete-btn, #bulk-activate-btn, #bulk-deactivate-btn').show();
        } else {
            $('#bulk-delete-btn, #bulk-activate-btn, #bulk-deactivate-btn').hide();
        }
    }

    // Date validation
    $('#start_date, #edit-start_date').on('change', function() {
        var startDate = $(this).val();
        var endDateField = $(this).attr('id') === 'start_date' ? '#end_date' : '#edit-end_date';
        var dueDateField = $(this).attr('id') === 'start_date' ? '#due_date' : '#edit-due_date';
        
        $(endDateField).attr('min', startDate);
        $(dueDateField).attr('min', startDate);
    });

    $('#end_date, #edit-end_date').on('change', function() {
        var endDate = $(this).val();
        var dueDateField = $(this).attr('id') === 'end_date' ? '#due_date' : '#edit-due_date';
        
        $(dueDateField).attr('max', endDate);
    });

    // Edit button click
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.get("{{ route('admin.fee.fee-terms.index') }}/" + id, function(data) {
            $('#edit-name').val(data.name);
            $('#edit-academic_year_id').val(data.academic_year_id);
            $('#edit-term_type').val(data.term_type);
            $('#edit-status').val(data.status);
            $('#edit-start_date').val(data.start_date);
            $('#edit-end_date').val(data.end_date);
            $('#edit-due_date').val(data.due_date);
            $('#edit-description').val(data.description);
            $('#edit-form').attr('action', "{{ route('admin.fee.fee-terms.index') }}/" + id);
            $('#editModal').modal('show');
        });
    });

    // Delete button click
    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this fee term?')) {
            $.ajax({
                url: "{{ route('admin.fee.fee-terms.index') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    table.ajax.reload();
                    alert('Fee term deleted successfully');
                },
                error: function(xhr) {
                    alert('Error deleting fee term');
                }
            });
        }
    });

    // Bulk actions
    $('#bulk-delete-btn').on('click', function() {
        var selectedIds = $('.row-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length > 0 && confirm('Are you sure you want to delete selected fee terms?')) {
            $.ajax({
                url: "{{ route('admin.fee.fee-terms.bulk-delete') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: selectedIds
                },
                success: function(response) {
                    table.ajax.reload();
                    $('#select-all').prop('checked', false);
                    toggleBulkActions();
                    alert('Selected fee terms deleted successfully');
                },
                error: function(xhr) {
                    alert('Error deleting fee terms');
                }
            });
        }
    });

    $('#bulk-activate-btn').on('click', function() {
        var selectedIds = $('.row-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length > 0) {
            $.ajax({
                url: "{{ route('admin.fee.fee-terms.bulk-status') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: selectedIds,
                    status: 'active'
                },
                success: function(response) {
                    table.ajax.reload();
                    $('#select-all').prop('checked', false);
                    toggleBulkActions();
                    alert('Selected fee terms activated successfully');
                },
                error: function(xhr) {
                    alert('Error activating fee terms');
                }
            });
        }
    });

    $('#bulk-deactivate-btn').on('click', function() {
        var selectedIds = $('.row-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length > 0) {
            $.ajax({
                url: "{{ route('admin.fee.fee-terms.bulk-status') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: selectedIds,
                    status: 'inactive'
                },
                success: function(response) {
                    table.ajax.reload();
                    $('#select-all').prop('checked', false);
                    toggleBulkActions();
                    alert('Selected fee terms deactivated successfully');
                },
                error: function(xhr) {
                    alert('Error deactivating fee terms');
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
                alert('Fee term created successfully');
            },
            error: function(xhr) {
                alert('Error creating fee term');
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
                alert('Fee term updated successfully');
            },
            error: function(xhr) {
                alert('Error updating fee term');
            }
        });
    });
});
</script>
@endsection