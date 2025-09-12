@extends('admin.layouts.main')

@section('title')
    Agent Commission
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Sale Recovery </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            <div class="col-12 text-right">
                <a href="{!! route('hr.new_sale_recovery_create') !!}" class="btn btn-primary btn-sm ">Create New Sale
                    Recovery</a>
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
                        <table class="w-100 table border-top-0 table-bordered  table-responsive  border-bottom " id="data_table">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Agent Name</th>
                                <th>Recovered Percentage</th>
                                <th>Total Paid Installments</th>
                                <th>Total Student Fee</th>
                                <th>Incentive Percentage</th>
                                <th>Commission</th>
                                <th>Start Date</th>
                                <th>End Date</th>
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
                    "url": "{{ route('datatable.new_recovery_incentive_get') }}",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'agent', name: 'agent'},
                    {data: 'recovered_percentage', name: 'recovered_percentage'},
                    {data: 'total_paid_installment', name: 'total_paid_installment'},
                    {data: 'total_student_fee', name: 'total_student_fee'},
                    {data: 'incentive_percentage', name: 'incentive_percentage'},
                    {data: 'commission', name: 'commission'},
                    {data: 'start_date', name: 'start_date'},
                    {data: 'end_date', name: 'end_date'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endsection
