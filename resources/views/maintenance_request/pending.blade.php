@extends('admin.layouts.main')

@section('title')
    Pending Maintenance Request
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
            <h3 class="text-22 text-center text-bold w-100 mb-4">  Pending Maintenance Request </h3>
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
                                        <th class="heading_style">Status</th>
                                        <th class="heading_style">Create Request</th>
                                        {{-- <th class="heading_style">Action</th> --}}
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
                            "url": "{{ route('maintenance-request.pending') }}",
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
                                data: 'request_creater',
                                name: 'request_creater'
                            },
                            // {
                            //     data: 'action',
                            //     name: 'action',
                            //     orderable: false,
                            //     searchable: false
                            // },
                        ]
                    });

                $(document).on('click', '.deleteType', function() {
                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this?')) {
                    $.ajax({
                        url: "/maintenance-request/" + id,
                        type: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(resp) {
                            toastr.success('Deleted successfully');
                            tableData.ajax.reload();
                        }
                    });
                }
            });

                });

                
            </script>
        @endsection


