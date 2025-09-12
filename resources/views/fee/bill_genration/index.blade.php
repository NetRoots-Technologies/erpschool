@extends('admin.layouts.main')

@section('title')
    Billing
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
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Billing</h3>
        </div>
        <div class="row    mt-4 mb-4 ">
       @if (Gate::allows('BillGeneration-create'))
            <div class="col-12 text-right">
                <a href="{!! route('admin.bill-generation.create') !!}" class="btn btn-primary btn-md"><b>Add
                        Billing</b></a>
            </div>
            @endif
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
                                <th class="heading_style">Sr No</th>
                                <th class="heading_style">Branch</th>
                                <th class="heading_style">Class</th>
                                <th class="heading_style">Student</th>
                                <th class="heading_style">Fees</th>
                                <th class="heading_style">Status</th>
                                <th class="heading_style">Action</th>
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
    {{--<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>--}}
    <script type="text/javascript">

        $(document).ready(function () {
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
                                                    url: '{{ route('admin.bill-generation-bulk') }}',
                                                    type: 'POST',
                                                    data: {
                                                        ids: selectedIds,
                                                        "_token": "{{ csrf_token() }}",
                                                    },
                                                    dataType: 'json',
                                                    success: function (response) {
                                                        dataTable.ajax.reload();
                                                        toastr.success('Your data has been deleted.');
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
                    "url": "{{ route('datatable.getBillGeneration') }}",
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
                    {data: 'branch', name: 'branch'},
                    // { data: 'FeeSection', name: 'FeeSection' },
                    {data: 'class', name: 'class'},
                    {data: 'student', name: 'student'},
                    {data: 'fees', name: 'fees'},
                    {
                        data: "status",
                        name: "status",
                        // "render": function(data, type, row) {
                        //     return (data == 'Unpaid') ? '<span style="color: red" >Unpaid</span>' : '<span style="color: green">Paid</span>';
                        // }
                    },

                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [2, 'desc']
            });


            $('#data_table tbody').on('click', '.change-status', function () {
                var id = $(this).data('id');
                var status = $(this).data('status');

                $('#statusModal').modal('show');

                $('#confirmStatusChange').off('click').on('click', function () {
                    var paidDate = $('input[name="paid_date"]').val();
                    var paidAmount = $('input[name="paid_amount"]').val();
                    var loader = $('<div class="loader"></div>').appendTo('body');

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('admin.bill-generation.change-status') }}',
                        data: {
                            id: id,
                            status: status,
                            paid_date: paidDate,
                            paid_amount: paidAmount,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (response) {
                            loader.remove();
                            $('#statusModal').modal('hide');
                            dataTable.ajax.reload();
                            toastr.success('Status updated successfully.');
                        },
                        error: function (xhr, status, error) {
                            loader.remove();
                            $('#statusModal').modal('hide');
                            toastr.error(xhr.responseText);
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        });

        function checkAll(source) {
            var checkboxes = $('.select-checkbox');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked;
            }
        }


    </script>
@endsection
