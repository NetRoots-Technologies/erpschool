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
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter_month" class="form-label">Filter by Month</label>
                                <input type="month" class="form-control" id="filter_month" 
                                       value="{{ date('Y-m') }}">
                                <small class="form-text text-muted">Filter will apply automatically</small>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="billingTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Challan No</th>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Billing Month</th>
                                    <th>Status</th>
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
                                <select class="form-control select2" id="billing_class_id" name="academic_class_id" required>
                                    <option value="">Select Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- <div class="col-md-4">
                            <div class="form-group">
                                        <label for="student_id">Student <span class="text-danger">*</span></label>
                                        <select class="form-control" id="student_id" name="student_id" required>
                                            <option value="">Select Student</option>
                                        </select>
                                        @error('student_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                        </div> --}}


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="billing_session_id" class="form-label">Session <span class="text-danger">*</span></label>
                                <select class="form-control" id="billing_session_id" name="academic_session_id" required>
                                    <option value="">Select Session</option>
                                    @foreach($sessions as $session)
                                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                                    @endforeach
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

            $('.select2').select2({
        width: '100%',
        placeholder: "Select option",
        allowClear: true
    });

        var billingTable = $('#billingTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.fee-management.billing.data') }}",
                type: 'GET',
                data: function(d) {
                    d.filter_month = $('#filter_month').val();
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'challan_number', name: 'challan_number' },
                { data: 'student_name', name: 'student_name' },
                { data: 'class_name', name: 'class_name' },
                { 
                    data: 'billing_month', 
                    name: 'billing_month',
                    render: function(data, type, row) {
                        return data ? new Date(data + '-01').toLocaleDateString('en-US', { year: 'numeric', month: 'long' }) : 'N/A';
                    }
                },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']],
            pageLength: 25,
            responsive: true
        });
        
        // Auto apply filter when month changes
        $('#filter_month').change(function() {
            billingTable.ajax.reload();
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

    $(function () {
        const $class   = $('#billing_class_id');
        const $student = $('#student_id');

        function resetStudents(placeholder) {
            $student.prop('disabled', true)
                    .empty()
                    .append('<option value="">' + (placeholder || 'Select Student') + '</option>');
        }

        $class.on('change', function () {
            const classId = $(this).val();
            resetStudents('Loading...');

            if (!classId) {
            resetStudents('Select Student');
            return;
            }

            // Build URL via route helper safely
            const url = "{{ route('admin.fee-management.class.students', ':id') }}".replace(':id', encodeURIComponent(classId));

            $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                
                $student.empty().append('<option value="">Select Student</option>');
                if (Array.isArray(res) && res.length) {
                res.forEach(function (s) {
                    $student.append('<option value="'+ s.id +'">'+ s.name +'</option>');
                });
                $student.prop('disabled', false);
                } else {
                resetStudents('No students found');
                }

                // If using Select2:
                // $student.trigger('change.select2');
            },
            error: function () {
                resetStudents('Unable to load students');
            }
            });
        });

  // (Optional) For edit forms with preselected values:
  // const preClassId = '{{ old('class_id') }}';
  // const preStudentId = '{{ old('student_id') }}';
  // if (preClassId) {
  //   $class.val(preClassId).trigger('change');
  //   // set selected student after AJAX completes
  //   $(document).one('ajaxStop', function(){ $student.val(preStudentId); });
  // }
});
</script>
<script>
            $(document).ready(function() {
                // Initialize DataTables aur baaki code wahan rahe

                // Jab modal khule to select2 initialize karo
                $('#generateBillingModal').on('shown.bs.modal', function () {
                    $('#billing_class_id').select2({
                        placeholder: 'Select Class',
                        width: '100%',
                        dropdownParent: $('#generateBillingModal')  // Modal ke andar dropdown properly render ho
                    });
                });

            });
</script>
@endsection

@section('css')
<style>
.badge {
    font-size: 0.75em;
    padding: 0.25em 0.5em;
    border-radius: 0.25rem;
}
.badge-success {
    background-color: #28a745;
    color: #212529;
}
.badge-warning {
    background-color: #ffc107;
    color: #212529;
}
.badge-info {
    background-color: #17a2b8;
    color: #212529;
}
.badge-danger {
    background-color: #dc3545;
    color: #212529;
}
.badge-secondary {
    background-color: #6c757d;
    color: #212529;
}
</style>
@endsection