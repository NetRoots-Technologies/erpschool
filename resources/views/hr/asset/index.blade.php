@extends('admin.layouts.main')

@section('title')
Asset
@stop

@section('content')
<div class="container-fluid">
    <div class="row w-100  mt-4 ">
        <h3 class="text-22 text-center text-bold w-100 mb-4">Asset</h3>
    </div>

    {{-- <div class="row    mt-4 mb-4 "> --}}
        {{-- @if (Gate::allows('Employee-create'))--}}
        {{-- <div class="col-12 text-right"> --}}
            {{-- <a href="{!! route('hr.asset.create') !!}" class="btn btn-primary btn-md"><b>Add Asset</b></a> --}}
        {{-- </div> --}}
        {{-- @endif--}}
    {{-- </div> --}}


         <div class="row mt-4 mb-4 justify-content-start gap-4">
        {{-- Add employee --}}
@if (Gate::allows('Assets-create'))

        <div class="col-auto p-0">
            <a href="{!! route('hr.asset.create') !!}" class="btn btn-primary btn-md"><b>Add Asset</b></a>
        </div>
       @endif

        {{-- Download Sample Bulk File --}}
        <div class="col-auto p-0">
            <a href="{{ route('academic.asset.export-file') }}" class="btn btn-warning btn-md">
                <b>Download Sample Bulk File</b>
            </a>
        </div>

        {{-- Import Sample Bulk File --}}
          <div class="col-auto">
                <a href="#" class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#importModal">
                    <b>Import Data</b>
                </a>
            </div>

    </div>

     <!-- Import File Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('academic.asset.import-file') }}" method="POST" enctype="multipart/form-data">
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
                                <th class="heading_style">No</th>
                                <th class="heading_style">Company</th>
                                <th class="heading_style">Branch</th>
                                <th class="heading_style">Company Asset Code</th>
                                <th class="heading_style">Name</th>
                                <th class="heading_style">Purchase Date</th>
                                <th class="heading_style">Amount</th>
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

            function confirmDelete(event, form) {
                event.preventDefault();

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });

                return false;
            }

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
                    {'visible': false}
                ],
                ajax: {
                    "url": "{{ route('datatable.data.assetData') }}",
                    "type": "POST",
                    "data": {_token: "{{ csrf_token() }}"}
                },
                "columns": [

                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'company', name: 'company'},
                    {data: 'branch', name: 'branch'},
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                    {data: 'purchase_date', name: 'purchase_date'},
                    {data: 'amount', name: 'amount'},
                    {data: 'action', name: 'action'},
                ],
                order: [1, 'desc']
            });

        });

</script>
@endsection
