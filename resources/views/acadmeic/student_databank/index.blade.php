@extends('admin.layouts.main')

@section('title')
    Admission
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
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Pre_Admission Form </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
@if (Gate::allows('PreAdmissionForm-create'))
             <div class="row mt-4 mb-4 justify-content-start gap-4">
                <div class="col-auto p-0">
                    <a href="{!! route('academic.studentDataBank.create') !!}" class="btn btn-primary btn-md"><b>Add Student</b></a>
                </div>
                <div class="col-auto p-0">
                     <a href="{{ route('academic.pre-admission.export-file') }}" class="btn btn-warning btn-md">
                        <b>Download Sample Bulk File</b>
                    </a>
                </div>
                <div class="col-auto">
                    <a href="#" class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#importModal">
                        <b>Import Data</b>
                    </a>
                </div>
            </div>
     @endif
        </div>
         <!-- Import File Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('academic.pre-admission.import-file') }}" method="POST" enctype="multipart/form-data">
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
                                <th class="heading_style">Sr No</th>
                                <th class="heading_style">Name</th>
                                <th class="heading_style">Father Name</th>
                                <th class="heading_style">Email</th>
                                <th class="heading_style">Gender</th>
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
                    // {
                    //     extend: 'collection',

                    //     text: 'Bulk Action',
                    //     className: 'btn-light',
                    //     buttons: [
                    //         {
                    //             text: '<i class="fas fa-trash"></i> Delete',
                    //             className: 'btn btn-danger delete-button',
                    //             action: function () {
                    //                 var selectedIds = [];

                    //                 $('#data_table').find('.select-checkbox:checked').each(function () {
                    //                     selectedIds.push($(this).val());
                    //                 });

                    //                 if (selectedIds.length > 0) {
                    //                     $ ('.dt-button-collection').hide();

                    //                     Swal.fire({
                    //                         title: 'Are you sure?',
                    //                         text: 'You are about to perform a bulk action!',
                    //                         icon: 'warning',
                    //                         showCancelButton: true,
                    //                         confirmButtonColor: '#3085d6',
                    //                         cancelButtonColor: '#d33',
                    //                         confirmButtonText: 'Yes, delete it!',
                    //                         cancelButtonText: 'Cancel'
                    //                     }).then((result) => {
                    //                         if (result.isConfirmed) {
                    //                             $.ajax({
                    //                                 url: '{{ route('academic.studentDataBank-bulk') }}',
                    //                                 type: 'POST',
                    //                                 data: {
                    //                                     ids: selectedIds,
                    //                                     "_token": "{{ csrf_token() }}",
                    //                                 },
                    //                                 dataType: 'json',
                    //                                 success: function (response) {
                    //                                     dataTable.ajax.reload();
                    //                                     toastr.success('Data has been deleted successfully.');
                    //                                 },
                    //                                 error: function (xhr, status, error) {
                    //                                     console.error(xhr.responseText);
                    //                                     toastr.error('AJAX request failed: ' + error);
                    //                                 }
                    //                             });
                    //                         }
                    //                     });
                    //                 } else {
                    //                     toastr.warning('No checkboxes selected.');
                    //                 }
                    //             }
                    //         },
                    //     ],
                    // },

                    {
                        extend: 'colvis',
                        columns: ':not(:first-child)'
                    }
                ],
                "columnDefs": [
                    { 'visible': false }
                ],
                ajax: {
                    "url": "{{ route('datatable.getAdmissionData') }}",
                    "type": "POST",
                    "data": { _token: "{{ csrf_token() }}" }
                },
                "columns": [
                    {
                        data: "checkbox",
                        render : function (data, type, row) {
                            return '<input type = "checkbox" value="'+ row.id +'" class="select-checkbox">'
                        },
                        orderable: false,searchable: false
                    },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'father_name', name: 'father_name' },
                    { data: 'student_email', name: 'student_email' },
                    { data: 'gender', name: 'gender' },

                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                order:[2,'desc']
            });

            // $(document).on("click", ".deleteBtn", function(e) {
            //     e.preventDefault();

            //     var id = $(this).data('id');
            //     var url = $(this).data('url');
            //     deleteConfirm(id, url);

            // })



        });
        function checkAll(source) {
            var checkboxes = $('.select-checkbox');
            for(var i= 0; i< checkboxes.length ; i++){
                checkboxes[i].checked = source.checked;
            }
        }

        // function deleteConfirm(id, url) {
        //         Swal.fire({
        //             title: 'Are you sure?',
        //             text: "You won't be able to revert this!",
        //             icon: 'warning',
        //             showCancelButton: true,
        //             confirmButtonColor: '#3085d6',
        //             cancelButtonColor: '#d33',
        //             confirmButtonText: 'Yes, delete it!'
        //         }).then((result) => {
        //             if (result.isConfirmed) {
        //                 $.ajax({
        //                     url: url,
        //                     type: 'POST',
        //                     data: {
        //                         _method: 'DELETE',
        //                         _token: $('meta[name="csrf-token"]').attr('content')
        //                     },
        //                     success: function (response) {
        //                         toastr.success("Your record has been deleted.");
        //                         $('#data_table').DataTable().ajax.reload();
        //                     },
        //                     error: function (xhr, status, error) {
        //                         toastr.error(xhr.responseText);
        //                     }
        //                 });
        //             }
        //         });
        // }

    </script>
@endsection
