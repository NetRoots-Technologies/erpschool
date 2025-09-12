@extends('admin.layouts.main')

@section('title')
    Students Fee
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> More than 30K Fee Paid Students </h3>
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form table-responsive">
                    <div class="card-body">


                        <table class="table border-top-0 table-bordered   border-bottom" id="data_table">

                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Session</th>
                                <th>Student Name</th>
                                {{--                                <th>Student Status</th>--}}
                                <th>Course Name</th>
                                <th>Course Fee</th>
                                <th>Student Fee</th>
                                <th>Discount Amount</th>
                                <th>Remaining Amount</th>
                                <th>Total Fee Paid</th>
                                <th>Tools Provided</th>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>

@endsection
@section('js')


    <script type="text/javascript">
        $(document).ready(function () {
            tableData = $('#data_table').DataTable({
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
                    "url":
                        "{{ route('datatable.get_data_student_fee_more_than_30k') }}",
                    "type":
                        "POST",
                    "data":
                        {
                            _token: "{{csrf_token()}}"
                        }
                }
                ,
                "columns":
                    [
                        {data: 'id', name: 'id'},
                        {data: 'session', name: 'session'},
                        {data: 'student_name', name: 'student_name'},
                        // {data: 'student_status', name: 'student_status'},
                        {data: 'course_name', name: 'course_name'},
                        {data: 'course_fee', name: 'course_fee'},
                        {data: 'student_fee', name: 'student_fee'},
                        {data: 'discount_amount', name: 'discount_amount'},
                        {data: 'remaining_amount', name: 'remaining_amount'},
                        {data: 'total_paid_fee', name: 'total_paid_fee'},
                        {data: 'tools_provided', name: 'tools_provided'},
                    ]
            });


        })
        ;
    </script>
@endsection
