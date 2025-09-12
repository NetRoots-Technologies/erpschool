@inject('request', 'Illuminate\Http\Request')
@inject('Currency', '\App\Helpers\Currency')
@extends('admin.layouts.main')

@section('css')
<style>
    #ledger_table th,
    #ledger_table td {
        width: calc(100% / 4);
    }
</style>
@stop

@section('content')

    <div class="row w-100 text-center">
        <div class="card basic-form table-responsive">
            <div class="card-body">
                <div class="row mb-5 d-flex flex-row-reverse" id="apply_filter">
                    <div class="col-lg-3 p-2">
                        {{--                        <button class="btn btn-outline-primary btn">Filters</button>--}}

                    </div>
                </div>
                <div class="row">
                    <div class="filter   mb-5" id="filter" style="display: none">
                        <div class="card">
                            <div class="card-body">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center p-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8"></div>
                    </div>
                    <table class="table table-bordered table-responsive" id="ledger_table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Group</th>
                            <th>Number</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        var tableData;
        $(document).ready(function () {
            tableData = $('#ledger_table').DataTable({
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
                    "url": "{{ route('datatable.get-data-ledger-receivable') }}",
                    "type": "get",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'groups', name: 'groups'},
                    {data: 'number', name: 'number'},
                ]
            });
        });
    </script>

@endsection
