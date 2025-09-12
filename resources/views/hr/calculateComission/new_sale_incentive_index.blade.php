@extends('admin.layouts.main')

@section('title')
    Agent Commission
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> New Sale Incentive </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            <div class="col-12 text-right">
                <a href="{!! route('hr.new_incentive') !!}" class="btn btn-primary btn-sm ">Create New Sale
                    Incentive</a>
            </div>
        </div>
        <div class="row mt-3 text-center">
            <div class="col-lg-12">
                @if (session('status'))
                    <div class="alert alert-outline-danger alert-dismissible">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <table class="w-100 table border-top-0 table-bordered   border-bottom " id="data_table">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Agent Name</th>
                                <th>Student Count</th>
                                <th>Percentage</th>
                                <th>Advance Fee /1<sup>st</sup> Instalment</th>
                                <th>Commission</th>
                                <th>Action</th>
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
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('js')

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
                    "url": "{{ route('datatable.new_sale_incentive_get') }}",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'agent', name: 'agent'},
                    {data: 'count', name: 'count'},
                    {data: 'percentage', name: 'percentage'},
                    {data: 'student_fee', name: 'student_fee'},
                    {data: 'commission', name: 'commission'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endsection
