@extends('admin.layouts.main')

@section('title', 'Fee Billing')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Fee Billing</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Billing</li>
                    </ol>
                </div>
                <div class="page-rightheader">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateBillingModal">
                        <i class="fa fa-file-text"></i> Generate Billing
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Billing Records</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="billingTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Challan No</th>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Total Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Billing Modal -->
<div class="modal fade" id="generateBillingModal" tabindex="-1" aria-labelledby="generateBillingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateBillingModalLabel">Generate Fee Billing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.fee-management.billing.generate') }}" method="POST" id="generateBillingForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="billing_class_id" class="form-label">Class <span class="text-danger">*</span></label>
                                <select class="form-control" id="billing_class_id" name="class_id" required>
                                    <option value="">Select Class</option>
                                    <option value="1">Class 1</option>
                                    <option value="2">Class 2</option>
                                    <option value="3">Class 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="billing_session_id" class="form-label">Session <span class="text-danger">*</span></label>
                                <select class="form-control" id="billing_session_id" name="session_id" required>
                                    <option value="">Select Session</option>
                                    <option value="1">2024-25</option>
                                    <option value="2">2025-26</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="billing_month" class="form-label">Billing Month <span class="text-danger">*</span></label>
                                <input type="month" class="form-control" id="billing_month" name="billing_month" 
                                       value="{{ date('Y-m') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="exclude_arrears" name="exclude_arrears">
                                    <label class="form-check-label" for="exclude_arrears">
                                        Exclude Arrears
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate Billing</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#billingTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.fee-management.billing.data') }}",
                type: 'GET'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'challan_number', name: 'challan_number' },
                { data: 'student_name', name: 'student_name' },
                { data: 'class_name', name: 'class_name' },
                { 
                    data: 'total_amount', 
                    name: 'total_amount',
                    render: function(data, type, row) {
                        return 'Rs. ' + parseFloat(data).toLocaleString();
                    }
                },
                { 
                    data: 'due_date', 
                    name: 'due_date',
                    render: function(data, type, row) {
                        return new Date(data).toLocaleDateString();
                    }
                },
                { data: 'status', name: 'status' },
                { 
                    data: 'created_at', 
                    name: 'created_at',
                    render: function(data, type, row) {
                        return new Date(data).toLocaleDateString();
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']],
            pageLength: 25,
            responsive: true
        });
        
        // Generate billing form validation
        $('#generateBillingForm').on('submit', function(e) {
            const classId = $('#billing_class_id').val();
            const sessionId = $('#billing_session_id').val();
            const billingMonth = $('#billing_month').val();
            
            if (!classId || !sessionId || !billingMonth) {
                e.preventDefault();
                toastr.error('Please fill in all required fields');
                return false;
            }
        });
    });
</script>
@endsection
