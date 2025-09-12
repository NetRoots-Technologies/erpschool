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
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4">Students</h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            {{--            @if (Gate::allows('Employee-create'))--}}

            {{--            @endif--}}
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body table-responsive">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="campus_filter"><b> Filter by Campus: </b></label>
                                <select class="form-control select2 select2-selection--single" id="campus_filter">
                                    <option value="" selected disabled> <b>Select Campus</b> </option>
                                    @foreach($branches as $branch)
                                        <option value="{!! $branch->id !!}">{!! $branch->name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="class_filter"><b> Filter by Class: </b></label>
                                <select class="form-control select2 select2-selection--single" id="class_filter">
                                    <option value="" selected disabled>  Select Class </option>
                                    @foreach($classes as $class)
                                        <option value="{!! $class->id !!}">{!! $class->name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mt-4">
                                <button type="button" name="refresh" id="refresh" class="btn btn-success">Reset</button>
                            </div>
                        </div>
                        <table class="w-100 table border-top-0 table-bordered border-bottom" id="data_table">
                            <thead>
                            <tr>
                                <th class="heading_style">Sr No</th>
                                <th class="heading_style">Student ID</th>
                                <th class="heading_style">Name</th>
                                <th class="heading_style">Email</th>
                                <th class="heading_style">Father Name</th>
                                <th class="heading_style">Class</th>
                                <th class="heading_style">Campus</th>
                                <th class="heading_style">Admission Date</th>
                                <th class="heading_style">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
            </div>
        </div>

    </div>
@stop
@section('css')
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
@endsection
@section('js')

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    {{--<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>--}}
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    {{--<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>--}}
    <script type="text/javascript">

        $(document).ready(function () {
            var dataTable = $('#data_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'collection',
                        className: "btn-light",
                        text: 'Export',
                        buttons: [
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'pdf',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            }
                        ]
                    },
                    {
                        extend: 'colvis',
                        columns: ':not(:first-child)'
                    }
                ],
                "columnDefs": [
                    {'visible': false}
                ],
                ajax: {
                    "url": "{{ route('datatable.getStudentViewData') }}",
                    "type": "POST",
                    "data": {_token: "{{ csrf_token() }}"}
                },
                "columns": [
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
                order: [2, 'desc']
            });

           $('#campus_filter').on('change', function () {
                var campus = $(this).find(":selected").text();
                dataTable.column(5).search(campus).draw(); // Campus is column index 5
            });

            $('#class_filter').on('change', function () {
                var AcademicClass = $(this).find(":selected").text();
                dataTable.column(4).search(AcademicClass).draw(); // Class is column index 4
            });

            $('#refresh').click(function () {
                $('#campus_filter').val('').trigger("change");
                $('#class_filter').val('').trigger("change");
                dataTable.columns([4, 5]).search('').draw(); // Class = 4, Campus = 5
            });

        });


    </script>
@endsection
