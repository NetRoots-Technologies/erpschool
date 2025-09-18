@extends('admin.layouts.main')

@section('title')
Student Challans
@stop

@section('content')
<style>
    .challan-style {
        float: left;
    }

    .select-checkbox {
        margin-right: 13px !important;
    }

    .status-badge {
        font-weight: bold;
    }
</style>
<div class="container-fluid">
    <div class="row w-100 mt-4">
        <h3 class="text-22 text-center text-bold w-100 mb-4">Student Challans</h3>
    </div>

    <div class="row mt-4 mb-4 justify-content-start gap-4">
        {{-- Generate Challan --}}
        @if (Gate::allows('FeeVouchers-create'))
        <div class="col-auto p-0">
            <a class="btn btn-primary btn-md text-white" style="margin-left: 15px;" data-toggle="modal"
                data-target="#generateChallanModal">
                <b>Generate Challan</b>
            </a>
        </div>
        @endif

        {{-- Bulk Generate --}}
        @if (Gate::allows('FeeVouchers-create'))
        <div class="col-auto p-0">
            <a class="btn btn-success btn-md text-white" data-toggle="modal"
                data-target="#bulkGenerateModal">
                <b>Bulk Generate</b>
            </a>
        </div>
        @endif
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
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->roll_no }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter-status">Status</label>
                                <select class="form-control" id="filter-status">
                                    <option value="">All Status</option>
                                    <option value="generated">Generated</option>
                                    <option value="issued">Issued</option>
                                    <option value="paid">Paid</option>
                                    <option value="expired">Expired</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter-company">Company</label>
                                <select class="form-control" id="filter-company">
                                    <option value="">All Companies</option>
                                    @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter-branch">Branch</label>
                                <select class="form-control" id="filter-branch">
                                    <option value="">All Branches</option>
                                    @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary" id="apply-filters">Apply Filters</button>
                            <button type="button" class="btn btn-secondary" id="clear-filters">Clear Filters</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-bordered" id="challans-table">
                        <thead>
                            <tr>
                                <th>Voucher Number</th>
                                <th>Student Name</th>
                                <th>Roll No</th>
                                <th>Total Amount</th>
                                <th>Net Amount</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Generate Challan Modal --}}
@if (Gate::allows('FeeVouchers-create'))
<div class="modal fade" id="generateChallanModal" tabindex="-1" role="dialog" aria-labelledby="generateChallanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateChallanModalLabel">Generate Student Challan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="generateChallanForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="student_id">Student <span class="text-danger">*</span></label>
                                <select class="form-control" id="student_id" name="student_id" required>
                                    <option value="">Select Student</option>
                                    @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->roll_no }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fee_collection_id">Fee Collection <span class="text-danger">*</span></label>
                                <select class="form-control" id="fee_collection_id" name="fee_collection_id" required>
                                    <option value="">Select Fee Collection</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="fee-details" style="display: none;">
                        <h6>Fee Details:</h6>
                        <div id="fee-breakdown"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Generate Challan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Bulk Generate Modal --}}
@if (Gate::allows('FeeVouchers-create'))
<div class="modal fade" id="bulkGenerateModal" tabindex="-1" role="dialog" aria-labelledby="bulkGenerateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkGenerateModalLabel">Bulk Generate Challans</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="bulkGenerateForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="bulk_fee_collection_id">Fee Collection <span class="text-danger">*</span></label>
                                <select class="form-control" id="bulk_fee_collection_id" name="fee_collection_id" required>
                                    <option value="">Select Fee Collection</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Students <span class="text-danger">*</span></label>
                                <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="select-all-students">
                                        <label class="form-check-label" for="select-all-students">
                                            <strong>Select All</strong>
                                        </label>
                                    </div>
                                    <hr>
                                    @foreach($students as $student)
                                    <div class="form-check">
                                        <input class="form-check-input student-checkbox" type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="student-{{ $student->id }}">
                                        <label class="form-check-label" for="student-{{ $student->id }}">
                                            {{ $student->name }} ({{ $student->roll_no }})
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Generate Challans</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@section('js')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#challans-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.fee.challans.data") }}',
            data: function(d) {
                d.student_id = $('#filter-student').val();
                d.status = $('#filter-status').val();
                d.company_id = $('#filter-company').val();
                d.branch_id = $('#filter-branch').val();
            },
            beforeSend: function(xhr) {
                var token = $('meta[name="csrf-token"]').attr('content');
                xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        columns: [
            { data: 'voucher_number', name: 'voucher_number' },
            { data: 'student_name', name: 'student_name' },
            { data: 'student_roll_no', name: 'student_roll_no' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'net_amount', name: 'net_amount' },
            { data: 'issue_date', name: 'issue_date' },
            { data: 'due_date', name: 'due_date' },
            { data: 'status_badge', name: 'status', orderable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']]
    });

    // Apply filters
    $('#apply-filters').click(function() {
        table.draw();
    });

    // Clear filters
    $('#clear-filters').click(function() {
        $('#filter-student, #filter-status, #filter-company, #filter-branch').val('');
        table.draw();
    });

    // Load fee collections when student is selected
    $('#student_id').change(function() {
        var studentId = $(this).val();
        if (studentId) {
            $.get('{{ route("admin.fee.challans.student-collections", ":id") }}'.replace(':id', studentId))
                .done(function(data) {
                    var options = '<option value="">Select Fee Collection</option>';
                    $.each(data, function(index, collection) {
                        options += '<option value="' + collection.id + '">' + 
                                  collection.title + ' - Rs. ' + collection.total_amount + '</option>';
                    });
                    $('#fee_collection_id').html(options);
                });
        } else {
            $('#fee_collection_id').html('<option value="">Select Fee Collection</option>');
        }
    });

    // Generate challan form submission
    $('#generateChallanForm').submit(function(e) {
        e.preventDefault();
        
        $.post('{{ route("admin.fee.challans.generate") }}', $(this).serialize())
            .done(function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#generateChallanModal').modal('hide');
                    table.draw();
                } else {
                    alert(response.message);
                }
            })
            .fail(function() {
                alert('Error generating challan. Please try again.');
            });
    });

    // Bulk generate form submission
    $('#bulkGenerateForm').submit(function(e) {
        e.preventDefault();
        
        var selectedStudents = $('.student-checkbox:checked').length;
        if (selectedStudents === 0) {
            alert('Please select at least one student.');
            return;
        }
        
        $.post('{{ route("admin.fee.challans.bulk-generate") }}', $(this).serialize())
            .done(function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#bulkGenerateModal').modal('hide');
                    table.draw();
                } else {
                    alert(response.message);
                }
            })
            .fail(function() {
                alert('Error generating challans. Please try again.');
            });
    });

    // Select all students checkbox
    $('#select-all-students').change(function() {
        $('.student-checkbox').prop('checked', $(this).is(':checked'));
    });

    // Load fee collections for bulk generate
    $('#bulk_fee_collection_id').change(function() {
        // You can add logic here to filter students based on fee collection if needed
    });

    // Load all fee collections for bulk generate modal
    $.get('{{ route("admin.fee.fee-collections.index") }}')
        .done(function(data) {
            // This would need to be adjusted based on your fee collections API
            // For now, we'll populate it when the modal is opened
        });
});
</script>
@endsection