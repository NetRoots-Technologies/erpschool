@extends('admin.layouts.main')

@section('title')
    Sibling Report
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
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Student Sibling Report</h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            {{--            @if (Gate::allows('Employee-create')) --}}
            {{--            @endif --}}
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body table-responsive">
                        <table class="w-100 table border-top-0 table-bordered   border-bottom " id="data_table">
                            <thead>
                                <tr>
                                    <th class="heading_style">Sr No</th>
                                    <th class="heading_style">Student Id</th>
                                    <th class="heading_style">Student Name</th>
                                    <th class="heading_style">Father Name</th>
                                    <th class="heading_style">Father CNIC</th>
                                    <th class="heading_style">Branch</th>
                                    <th class="heading_style">Class</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>

                        </table>
                    </div>
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
    {{-- <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script> --}}
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script> --}}
    <script type="text/javascript">
        $(document).ready(function() {
            // toastr.success('Student Sibling Report Fetched Successfully');
            var dataTable = $('#data_table').DataTable({
                "processing": true,
                "serverSide": true,
                "ordering": false,
                "pageLength": 100,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'collection',
                        className: "btn-light",
                        text: 'Export',
                        buttons: [{
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
                "columnDefs": [{
                    'visible': false
                }],
                ajax: {
                    "url": '{{ route('datatable.getStudentSibling') }}',
                    "type": "POST",
                    "data": {
                        _token: "{{ csrf_token() }}"
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'student_id',
                        name: 'student_id'
                    },
                    {
                        data: 'student_name',
                        name: 'student_name'
                    },
                    {
                        data: 'father_name',
                        name: 'father_name'
                    },
                    {
                        data: 'father_cnic',
                        name: 'father_cnic'
                    },
                    {
                        data: 'branch',
                        name: 'branch'
                    },
                    {
                        data: 'class',
                        name: 'class'
                    },
                ],
                order: [4, 'desc']
            });

        });
    </script>
@endsection
