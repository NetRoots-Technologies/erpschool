@extends('admin.layouts.main')

@section('title')
    Generate Payroll
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Generate Payroll </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            <div class="col-12 text-right">
                <a href="{!! route('hr.calculate_comission.index') !!}" class="btn btn-primary btn-sm ">Back</a>
            </div>
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <form action="{!! route('hr.calculate_comission.store') !!}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mt-4 mb-4">

                                <div class="col-md-6">
                                    <label for="name"> Start Date <b>*</b> </label>
                                    <input type="text" class="form-control" name="start_date" id="datepicker" autocomplete="off"/>
                                </div>

                                <div class="col-md-6">
                                    <label for="name"> End Date <b>*</b> </label>
                                    <input type="text" class="form-control" name="end_date" id="datepicker1" autocomplete="off"/>
                                </div>

                            </div>


                            <div class="row">
                                <table class="w-100 table border-top-0 table-bordered   border-bottom " id="data_table">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Selection</th>
                                        <th>Agent Name</th>
                                        <th>Agent Type</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                            </div>
                            <div class="row mt-4 mb-4">
                                <button class="btn btn-primary" type="submit">Generate</button>
                            </div>
                        </form>
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


    <script src="{{asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js')}}"></script>

    <script>
        $("#datepicker").datepicker( {
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        }); $("#datepicker1").datepicker( {
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });

    </script>







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
                    "url": "{{ route('datatable.get_agent_comission') }}",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'selection', name: 'selection'},
                    {data: 'name', name: 'name'},
                    {data: 'agent_type', name: 'agent_type'},
                ]
            });
        });
    </script>
@endsection

