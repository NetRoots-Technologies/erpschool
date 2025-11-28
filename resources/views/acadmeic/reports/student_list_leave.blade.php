@extends('admin.layouts.main')

@section('title', 'Student List Leave Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="page-title mb-0">Student List Leave Report</h4>
                <ol class="breadcrumb mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Academic</li>
                    <li class="breadcrumb-item active">Student List Leave Report</li>
                </ol>
            </div>
        </div>
    </div>

  <!-- Filters Card -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row">
                    <!-- Class Filter -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Class</label>
                            <select class="form-control" id="class_filter">
                                <option value="" selected>All</option>
                                @foreach(\App\Models\Academic\AcademicClass::all() as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Month Filter -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="filter_month" class="form-label font-weight-bold">Filter by Month</label>
                            <input type="date" class="form-control" id="filter_month" value="">
                            {{-- <small class="form-text text-muted">Filter will apply automatically on current month</small> --}}
                        </div>
                    </div>
                </div> <!-- end row inside card -->
            </div>
        </div>
    </div>
</div>


    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
              
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered text-nowrap mb-0" id="studentListTable">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Father Name</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Leave Date</th>
                                    <th>Leave Reason</th>
                                    <th>Approve By</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
$(document).ready(function() {

    var table = $('#studentListTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('academic.report.student-leave') }}",
            type: 'GET',
            data: function(d) {
                d.class_id = $('#class_filter').val(); // Class filter only
                d.month = $('#filter_month').val(); // Month filter
            }
        },
        dom: 'Bfrtip',
        buttons: ['pageLength', 'copy', 'csv', 'excel', 'pdf', 'print'],
        lengthMenu: [[10,25,50,-1],[10,25,50,"All"]],
        columns: [
            { data: null, orderable: false, searchable: false, render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1; 
            }},
            { data: 'student_id', name: 'student_id' },
            { data: 'name', name: 'name' },
            { data: 'father_name', name: 'father_name' },
            { data: 'class', name: 'AcademicClass.name' },
            { data: 'section', name: 'section' },
            { data: 'leave_date', name: 'leave_date' },
            { data: 'leave_reason', name: 'leave_reason' },
            { data: 'approve_by_name', name: 'approve_by_name' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
        ],
        order: [[1, 'desc']],
    });

    // Reload table on class filter change
    $('#class_filter').change(function() {
        table.ajax.reload();
    });

    // Reload table on month filter change
    $('#filter_month').change(function() {
        table.ajax.reload();
    });

});
</script>
@endsection
