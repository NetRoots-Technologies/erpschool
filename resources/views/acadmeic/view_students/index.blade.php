@extends('admin.layouts.main')

@section('title')
    View Student
@stop

@section('css')
    <style>
        .bg-info {
            background-color: #525252 !important;
        }

        .dt-button.buttons-columnVisibility {
            background: blue !important;
            color: white !important;
            opacity: 0.5;
        }

        .dt-button.buttons-columnVisibility.active {
            background: lightgrey !important;
            color: black !important;
            opacity: 1;
        }
    </style>
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row w-100 mt-4">
        <h3 class="text-22 text-center text-bold w-100 mb-4">Students</h3>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <label for="campus_filter"><b> Filter by Campus: </b></label>
            <select class="form-control select2 select2-selection--single" id="campus_filter">
                <option value="" selected disabled>Select Campus</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label for="class_filter"><b> Filter by Class: </b></label>
            <select class="form-control select2 select2-selection--single" id="class_filter">
                <option value="" selected disabled>Select Class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 mt-4">
            <button type="button" class="btn btn-success" id="refresh">Reset</button>
        </div>
    </div>

    <div class="row w-100 text-center">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body table-responsive">
                    <table class="w-100 table table-bordered table-striped" id="data_table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Father Name</th>
                                <th>Class</th>
                                <th>Campus</th>
                                <th>Admission Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function () {
    var dataTable = $('#data_table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 100,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'collection',
                className: 'btn-light',
                text: 'Export',
                buttons: ['excel', 'pdf', 'print']
            },
            {
                extend: 'colvis',
                columns: ':not(:first-child)'
            }
        ],
        ajax: {
            url: "{{ route('datatable.getStudentViewData') }}",
            type: "POST",
            data: function(d) {
                d._token = "{{ csrf_token() }}";
                d.campus = $('#campus_filter').val();
                d.academic_class = $('#class_filter').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'student_id', name: 'student_id'},
            {data: 'name', name: 'name'},
            {data: 'student_email', name: 'student_email'},
            {data: 'father_name', name: 'father_name'},
            {data: 'AcademicClass', name: 'AcademicClass'},
            {data: 'campus', name: 'campus'},
            {data: 'admission_date', name: 'admission_date'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        order: [[2, 'desc']]
    });

    // Filter change events
    $('#campus_filter, #class_filter').on('change', function () {
        dataTable.ajax.reload();
    });

    // Reset button
    $('#refresh').click(function () {
        $('#campus_filter').val(null).trigger('change');
        $('#class_filter').val(null).trigger('change');
        dataTable.ajax.reload();
    });
});
</script>
@endsection
