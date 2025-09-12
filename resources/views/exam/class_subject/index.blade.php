@extends('admin.layouts.main')

@section('title')
Exam Detail
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
        <h3 class="text-22 text-center text-bold w-100 mb-4">Class Subject</h3>
    </div>
    <div class="row    mt-4 mb-4 ">
   @if (Gate::allows('students'))
        <div class="col-12 text-right">
            <a href="{!! route('exam.class_subjects.create') !!}" class="btn btn-primary btn-md"><b>Add Class Subject</b></a>
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
                                <th class="heading_style">Company</th>
                                <th class="heading_style">Branch</th>
                                <th class="heading_style">Class</th>
                                <th class="heading_style">Subject</th>
                                <th class="heading_style">User</th>
                                <th class="heading_style">Log</th>
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

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            console.log('Document ready, initializing DataTable...');

            // Check if DataTable is available
            if (typeof $.fn.DataTable === 'undefined') {
                console.error('DataTables library not loaded!');
                return;
            }

            // Check if the table element exists
            if ($('#data_table').length === 0) {
                console.error('Table element #data_table not found!');
                return;
            }

            console.log('Table element found, creating DataTable...');

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
                                                    url: '{{ route('exam.class-subject-bulk') }}',
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
                                                        alert('AJAX request failed: ' + error);
                                                    }
                                                });
                                            }
                                        });
                                    } else {
                                        alert('No checkboxes selected.');
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
                    { 'visible': false, 'targets': [] }
                ],
                ajax: {
                    "url": "{{ route('datatable.classSubject.getdata') }}",
                    "type": "POST",
                    "data": { _token: "{{ csrf_token() }}" },
                    "dataSrc": "data",
                    "error": function (xhr, error, thrown) {
                        console.error('DataTables AJAX error:', error);
                        console.error('Response:', xhr.responseText);
                    }
                },
                "columns": [
                    {
                        data: "checkbox",
                        render: function (data, type, row) {
                            return '<input type = "checkbox" value="' + row.id + '" class="select-checkbox">'
                        },
                        orderable: false, searchable: false
                    },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'company', name: 'company' },
                    { data: 'branch', name: 'branch' },
                    { data: 'AcademicClass', name: 'AcademicClass' },
                    { data: 'Subject', name: 'Subject' },
                    { data: 'user', name: 'user' },
                    { data: 'created_at', name: 'created_at' },

                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                order: [2, 'desc']
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
