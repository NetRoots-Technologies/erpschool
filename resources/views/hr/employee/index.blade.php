@extends('admin.layouts.main')

@section('title')
    Employee
@stop
@section('css')
    <style>
        .bg-info {
            background-color: #525252 !important;
        }

        .dt-button.buttons-columnVisibility {
            background: blue !important;
            color: white !important;
            opacity: 0.5;
        }

        .dt-button.buttons-columnVisibility.active {
            background: lightgrey !important;
            color: black !important;
            opacity: 1;
        }
        .error{
            color: red;
            font-size: 14px;
            font-weight: 500;
        }


    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Employee </h3>
        </div>

        {{-- <div class="row    mt-4 mb-4 ">
@if (Gate::allows('students'))
                <div class="col-12 text-right">
                    <a href="{!! route('hr.employee.create') !!}" class="btn btn-primary btn-md"><b>Create Employee</b></a>
                </div>
                <div class="col-12 text-right">
                    <form action="{{ route('hr.import.employee') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Choose Excel File</label>
                            <input type="file" name="file" id="file" class="form-control-file">
                        </div>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </form>
                </div>
            @endif
        </div> --}}



        <div class="row mt-4 mb-4 justify-content-start gap-4">
        {{-- Add employee --}}
        @if (Gate::allows('Employees-create'))

        <div class="col-auto p-0">
          <a href="{!! route('hr.employee.create') !!}" class="btn btn-primary btn-md"><b>Create Employee</b></a>
        </div>

        {{-- Download Sample Bulk File --}}
        <div class="col-auto p-0">
            <a href="{{ config('google_sheet_links.employee_file_link') }}" target="_blank" class="btn btn-warning btn-md">
                <b>Download Sample Bulk File</b>
            </a>
        </div>

        {{-- Import Sample Bulk File --}}
          <div class="col-auto">
                <a href="#" class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#importModal">
                    <b>Import Datale</b>
                </a>
            </div>
         @endif

    </div>

     <!-- Import File Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('academic.employee.import-file') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="importModalLabel">Import Excel File</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="import_file" class="form-label">Select File</label>
                                <input type="file" name="import_file" id="import_file" class="form-control" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Upload</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>




        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body table-responsive">
                        <table class="w-100 table border-top-0 table-bordered   border-bottom " id="data_table">
                            <thead>
                            <tr>
                                <th style="text-align: center;">
                                    <input type="checkbox" class="select-all-checkbox" onchange="checkAll(this)">
                                </th>

                                <th class="heading_style">No</th>
                                <th class="heading_style">Emp Id</th>
                                <th class="heading_style">Name</th>
                                {{--                                <th class="heading_style" >Email</th>--}}
                                <th class="heading_style">Mobile</th>
                                <th class="heading_style">Company</th>
                                <th class="heading_style">Branch</th>
                                <th class="heading_style">Department</th>
                                <th class="heading_style">Status</th>

                                <th class="heading_style">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot align="right">
                            <tr>
                                <th></th>
                                <th></th>
                                                                <th></th>
                                {{--                                <th></th>--}}
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>

                            </tr>
                            </tfoot>
                            <tr class="bg-info">
                                <th></th>
                                <th>No</th>
                                <th class="heading_style">Emp Id</th>
                                <th>Name</th>
                                {{--                                <th>Department</th>--}}
                                {{--                                <th>Email</th>--}}
                                <th>Mobile</th>
                                <th>Company</th>
                                <th>Branch</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" tabindex="-1" role="dialog" id="statusModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Employee Status</h5>
                    <button type="button" class="close close_modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="statusForm">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveStatus">Save changes</button>
                    <button type="button" class="btn btn-secondary close_modal" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="inActiveModal" style="display: none;">
        <div class="row">
            <div class="col-md-6">
                <label for="statusSelect">Status</label>
                <select class="form-control"  name="reason_leaving" required>
                    <option value="" selected disabled>Select Status</option>
                    <option value="ter">Terminate</option>
                    <option value="res">Resigned</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="dateInput">Date</label>
                <input type="date" class="form-control" id="dateInput" name="leaving_date" required>
            </div>
        </div>
        <input type="hidden" id="employeeId" name="id">
        <input type="hidden" id="statusSelect" name="status">
    </div>

    <div id="ActiveModal" style="display: none;">
        <div class="row">

            <div class="col-md-6">
                <label for="statusSelect">Status</label>
                <select class="form-control" name="reason_leaving" required>
                    <option value="" selected disabled>Select Status</option>
                    <option value="rejoin" selected>Rejoin</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="dateInput">Date</label>
                <input type="date" class="form-control" id="dateInput" name="leaving_date" required>
            </div>
        </div>
        <input type="hidden" id="employeeId" name="id">
        <input type="hidden" id="statusSelect" name="status">

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
    {{--<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>--}}
    <script type="text/javascript">
        function checkAll(source) {
            var checkboxes = $('.select-checkbox');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        $(document).ready(function () {

            $("#statusForm").validate({
                rules: {
                    reason_leaving: {
                        required: true
                    },
                    leaving_date: {
                        required: true
                    }
                }
            });

            var dataTable = $('#data_table').DataTable({
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
                                        $('.dt-button-collection').hide();

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
                                                    url: '{{ route('hr.employee-bulk') }}',
                                                    type: 'POST',
                                                    data: {
                                                        ids: selectedIds,
                                                        "_token": "{{ csrf_token() }}",
                                                    },
                                                    dataType: 'json',
                                                    success: function (response) {
                                                        dataTable.ajax.reload();
                                                        Swal.fire('Deleted!', 'Your data has been deleted.', 'success');
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
                    {'visible': false}
                ],
                ajax: {
                    "url": "{{ route('datatable.get_data_employee') }}",
                    "type": "POST",
                    "data": {_token: "{{ csrf_token() }}"}
                },
                "columns": [
                    {
                        data: "checkbox",
                        render: function (data, type, row) {
                            return '<input type = "checkbox" value="' + row.id + '" class="select-checkbox">'
                        },
                        orderable: false, searchable: false
                    },
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'emp_id', name: 'emp_id'},
                    {data: 'name', name: 'name'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    {data: 'company', name: 'company'},
                    {data: 'branch', name: 'branch'},
                    {data: 'department', name: 'department'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [2, 'desc']


            });


            $('#data_table tbody').on('click', '.employee_attendance', function () {
                var id = $(this).data('id');

                var loader = $('<div class="loader"></div>').appendTo('body');

                $.ajax({
                    type: 'POST',
                    url: '{{ route('hr.employee.add-data') }}',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (response) {
                        loader.remove();
                        toastr.success("Employee Added in Machine successfully.")
                        dataTable.ajax.reload();
                    },
                    error: function (xhr, status, error) {
                        loader.remove();
                        toastr.error(xhr.responseText);
                        console.error(xhr.responseText);
                    }
                });


            });


            $('#data_table tbody').on('click', '.change-status', function () {
                var id = $(this).data('id');
                var status = $(this).data('status');

                $('#statusForm').empty();



                if (status == 'inactive') {
                    $('#statusForm').append($('#inActiveModal').html());
                } else {
                    $('#statusForm').append($('#ActiveModal').html());
                }

                $('#statusSelect').val(status);
                $('#employeeId').val(id);

                $('#statusModal').modal('show');
            });

            $('#saveStatus').on('click', function (e) {
                e.preventDefault();
                var form = $('#statusForm');
                var formData = form.serialize();
                var loader = $('<div class="loader"></div>').appendTo('body');
                if (!$("#statusForm").valid()) {
                    return false;
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '{{ route('hr.employee.change-status') }}',
                    data: formData,
                    success: function (response) {
                        loader.remove();

                        console.log(response);
                        dataTable.ajax.reload();
                        toastr.success('Status updated successfully.');

                        $('#statusModal').modal('hide');
                    },
                    error: function (xhr, status, error) {
                        loader.remove();

                        console.error(xhr.responseText);
                    }
                });
            });

            $('.close_modal').on('click',function () {
                    $('#statusModal').modal('hide');
            });
        });

    </script>
@endsection
