@extends('admin.layouts.main')

@section('title')
    Payroll | Approval
@stop

@section('content')
    <div class="container-fluid ">
        <div class="card">
            <form id="approvalForm">
                <div class="card-body">
                    <div class="form-group col-md-3" id="from_date_div">
                        <label for="selectMonth"><b>Select Month/Year</b></label>
                        <input type="month" id="month_year"
                               name="month_year" class="form-control" value="{{ date('Y-m') }}">

                    </div>
                    <div>
                        <button id="search_button" type="button"
                            class="btn  btn-md btn-flat btn-primary ms-4">
                            <b>Search </b></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body table-responsive">
                        <table class="w-100 table border-top-0 table-bordered   border-bottom " id="data_table">
                            <thead>
                            <tr>

                                <th class="heading_style">No</th>
                                <th class="heading_style">Month</th>
                                {{--                                <th>Department</th>--}}
                                <th class="heading_style">Year</th>
                                <th class="heading_style">Status</th>
                                <th class="heading_style">Created At</th>
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
    </div>
@endsection
@section('css')
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
@endsection
@section('js')

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    {{--<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>--}}
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function () {

            var month_year = $('#month_year').val();
            $('#data_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('datatable.data.payroll.approvalDatatable')}}',
                    method: 'POST',
                    data: {
                        salary_year: month_year,
                        "_token": "{{ csrf_token() }}",
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'month', name: 'month'},
                    {data: 'year', name: 'year'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [[1, "asc"]]
            });


            $('#search_button').on('click', function () {
                var month_year = $('#month_year').val();
                $('#data_table').DataTable().destroy();
                $('#data_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{route('datatable.data.payroll.approvalDatatable')}}',
                        method: 'POST',
                        data: {
                            salary_year: month_year,
                            "_token": "{{ csrf_token() }}",
                        }
                    },
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'month', name: 'month'},
                        {data: 'year', name: 'year'},
                        {data: 'status', name: 'status'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ],
                    order: [[1, "asc"]]
                });
            });
        });
    </script>

@endsection
