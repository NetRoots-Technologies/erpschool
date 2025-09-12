@extends('admin.layouts.main')

@section('Attendance')
    Employee Attendance
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100   mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Employee Attendance  </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            <div class="col-12 text-right">
                <a class="btn btn-primary btn-sm " href="{{route('hr.attendance.create')}}">Create Attendance</a>
                <button class="btn btn-success btn-sm " id="sync_attendance">Sync Attendance</button>
            </div>
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body table-responsive">
                        <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="data_table">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>checkin_time</th>
                                <th>checkout_time</th>
                                <th>Difference</th>
{{--                                <th>overtime_out</th>--}}
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{--                                @foreach($attendance as $item)--}}
                            {{--                                    <tr>--}}
                            {{--                                        <td>{!! $item['id'] !!}</td>--}}
                            {{--                                        <td>{!! $item['user_id'] !!}</td>--}}
                            {{--                                        <td>--}}
                            {{--                                           @if(isset($item->user_name)) {!! $item->user_name->name !!} @endif</td>--}}
                            {{--                                        <td>{!! $item['date'] !!}</td>--}}
                            {{--                                        <td>{!! $item['checkin_time'] !!}</td>--}}
                            {{--                                        <td>{!! $item['checkout_time'] !!}</td>--}}
                            {{--                                        <td>{!! $item['overtime_in'] !!}</td>--}}
                            {{--                                        <td>{!! $item['overtime_out'] !!}</td>--}}
                            {{--                                    </tr>--}}
                            {{--                            @endforeach--}}
                            </tbody>
                        </table>
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

                var tableData = null;
                $(document).ready(function () {
                    tableData =   $('#data_table').DataTable({
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
                        order: [[0, 'desc']],
                        ajax: {
                            url: "{{ route('datatable.get_employee_attendance') }}",
                            type: "POST",
                            data: {_token: "{{csrf_token()}}"}
                        },

                        "columns": [
                            {data: 'id', name: 'id'},
                            {data: 'user_id', name: 'user_id'},
                            {data: 'user_name', name: 'user_name'},
                            {data: 'date', name: 'date'},
                            {data: 'checkin_time', name: 'checkin_time'},
                            {data: 'checkout_time', name: 'checkout_time'},
                            {data: 'difference', name: 'difference'},
                            // {data: 'overtime_out', name: 'overtime_out'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},

                        ]
                    });
                });
            </script>


            <script>
                $('#sync_attendance').click(function () {
                    $.ajax
                    ({
                        url: "{{ route('hr.sync_employee_attendance') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        type: 'post',
                        success: function (result) {
                            alert("Attendance Synced Successfully");
                            tableData.ajax.reload();

                        }
                    });
                });
            </script>
            <script>
                $(document).on("click", ".delete", function (event) {
                    event.preventDefault();
                    var route = $(this).data('route');
                    var a = confirm('Are you sure you want to Delete this?');
                    if (a) {
                        $.ajax({
                            url: route,
                            type: 'delete',
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function (result) {
                                tableData.ajax.reload();
                            }
                        });
                    }
                });
            </script>


@endsection

