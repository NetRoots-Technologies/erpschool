@extends('admin.layouts.main')

@section('title', 'Student List Report')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h3 class="card-title">Filter Options</h3></div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Status Filter -->
                        <div class="col-md-3">
                            <label>Status</label>
                            <select class="form-control" id="status_filter">
                                <option value="" selected>All</option>
                                <option value="active">On Roll</option>
                                <option value="inactive">Left</option>
                            </select>
                        </div>

                        <!-- Class Filter -->
                        <div class="col-md-3">
                            <label>Class</label>
                            <select class="form-control" id="class_filter">
                                <option value="" selected>All</option>
                                @foreach(\App\Models\Academic\AcademicClass::all() as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h3 class="card-title">Student List</h3></div>
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
                                    <th>Summary</th>
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
            url: "{{ route('academic.report.student-status') }}",
            type: 'GET',
            data: function(d) {
                d.status = $('#status_filter').val();
                d.class_id = $('#class_filter').val();
            }
        },
        dom: 'Bfrtip',
        buttons: ['pageLength', 'copy', 'csv', 'excel', 'pdf', 'print'],
        lengthMenu: [[10,25,50,-1],[10,25,50,"All"]],
        columns: [
            { data: null, orderable: false, searchable: false,
              render: function(data, type, row, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
              }
            },
            { data: 'student_id', name: 'student_id' },
            { data: 'name', name: 'name' },
            { data: 'father_name', name: 'father_name' },
            { data: 'class', name: 'AcademicClass.name' },
            { data: 'section', name: 'section.name' },
            { data: 'summary', name: 'summary', orderable: false, searchable: false },
        ],
        order: [[1, 'desc']],
    });

    // Reload table on filter change
    $('#status_filter, #class_filter').change(function() {
        table.ajax.reload();
    });

});
</script>
@endsection
