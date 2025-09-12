@extends('admin.layouts.main')

@section('title')
Subjects
@stop
@section('css')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row w-100  mt-4 ">
        <h3 class="text-22 text-center text-bold w-100 mb-4"> Subjects
        </h3>
    </div>


    <div class="row    mt-4 mb-4 ">
        @if (Gate::allows('Subjects-create'))
            <div class="col-auto">
                <a href="{!! route('academic.subjects.create') !!}" class="btn btn-primary btn-md"><b>Create Subjects
                    </b></a>
            </div>

            {{-- Download Sample Bulk File --}}
            <div class="col-auto">
                <a href="{{ config('google_sheet_links.subject_file_link') }}" target="_blank"
                    class="btn btn-warning btn-md">
                    <b>Download Sample Bulk File</b>
                </a>
            </div>

            {{-- Import Sample Bulk File --}}
            <div class="col-auto">
                <a href="#" class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#importModal">
                    <b>Import Data</b>
                </a>
            </div>

        @endif

    </div>
    <!-- Import File Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('academic.subjects.import-file') }}" method="POST" enctype="multipart/form-data">
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
                                <th class="heading_style">Course Name</th>
                                <th class="heading_style">Course Type</th>
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
<!-- Clone Modal -->
<div class="modal fade" id="cloneModal" tabindex="-1" aria-labelledby="cloneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="cloneForm" method="POST" action="{{ route('academic.courses.clone') }}">
                @csrf
                <input type="hidden" name="course_id" id="clone_course_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="cloneModalLabel">Clone Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="session" class="form-label">Select Session</label>
                        <input type="hidden" class="clone-val">
                        <select class="form-select select2" name="session_id" id="session" required>
                            <option value="">-- Select Session --</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}">{{ $session->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Clone</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
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
    {{--
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>--}}
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    {{--
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>--}}
    <script type="text/javascript">

        $(document).ready(function () {
            var dataTable = $('#data_table').DataTable({
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
                    { "visible": false }
                ],
                ajax: {
                    "url": "{{ route('datatable.get-data-courses') }}",
                    "type": "POST",
                    "data": { _token: "{{csrf_token()}}" }
                },
                "columns": [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'company', name: 'company' },
                    { data: 'branch', name: 'branch' },
                    { data: 'name', name: 'name' },
                    { data: 'courseType', name: 'courseType' },
                    { data: 'status', name: 'status' },

                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            $('#data_table tbody').on('click', '.change-status', function () {
                var id = $(this).data('id');
                var status = $(this).data('status');

                $.ajax({
                    type: 'POST',
                    url: '{{route('academic.courses.change-status')}}',
                    data: {
                        id: id,
                        status: status,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (response) {

                        console.log(response);
                        dataTable.ajax.reload();
                        toastr.success('Status Updated successfully.');

                    },
                    error: function (xhr, status, error) {
                        toastr.error(xhr.responseText);
                    }
                });
            });

            $(document).on("click", ".deleteBtn", function (e) {
                e.preventDefault();

                var id = $(this).data("id");
                var url = $(this).data("url");

                deleteConfirm(id, url);
            });

            $(document).on("click", ".clone-btn", function () {
                var courseId = $(this).data("id");
                $("#clone_course_id").val(courseId);
                $(".clone-val").val(courseId);
                $("#cloneModal").modal("show");
            });
            // $(document).on("click", ".cloneBtn", function () {
            //     var courseId = $(this).data("id");
            //     $("#cloneModal").modal("show");
            // });

            function deleteConfirm(id, url) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                toastr.success("Your record has been deleted.");
                                dataTable.ajax.reload();
                            },
                            error: function (xhr, status, error) {
                                Swal.fire('Error!', xhr.responseText, 'error');
                            }
                        });
                    }
                });
            }
        });


    </script>
@endsection