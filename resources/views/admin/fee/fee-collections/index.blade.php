@extends('admin.layouts.main')

@section('title')
Fee Collections
@stop

@section('content')
<style>
    .fee-collection-style {
        float: left;
    }

    .select-checkbox {
        margin-right: 13px !important;
    }

    .payment-status {
        font-weight: bold;
    }
</style>
<div class="container-fluid">
    <div class="row w-100 mt-4">
        <h3 class="text-22 text-center text-bold w-100 mb-4">Fee Collections</h3>
    </div>

    <div class="row mt-4 mb-4 justify-content-start gap-4">
        {{-- Add Fee Collection --}}
        @if (Gate::allows('FeeCollection-create'))
        <div class="col-auto p-0">
            <a class="btn btn-primary btn-md text-white" style="margin-left: 15px;" data-toggle="modal"
                data-target="#createModal">
                <b>Collect Fee</b>
            </a>
        </div>
        @endif

        {{-- Generate Receipt --}}
        <div class="col-auto p-0">
            <button type="button" class="btn btn-success btn-md" id="generate-receipt-btn" style="display: none;">
                <b>Generate Receipt</b>
            </button>
        </div>

        {{-- Bulk Actions --}}
        <div class="col-auto p-0">
            <button type="button" class="btn btn-danger btn-md" id="bulk-delete-btn" style="display: none;">
                <b>Delete Selected</b>
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
                                <label for="filter-student">Student</label>
                                <select class="form-control" id="filter-student">
                                    <option value="">All Students</option>
                                    @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->roll_number }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter-status">Payment Status</label>
                                <select class="form-control" id="filter-status">
                                    <option value="">All Status</option>
                                    <option value="paid">Paid</option>
                                    <option value="partial">Partial</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter-date-from">Date From</label>
                                <input type="date" class="form-control" id="filter-date-from">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter-date-to">Date To</label>
                                <input type="date" class="form-control" id="filter-date-to">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" id="apply-filters">Apply Filters</button>
                            <button type="button" class="btn btn-secondary" id="clear-filters">Clear Filters</button>
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
                        <table id="fee-collections-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" id="select-all" class="select-checkbox">
                                    </th>
                                    <th>Receipt No</th>
                                    <th>Student</th>
                                    <th>Fee Structure</th>
                                    <th>Amount Due</th>
                                    <th>Amount Paid</th>
                                    <th>Payment Status</th>
                                    <th>Payment Date</th>
                                    <th>Payment Method</th>
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
@if (Gate::allows('FeeCollection-create'))
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Collect Fee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="create-form" method="POST" action="{{ route('admin.fee.fee-collections.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="student_id">Student <span class="text-danger">*</span></label>
                                <select class="form-control" id="student_id" name="student_id" required>
                                    <option value="">Select Student</option>
                                    @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->roll_number }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fee_structure_id">Fee Structure <span class="text-danger">*</span></label>
                                <select class="form-control" id="fee_structure_id" name="fee_structure_id" required>
                                    <option value="">Select Fee Structure</option>
                                    @foreach($feeStructures as $structure)
                                    <option value="{{ $structure->id }}" data-amount="{{ $structure->amount }}">
                                        {{ $structure->name }} - ${{ $structure->amount }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount_due">Amount Due <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="amount_due" name="amount_due" step="0.01" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount_paid">Amount Paid <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="amount_paid" name="amount_paid" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_date">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-control" id="payment_method" name="payment_method" required>
                                    <option value="">Select Method</option>
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="online">Online Payment</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Collect Fee</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Edit Modal --}}
@if (Gate::allows('FeeCollection-edit'))
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Fee Collection</h5>
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
                                <label for="edit-student_id">Student <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit-student_id" name="student_id" required>
                                    <option value="">Select Student</option>
                                    @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->roll_number }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-fee_structure_id">Fee Structure <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit-fee_structure_id" name="fee_structure_id" required>
                                    <option value="">Select Fee Structure</option>
                                    @foreach($feeStructures as $structure)
                                    <option value="{{ $structure->id }}" data-amount="{{ $structure->amount }}">
                                        {{ $structure->name }} - ${{ $structure->amount }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-amount_due">Amount Due <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit-amount_due" name="amount_due" step="0.01" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-amount_paid">Amount Paid <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit-amount_paid" name="amount_paid" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-payment_date">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit-payment_date" name="payment_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-payment_method">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit-payment_method" name="payment_method" required>
                                    <option value="">Select Method</option>
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="online">Online Payment</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-notes">Notes</label>
                        <textarea class="form-control" id="edit-notes" name="notes" rows="3"></textarea>
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
    var table = $('#fee-collections-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.fee.fee-collections.index') }}",
            type: 'GET',
            data: function(d) {
                d.student_id = $('#filter-student').val();
                d.status = $('#filter-status').val();
                d.date_from = $('#filter-date-from').val();
                d.date_to = $('#filter-date-to').val();
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
            {data: 'receipt_number', name: 'receipt_number'},
            {data: 'student.name', name: 'student.name'},
            {data: 'fee_structure.name', name: 'feeStructure.name'},
            {
                data: 'amount_due',
                name: 'amount_due',
                render: function(data, type, row) {
                    return '$' + parseFloat(data).toFixed(2);
                }
            },
            {
                data: 'amount_paid',
                name: 'amount_paid',
                render: function(data, type, row) {
                    return '$' + parseFloat(data).toFixed(2);
                }
            },
            {
                data: 'payment_status',
                name: 'payment_status',
                render: function(data, type, row) {
                    var badgeClass = '';
                    switch(data) {
                        case 'paid':
                            badgeClass = 'badge-success';
                            break;
                        case 'partial':
                            badgeClass = 'badge-warning';
                            break;
                        case 'pending':
                            badgeClass = 'badge-danger';
                            break;
                        default:
                            badgeClass = 'badge-secondary';
                    }
                    return '<span class="badge ' + badgeClass + ' payment-status">' + data.toUpperCase() + '</span>';
                }
            },
            {data: 'payment_date', name: 'payment_date'},
            {data: 'payment_method', name: 'payment_method'},
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    var actions = '';
                    actions += '<button class="btn btn-sm btn-info view-receipt-btn" data-id="' + row.id + '">Receipt</button> ';
                    @if (Gate::allows('FeeCollection-edit'))
                    actions += '<button class="btn btn-sm btn-primary edit-btn" data-id="' + row.id + '">Edit</button> ';
                    @endif
                    @if (Gate::allows('FeeCollection-delete'))
                    actions += '<button class="btn btn-sm btn-danger delete-btn" data-id="' + row.id + '">Delete</button>';
                    @endif
                    return actions;
                }
            }
        ]
    });

    // Fee structure change event
    $('#fee_structure_id, #edit-fee_structure_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var amount = selectedOption.data('amount');
        var targetField = $(this).attr('id') === 'fee_structure_id' ? '#amount_due' : '#edit-amount_due';
        $(targetField).val(amount);
    });

    // Filter functionality
    $('#apply-filters').on('click', function() {
        table.ajax.reload();
    });

    $('#clear-filters').on('click', function() {
        $('#filter-student, #filter-status, #filter-date-from, #filter-date-to').val('');
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
            $('#bulk-delete-btn, #generate-receipt-btn').show();
        } else {
            $('#bulk-delete-btn, #generate-receipt-btn').hide();
        }
    }

    // Edit button click
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.get("{{ route('admin.fee.fee-collections.index') }}/" + id, function(data) {
            $('#edit-student_id').val(data.student_id);
            $('#edit-fee_structure_id').val(data.fee_structure_id);
            $('#edit-amount_due').val(data.amount_due);
            $('#edit-amount_paid').val(data.amount_paid);
            $('#edit-payment_date').val(data.payment_date);
            $('#edit-payment_method').val(data.payment_method);
            $('#edit-notes').val(data.notes);
            $('#edit-form').attr('action', "{{ route('admin.fee.fee-collections.index') }}/" + id);
            $('#editModal').modal('show');
        });
    });

    // View receipt button click
    $(document).on('click', '.view-receipt-btn', function() {
        var id = $(this).data('id');
        window.open("{{ route('admin.fee.fee-collections.index') }}/" + id + "/receipt", '_blank');
    });

    // Delete button click
    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this fee collection?')) {
            $.ajax({
                url: "{{ route('admin.fee.fee-collections.index') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    table.ajax.reload();
                    alert('Fee collection deleted successfully');
                },
                error: function(xhr) {
                    alert('Error deleting fee collection');
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
                alert('Fee collected successfully');
            },
            error: function(xhr) {
                alert('Error collecting fee');
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
                alert('Fee collection updated successfully');
            },
            error: function(xhr) {
                alert('Error updating fee collection');
            }
        });
    });
});
</script>
@endsection