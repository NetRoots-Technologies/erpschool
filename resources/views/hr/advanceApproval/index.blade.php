@extends('admin.layouts.main')

@section('title')
    Advance Approval
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
        a.btn.btn-primary.btn-sm{
            margin-right: 3px !important;
            margin-bottom: 5px !important;
        }
        button.btn.btn-danger.btn-sm{
            margin-bottom: 5px !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4">Advances Approval</h3>
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body table-responsive">
                        <table class="w-100 table border-top-0 table-bordered   border-bottom " id="data_table">
                            <thead>
                            <tr>

                                <th>No</th>
                                <th>Employee</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Effective from</th>
                                <th>Installment Amount</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot align="right">
                            <tr>
                                <th></th>
                                {{--                                <th></th>--}}
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>


                            </tr>
                            </tfoot>
                            <tr class="bg-info">

                                <th>No</th>
                                <th>Employee</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Effective from</th>
                                <th>Installment Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>

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
                "pageLength": 100,
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
                        extend: 'colvis',
                        columns: ':not(:first-child)'
                    }
                ],
                "columnDefs": [
                    { 'visible': false }
                ],
                ajax: {
                    "url": "{{ route('datatable.data.advanceApproval') }}",
                    "type": "POST",
                    "data": { _token: "{{ csrf_token() }}" }
                },
                "columns": [

                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'employee', name: 'employee' },
                    { data: 'name', name: 'name' },
                    { data: 'amount', name: 'amount' },
                    { data: 'effective_from', name: 'effective_from' },
                    { data: 'installmentAmount', name: 'installmentAmount' },
                    { data: 'status', name: 'status' },
                    {data: 'action', name: 'action', orderable: false, searchable: false},

                ],
                order:[1,'desc']
            });


        $('#data_table tbody').on('click', '.change-status', function () {
            // alert(12);
            var id = $(this).data('id');
            var status = $(this).data('status');

            $.ajax({
                type: 'POST',
                url: '{{ route('hr.advanceApproval.change-status') }}',
                data: {
                    id: id,
                    status: status,
                    _token: '{{ csrf_token() }}',
                },
                success: function (response) {
                    console.log(response);
                    dataTable.ajax.reload();
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
        });

    </script>
@endsection
