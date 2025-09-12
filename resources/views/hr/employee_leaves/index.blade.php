@extends('admin.layouts.main')

@section('title')
    Employee Leaves
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Employee Leaves </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
@if (Gate::allows('students'))
                <div class="col-12 text-right">
                    <a href="{!! route('hr.employee_leaves.create') !!}" class="btn btn-primary btn-sm ">Create Employee
                        Leaves</a>
                </div>
            @endif
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <table class="w-100 table border-top-0 table-bordered  table-responsive border-bottom "
                               id="data_table">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Leave Title</th>
                                <th>Employee Name</th>
                                <th>Leave Type</th>
                                <th>Leave Reason</th>
                                <th>Leave Date</th>
                                <th>Admin Approval</th>
                                <th>HR Approval</th>
                                <th>HOD Approval</th>
                                <th>Team Lead Approval</th>
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
                    "url": "{{ route('datatable.get_data_employee_leaves') }}",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'leave_title', name: 'leave_title'},
                    {data: 'employee_name', name: 'employee_name'},
                    {data: 'leave_type', name: 'leave_type'},
                    {data: 'leave_reason', name: 'leave_reason'},
                    {data: 'leave_date', name: 'leave_date'},
                    {data: 'admin_approval', name: 'admin_approval'},
                    {data: 'hr_approval', name: 'hr_approval'},
                    {data: 'hod_approval', name: 'hod_approval'},
                    {data: 'team_lead_approval', name: 'team_lead_approval'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endsection
