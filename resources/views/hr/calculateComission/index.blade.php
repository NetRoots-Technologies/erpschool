@extends('admin.layouts.main')

@section('title')
    Agent Commission
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Agent Commission </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            @if (Gate::allows('students'))

            <div class="col-12 text-right">
                <a href="{!! route('hr.calculate_comission.create') !!}" class="btn btn-primary btn-sm ">Generate Agent Commission</a>
            </div>
            @endif
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
                                <th>Agent Type</th>
                                <th>Comission Type</th>
                                <th>Slab Type</th>
                                <th>Total Comission</th>
                                <th>Status</th>
{{--                                <th>Action</th>--}}
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
                    { "visible": false }
                ],
                ajax: {
                    "url": "{{ route('datatable.old_commission_getdata') }}",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'agent', name: 'agent'},
                    {data: 'agent_type', name: 'agent_type'},
                    {data: 'comission_type', name: 'comission_type'},
                    {data: 'slab_type', name: 'slab_type'},
                    {data: 'total_comission', name: 'total_comission'},
                    {data: 'status', name: 'status'},
                    // {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endsection
