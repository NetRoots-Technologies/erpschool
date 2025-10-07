@extends('admin.layouts.main')

@section('title')
    Approval Maintenance Request
@stop

@section('content')
    <style>
        #modal_name,
        #modal_type {
            margin-right: 500px;
        }
    </style>
    <div class="container-fluid">
        <div class="row w-100">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Approval Maintenance Request </h3>
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="maintenance_request"
                                class="border-top-0  table table-bordered text-nowrap key-buttons border-bottom">
                                <thead>
                                    <tr>
                                        <th class="heading_style">Builing Name</th>
                                        <th class="heading_style">Unit</th>
                                        <th class="heading_style">Issue Type</th>
                                        <th class="heading_style">Maintainer</th>
                                        <th class="heading_style">Request Date</th>
                                        <th class="heading_style">Approval</th>
                                        <th class="heading_style">Work Status</th>
                                        <th class="heading_style">Done Approval</th>
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
        @endsection
        @section('js')
            <script type="text/javascript">
                var tableData = null;
                $(document).ready(function() {
                    tableData = $('#maintenance_request').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "pageLength": 10,
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'collection',
                                className: "btn-light",
                                text: 'Export',
                                buttons: [{
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
                                extend: 'colvis',
                                columns: ':not(:first-child)'
                            }
                        ],
                        "columnDefs": [{
                            'visible': false
                        }],
                        ajax: {
                            "url": "{{ route('maintenance-request.approval') }}",
                            "type": "GET",
                            "data": {
                                _token: "{{ csrf_token() }}"
                            }
                        },
                        "columns": [{
                                data: 'building_name',
                                name: 'building_name'
                            },
                            {
                                data: 'unit_name',
                                name: 'unit_name'
                            },
                            {
                                data: 'type_name',
                                name: 'type_name'
                            },
                            {
                                data: 'maintenance_name',
                                name: 'maintenance_name'
                            },
                            {
                                data: 'request_date',
                                name: 'request_date'
                            },
                            {
                                data: 'status',
                                name: 'status'
                            },
                            {
                                data: 'done_status',
                                name: 'done_status'
                            },
                            {
                                data: 'approval_status',
                                name: 'approval_status'
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            },
                        ]
                    });

                    // Approve
                    $(document).on('click', '.js-approve', function(e) {
                        e.preventDefault();
                        const url = $(this).data('url');

                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            beforeSend: () => $(".loader").show?.(),
                            success: function(res) {
                                toastr.success(res.message || 'Approved');
                                tableData.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                toastr.error(xhr.responseJSON?.message || 'Approve failed');
                            },
                            complete: () => $(".loader").hide?.()
                        });
                    });

                    // Reject (ask optional reason)
                    $(document).on('click', '.js-reject', function(e) {
                        e.preventDefault();
                        const url = $(this).data('url');

                        // Simple prompt; replace with your modal if you have one
                        // const reason = prompt('Reason for rejection (optional):', '');

                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            beforeSend: () => $(".loader").show?.(),
                            success: function(res) {
                                toastr.success(res.message || 'Rejected');
                                tableData.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                toastr.error(xhr.responseJSON?.message || 'Reject failed');
                            },
                            complete: () => $(".loader").hide?.()
                        });
                    });


                    const CSRF = $('meta[name="csrf-token"]').attr('content');

                    // Maintainer: mark In Progress
                    $(document).on('click', '.js-progress', function(e) {
                        e.preventDefault();
                        if ($(this).prop('disabled')) return;

                        $.ajax({
                            type: 'POST',
                            url: $(this).data('url'),
                            data: {
                                _token: CSRF
                            },
                            success: function(res) {
                                toastr.success(res.message || 'Updated');
                                tableData.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                toastr.error(xhr.responseJSON?.message || 'Failed');
                            }
                        });
                    });

                    // Maintainer: mark Completed
                    $(document).on('click', '.js-complete', function(e) {
                        e.preventDefault();
                        if ($(this).prop('disabled')) return;

                        $.ajax({
                            type: 'POST',
                            url: $(this).data('url'),
                            data: {
                                _token: CSRF
                            },
                            success: function(res) {
                                toastr.success(res.message || 'Updated');
                                tableData.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                toastr.error(xhr.responseJSON?.message || 'Failed');
                            }
                        });
                    });


                });
            </script>
        @endsection
