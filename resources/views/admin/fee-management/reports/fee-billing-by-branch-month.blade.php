@extends('admin.layouts.main')

@section('title', 'Fee Billing Total Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-3">
        <div class="col-12">
            <h4 class="page-title">Fee Billing Total Report</h4>
            <p class="text-muted">View total fee billing summary by branch and month</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="filterForm" class="row g-3" onsubmit="return false;">
                        <div class="col-md-4">
                            <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                            <select class="form-control" id="branch_id" required>
                                <option value="">Select Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filter_month" class="form-label">Billing Month <span class="text-danger">*</span></label>
                            <input type="month" class="form-control" id="filter_month" value="{{ date('Y-m') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="button" id="applyFilters" class="btn btn-primary">Generate Report</button>
                                <button type="button" id="resetFilters" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-3" id="summarySection" style="display: none;">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Bills</div>
                            <div class="h4 mb-0" id="totalBills">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Billed Amount</div>
                            <div class="h5 mb-0" id="totalBilled">Rs. 0.00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Paid Amount</div>
                            <div class="h5 mb-0" id="totalPaid">Rs. 0.00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Outstanding</div>
                            <div class="h5 mb-0" id="totalOutstanding">Rs. 0.00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Breakdown -->
    <div class="row mb-3" id="statusSection" style="display: none;">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="text-muted small mb-2">Paid Bills</div>
                    <div class="h3 text-success mb-0" id="paidBills">0</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="text-muted small mb-2">Partially Paid Bills</div>
                    <div class="h3 text-warning mb-0" id="partialBills">0</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="text-muted small mb-2">Pending Bills</div>
                    <div class="h3 text-danger mb-0" id="pendingBills">0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Class-wise Breakdown -->
    <div class="row" id="classBreakdownSection" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Class-wise Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="classBreakdownTable">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th class="text-right">Total Bills</th>
                                    <th class="text-right">Total Billed</th>
                                    <th class="text-right">Total Paid</th>
                                    <th class="text-right">Outstanding</th>
                                </tr>
                            </thead>
                            <tbody id="classBreakdownBody">
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th class="text-right"><strong>Grand Total:</strong></th>
                                    <th class="text-right" id="footer_total_bills"><strong>0</strong></th>
                                    <th class="text-right" id="footer_total_billed"><strong>0.00</strong></th>
                                    <th class="text-right" id="footer_total_paid"><strong>0.00</strong></th>
                                    <th class="text-right" id="footer_total_outstanding"><strong>0.00</strong></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .border-left-primary {
        border-left: 4px solid #007bff;
    }
    .border-left-success {
        border-left: 4px solid #28a745;
    }
    .border-left-info {
        border-left: 4px solid #17a2b8;
    }
    .border-left-warning {
        border-left: 4px solid #ffc107;
    }
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }
    .table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    .table tbody td {
        vertical-align: middle;
    }
</style>
@endsection

@section('js')
<script>
$(document).ready(function() {
    $('#applyFilters').click(function(e) {
        e.preventDefault();
        
        var branchId = $('#branch_id').val();
        var month = $('#filter_month').val();
        
        if (!branchId) {
            alert('Please select a branch');
            $('#branch_id').focus();
            return false;
        }
        
        if (!month) {
            alert('Please select a billing month');
            $('#filter_month').focus();
            return false;
        }
        
        // Show loading
        $('#applyFilters').prop('disabled', true).text('Loading...');
        
        // Make AJAX request
        $.ajax({
            url: "{{ route('admin.fee-management.reports.fee-billing-by-branch-month') }}",
            type: 'GET',
            data: {
                branch_id: branchId,
                filter_month: month
            },
            success: function(response) {
                if (response.success) {
                    // Update summary cards
                    $('#totalBills').text(response.summary.total_bills);
                    $('#totalBilled').text('Rs. ' + parseFloat(response.summary.total_billed).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $('#totalPaid').text('Rs. ' + parseFloat(response.summary.total_paid).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $('#totalOutstanding').text('Rs. ' + parseFloat(response.summary.total_outstanding).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    
                    // Update status breakdown
                    $('#paidBills').text(response.summary.paid_bills);
                    $('#partialBills').text(response.summary.partial_bills);
                    $('#pendingBills').text(response.summary.pending_bills);
                    
                    // Update class breakdown
                    var tbody = $('#classBreakdownBody');
                    tbody.empty();
                    
                    var totalBills = 0;
                    var totalBilled = 0;
                    var totalPaid = 0;
                    var totalOutstanding = 0;
                    
                    if (response.class_breakdown && response.class_breakdown.length > 0) {
                        response.class_breakdown.forEach(function(item) {
                            var row = '<tr>' +
                                '<td>' + item.class_name + '</td>' +
                                '<td class="text-right">' + item.total_bills + '</td>' +
                                '<td class="text-right">' + parseFloat(item.total_billed).toLocaleString('en-US', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) + '</td>' +
                                '<td class="text-right">' + parseFloat(item.total_paid).toLocaleString('en-US', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) + '</td>' +
                                '<td class="text-right">' + parseFloat(item.total_outstanding).toLocaleString('en-US', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) + '</td>' +
                                '</tr>';
                            tbody.append(row);
                            
                            totalBills += parseInt(item.total_bills);
                            totalBilled += parseFloat(item.total_billed);
                            totalPaid += parseFloat(item.total_paid);
                            totalOutstanding += parseFloat(item.total_outstanding);
                        });
                    } else {
                        tbody.append('<tr><td colspan="5" class="text-center">No data available</td></tr>');
                    }
                    
                    // Update footer totals
                    $('#footer_total_bills').text(totalBills);
                    $('#footer_total_billed').text(totalBilled.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $('#footer_total_paid').text(totalPaid.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $('#footer_total_outstanding').text(totalOutstanding.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    
                    // Show sections
                    $('#summarySection').show();
                    $('#statusSection').show();
                    $('#classBreakdownSection').show();
                } else {
                    alert(response.message || 'Error loading report');
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                alert('Error loading report. Please try again.');
            },
            complete: function() {
                $('#applyFilters').prop('disabled', false).text('Generate Report');
            }
        });
        
        return false;
    });
    
    $('#resetFilters').click(function() {
        $('#branch_id').val('');
        $('#filter_month').val('{{ date('Y-m') }}');
        $('#summarySection').hide();
        $('#statusSection').hide();
        $('#classBreakdownSection').hide();
    });
    
    // Prevent form submission
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        $('#applyFilters').click();
        return false;
    });
});
</script>
@endsection
