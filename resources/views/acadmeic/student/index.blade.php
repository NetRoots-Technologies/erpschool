@extends('admin.layouts.main')

@section('title')
All Students
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

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Import Failed:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="container-fluid">
    <div class="row w-100  mt-4 ">
        <h3 class="text-22 text-center text-bold w-100 mb-4"> All Students</h3>
    </div>
    <div class="row mt-4 mb-4 align-items-center justify-content-start">
        {{-- Add Student Button --}}
        @if (Gate::allows('ViewStudents-create'))
         <div class="col-auto">
            <a href="{{ route('academic.students.create') }}" class="btn btn-primary btn-md">
                <b>Add Student</b>
            </a>
        </div>

        {{-- Download Sample Bulk File --}}
        <div class="col-auto">
            <a href="{{ config('google_sheet_links.student_flie_link') }}" target="_blank"
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
                <form action="{{ route('academic.student.import-file') }}" method="POST" enctype="multipart/form-data">
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
                                <th class="heading_style">Student ID</th>
                                <th class="heading_style">Name</th>
                                <th class="heading_style">Email</th>
                                <th class="heading_style">Father Name</th>
                                <th class="heading_style">Admission Date</th>
                                <th class="heading_style">Campus</th>
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
<!-- Import File Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('academic.student.import-file') }}" method="POST" enctype="multipart/form-data">
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

<!-- Leave Modal -->
<div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content border-0 shadow">
      <form id="leaveForm">
        @csrf
        <input type="hidden" id="leave_student_id" name="id" value="">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="leaveModalLabel">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>Mark Student as Left
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-3">Please provide a reason for marking this student as left.</p>
          <div class="mb-3">
            <textarea id="leave_reason" name="reason" class="form-control form-control-lg" rows="4" placeholder="Enter reason..." required></textarea>
            <div id="leaveError" class="text-danger small mt-1" style="display:none;"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary cancel" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancel
          </button>
          <button type="submit" id="leaveSaveBtn" class="btn btn-danger">
            <i class="bi bi-check-circle me-1"></i> Save
          </button>
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
                                                    url: '{{ route('academic.student-bulk') }}',
                                                    type: 'POST',
                                                    data: {
                                                        ids: selectedIds,
                                                        "_token": "{{ csrf_token() }}",
                                                    },
                                                    dataType: 'json',
                                                    success: function (response) {
                                                        dataTable.ajax.reload();
                                                        toastr.success('Your data has been deleted successfully')
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
                    "url": "{{ route('datatable.getStudentData') }}",
                    "type": "POST",
                    "data": { _token: "{{ csrf_token() }}" }
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
                    { data: 'student_id', name: 'student_id' },
                    { data: 'name', name: 'name' },
                    { data: 'student_email', name: 'student_email' },
                    { data: 'father_name', name: 'father_name' },
                    { data: 'admission_date', name: 'admission_date' },
                    { data: 'campus', name: 'campus' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                order: [2, 'desc']
            });

            $(document).on("click", ".deleteBtn", function (e) {
                e.preventDefault();
                let id = $(this).data('id');
                let url = $(this).data('url');
                deleteConfirm(id, url);
            });

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
                                Swal.fire('Deleted!', 'Student deleted Sucessfully.', 'success');
                                dataTable.ajax.reload();
                            },
                            error: function () {
                                Swal.fire('Error!', 'Something went wrong.', 'error');
                            }
                        });
                    }
                });
            }

        });
        function checkAll(source) {
            var checkboxes = $('.select-checkbox');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

    $(document).ready(function () {
        // Use a single modal instance
        var leaveModalEl = document.getElementById('leaveModal');
        var leaveModal = new bootstrap.Modal(leaveModalEl);

        // Open modal on button click
        $('#data_table').on('click', '.leaveBtn', function () {
            let studentId = $(this).data('id');
            $('#leave_student_id').val(studentId);
            $('#leave_reason').val('');
            $('#leaveError').hide().text('');
            leaveModal.show();
        });

        // Handle form submit
        $('#leaveForm').on('submit', function (e) {
            e.preventDefault();

            let id = $('#leave_student_id').val();
            let reason = $('#leave_reason').val().trim();

            if (!reason) {
                $('#leaveError').text('Reason is required').show();
                return;
            } else {
                $('#leaveError').hide();
            }

            $('#leaveSaveBtn').prop('disabled', true).text('Saving...');

            $.ajax({
                url: "{{ route('academic.students.leave') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,
                    reason: reason
                },
                success: function (res) {
                    leaveModal.hide();
                    toastr.success(res.message);
                    $('#data_table').DataTable().ajax.reload();
                    window.location.reload();
                },
                error: function (xhr) {
                    let msg = 'An error occurred';
                    if (xhr.responseJSON?.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    } else if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    }
                    $('#leaveError').html(msg).show();
                },
                complete: function () {
                    $('#leaveSaveBtn').prop('disabled', false).text('Save');
                }
            });
        });

        // Reset form when modal closes
        leaveModalEl.addEventListener('hidden.bs.modal', function () {
            $('#leaveForm')[0].reset();
            $('#leaveError').hide().text('');
            $('#leaveSaveBtn').prop('disabled', false).text('Save');
        });

        $('.cancel').on('click', function () {
            leaveModal.hide();
            window.location.reload();
        });
    });

    </script>
@endsection
