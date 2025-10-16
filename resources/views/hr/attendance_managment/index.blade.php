@extends('admin.layouts.main')

@section('title')
    Attendance
@stop
@section('css')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Attendance </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
@if (Gate::allows('EmployeeAttendance-create'))
            <div class="col-12 text-right">
                <a href="{!! route('hr.attendance.create') !!}" class="btn btn-primary btn-md"><b>Add Attendance</b></a>
            </div>
            @endif
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body table-responsive">
                        <div class="mb-4">
                                <div class="row input-daterange">
                                    <div class="col-md-4">
                                        <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                                        <button type="button" name="refresh" id="refresh" class="btn btn-success">Reset</button>
                                    </div>
                                </div>
                        </div>
                        <table class="w-100 table border-top-0 table-bordered   border-bottom " id="data_table">
                            <thead>
                            <tr>
                                <th style="text-align: center;">
                                    <input type="checkbox" class="select-all-checkbox" onchange="checkAll(this)">
                                </th>

                                <th style="text-align: center;">No</th>
                                <th style="text-align: center;">Employee</th>
                                <th style="text-align: center;">Branch</th>
                                <th style="text-align: center;">Date</th>
                                <th style="text-align: center;">Status</th>
                                <th style="text-align: center;">Time In</th>
                                <th style="text-align: center;">Time Out</th>
                                <th style="text-align: center;">Action</th>
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
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
@endsection
@section('js')

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    {{--<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>--}}
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>

    {{--<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>--}}
    <script type="text/javascript">

        $(document).ready(function () {
            $('.input-daterange').datepicker({
                todayBtn:'linked',
                format:'yyyy-mm-dd',
                autoclose:true
            });

            load_data();
            function load_data(from_date = '', to_date = ''){
            var tableData = $('#data_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 10,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'collection',
                        className: "btn-light",
                        text: 'Export',
                        buttons: [
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: ':visible:not(.no-export)'
                                }
                            },
                            {
                                extend: 'pdf',
                                exportOptions: {
                                    columns: ':visible:not(.no-export)'
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: ':visible:not(.no-export)'
                                }
                            }
                        ]
                    },
                    {
                        extend: 'collection',

                        text: 'Bulk Action',
                        className: 'btn-light',
                        buttons: [
                            {
                                text: '<i class="fas fa-trash"></i> Delete',
                                className: 'btn btn-danger delete-button',
                                action: function () {
                                    var selectedIds = [];

                                    $('#data_table').find('.select-checkbox:checked').each(function () {
                                        selectedIds.push($(this).val());
                                    });

                                    if (selectedIds.length > 0) {
                                      $ ('.dt-button-collection').hide();

                                        Swal.fire({
                                            title: 'Are you sure?',
                                            text: 'You are about to perform a bulk action!',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'Yes, delete it!',
                                            cancelButtonText: 'Cancel'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $.ajax({
                                                    url: '{{ route('hr.bulk-action') }}',
                                                    type: 'POST',
                                                    data: {
                                                        ids: selectedIds,
                                                        "_token": "{{ csrf_token() }}",
                                                    },
                                                    dataType: 'json',
                                                    success: function (response) {
                                                        tableData.ajax.reload();
                                                        toastr.success('Data Deleted Successfully');
                                                    },
                                                    error: function (xhr, status, error) {
                                                        console.error(xhr.responseText);
                                                        toastr.error('AJAX request failed: ' + error);
                                                    }
                                                });
                                            }
                                        });
                                    } else {
                                        toastr.warning('No checkboxes selected.');
                                    }
                                }
                            },
                        ],
                    },

                    {
                        extend: 'colvis',
                        columns: ':not(:first-child)'
                    }
                ],
                "columnDefs": [
                    { 'visible': false }
                ],
                ajax: {
                    "url": "{{ route('datatable.attendance.getAttendance') }}",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}" ,from_date,to_date}
                },
                "columns": [
                    {
                        "data": "checkbox",
                        "render": function (data, type, row) {
                            return '<input type="checkbox" value="' + row.id + '" class="select-checkbox">';
                        },
                        orderable: false, searchable: false, className: 'no-export'
                    },

                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'employee', name: 'employee'},
                    {data: 'branch', name: 'branch'},
                    {data: 'attendance_date', name: 'attendance_date'},
                    {data: 'status', name: 'status'},
                    {data: 'timeIn', name: 'timeIn'},
                    {data: 'timeOut', name: 'timeOut'},
                    {data: 'action', name: 'action', orderable: false, searchable: false,  className: 'no-export'},
                ],
                order: [[2, 'desc']]
            });
            }

            $('#filter').click(function(){
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                if(from_date != '' &&  to_date != '') {
                    $('#data_table').DataTable().destroy();
                    load_data(from_date, to_date);
                } else {
                    toastr.warning('Both Date is required');
                }
            });

            $('#refresh').click(function () {
                $('#from_date').val('');
                $('#to_date').val('');
                $('#data_table').DataTable().destroy();
                load_data();
            });

        });

        function checkAll(source) {
            var checkboxes = document.querySelectorAll('.select-checkbox');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

    </script>
@endsection
