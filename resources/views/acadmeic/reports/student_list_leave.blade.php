@extends('admin.layouts.main')

@section('title', 'Student List Leave Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader"></div>
                <h4 class="page-title mb-0">Student List Leave Report</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Acedemic</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.reports') }}">Reports</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Student List Leave Report</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
{{-- <div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Filter Options</h3></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" id="status_filter">
                        <option value="" selected>All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

{{-- Table --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Student List</h3></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-vcenter text-nowrap mb-0" id="studentListTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Father Name</th>
                                <th>Class</th>
                                <th>Section</th>
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
                d.status = $('#status_filter').val();
            }
        },
        dom: 'Bfrtip',
        buttons: ['pageLength', 'copy', 'csv', 'excel', 'pdf', 'print'],
        lengthMenu: [[10,25,50,-1],[10,25,50,"All"]],
        columns: [
            { data: null, orderable: false, searchable: false, render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
            { data: 'student_id', name: 'student_id' },
            { data: 'name', name: 'name' },
            { data: 'father_name', name: 'father_name' },
            { data: 'class', name: 'AcademicClass.name' },
            { data: 'section', name: 'section' },
            { data: 'leave_reason', name: 'leave_reason' },
            { data: 'approve_by_name', name: 'approve_by_name' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
        ],
        order: [[1, 'desc']],
    });

    // On filter change reload table
    // $('#status_filter').change(function() {
    //     table.ajax.reload();
    // });

    // // Reset filters
    // $('#resetFilters').click(function () {
    //     $('#status_filter').val('').trigger('change');
    //     table.ajax.reload();
    // });

});
</script>
@endsection
