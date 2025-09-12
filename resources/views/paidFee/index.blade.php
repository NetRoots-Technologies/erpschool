@extends('admin.layouts.main')

@section('title')
    Students Fee
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Students Fee </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
@if (Gate::allows('students'))

            <div class="col-12 text-right">
                <a href="{!! route('admin.paid_student_fee.create') !!}" class="btn btn-primary btn-sm ">Pay Student Fee</a>
            </div>
            @endif
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="data_table">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Student Name</th>
                                <th>Student Fee</th>
                                <th>Course Fee</th>
                                <th>Instalment Type</th>
                                <th>Course</th>
                                <th>Status</th>
                                <th width="200px">Action</th>
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
                    "url": "{{ route('datatable.get_data_student_fee') }}",
                    "type": "get",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'data_bank_id', name: 'name'},
                    {data: 'student_fee', name: 'student_fee'},
                    {data: 'course', name: 'course'},
                    {data: 'installement_type', name: 'installement_type'},
                    {data: 'course_id', name: 'course_id'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });




        });
    </script>
@endsection
