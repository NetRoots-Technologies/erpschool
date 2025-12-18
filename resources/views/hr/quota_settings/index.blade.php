@extends('admin.layouts.main')

@section('title')
    Quota
@stop
@section('css')

@endsection

@section('content')

    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Qouta </h3>
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body table-responsive">

                        <div class="card-body table-responsive">

                    <div class="mb-3 text-right">
                        <a href="{{ route('hr.qouta_sections.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add Qouta
                        </a>
                    </div>
                        <table class="w-100 table border-top-0 table-bordered   border-bottom " id="data_table">
                            <thead>
                            <tr>
                                <th class="heading_style">No</th>
                                <th class="heading_style">Leave Type</th>
                                <th class="heading_style">Permitted Days</th>
                                {{-- <th class="heading_style">Action</th> --}}
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
    {{--<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>--}}
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    {{--<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>--}}
    <script type="text/javascript">

        $(document).ready(function () {
            $('#data_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                dom: 'Bfrtip',
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
                    },
                    'colvis'
                ],
                "columnDefs": [
                    {"visible": false}
                ],
                ajax: {
                    "url": "{{ route('datatable.quota.getdata') }}",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },

                    {data: 'leave_type', name: 'leave_type'},
                    {data: 'permitted_days', name: 'permitted_days'},
                    // {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });

    </script>
@endsection
