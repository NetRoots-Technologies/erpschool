```blade
@extends('admin.layouts.main')
@section('title', 'Budget Category')

@section('content')
    <div class="container-fluid">
        <div class="row w-100 text-center">
            @if (Gate::allows('InventoryCategory-create'))

            <div class="col-auto mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Create New Category</button>
            </div>
            @endif
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="category-datatable" class="border-top-0 table table-bordered text-nowrap key-buttons border-bottom">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th class="heading_style">Title</th>
                                        <th class="heading_style">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for create -->
    <div class="modal" id="createModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Create Category</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <form id="createform" method="POST" action="{{ route('inventory.category.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="department_create_label">Title</label>
                                    <input type="text" required class="form-control" id="title" name="title">
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group text-right d-flex justify-content-end">
                                    <input id="create-form-submit" type="submit" class="btn btn-primary me-2" value="Submit">
                                    <button type="button" class="btn btn-danger modalclose" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Edit -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Edit Category</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <form id="editform">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="edit_id">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-footer justify-content-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-danger edit-cancel" data*-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            // DataTable initialization
            var tableData = $('#category-datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'collection',
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
                    'colvis'
                ],
                ajax: {
                    url: "{{ route('datatable.get-data-category') }}",
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}" }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'title', name: 'title' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                columnDefs: [
                    { targets: [0, 1, 2], className: 'text-center' }, // Center all columns
                    { targets: '_all', visible: true } // Ensure all columns are visible
                ]
            });

            // Check for validation errors and show create modal if needed
            const hasErrors = document.querySelectorAll('.text-danger').length > 0;
            if (hasErrors) {
                const modal = document.getElementById('createModal');
                if (modal) {
                    const bootstrapModal = new bootstrap.Modal(modal);
                    bootstrapModal.show();
                }
            }

            // Form submission
            $('#create-form-submit').on('click', function (e) {
                e.preventDefault();
                $(this).closest('form').find('.modalclose').trigger('click');

                var url = "{{ route('inventory.category.store') }}";
                if (!$('#createform').valid()) {
                    return false;
                }
                var loader = $('<div class="loader"></div>').appendTo('body');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $('#createform').serialize(),
                    success: function (response) {
                        loader.remove();
                        $('#createform').trigger("reset");
                        tableData.ajax.reload();
                        toastr.success('Category Added successfully.');
                    },
                    error: function () {
                        loader.remove();
                        toastr.error('Please fill all the fields.');
                    }
                });
                return false;
            });

            // Populate modal fields for edit
            $(document).on('click', '.category_edit', function () {
                const category = $(this).data('category-edit');
                if (!category || !category.id) {
                    Swal.fire('Error', 'Invalid category data.', 'error');
                    return;
                }

                $('#edit_id').val(category.id);
                $('#edit_title').val(category.title);
                $('#editform').attr('action', '{{ route("inventory.category.update", ":id") }}'.replace(':id', category.id));

                // Show modal
                const editModal = new bootstrap.Modal(document.getElementById('myModal'));
                editModal.show();
            });

            // Submit the edit form
            $('#editform').on('submit', function (e) {
                e.preventDefault();
                $(this).closest("form").find('.edit-cancel').trigger('click');

                const form = $(this);
                const url = form.attr('action');
                const formData = form.serialize() + '&_method=PUT';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        Swal.fire('Updated!', response.message, 'success');
                        $('#category-datatable').DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        let errorMessage = 'Failed to update category.';
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('<br>');
                        } else if (xhr.status === 419) {
                            errorMessage = 'CSRF token mismatch. Please refresh the page.';
                        } else if (xhr.status === 404) {
                            errorMessage = 'Category not found.';
                        }
                        Swal.fire('Error', errorMessage, 'error');
                    }
                });
            });

            // Delete functionality
            $(document).on('click', '.delete', function () {
                const formId = $(this).data('id');
                const route = $('#' + formId).data('route') || $(this).closest('form').data('route');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: route,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.fire('Deleted!', response.success, 'success');
                                $('#category-datatable').DataTable().ajax.reload();
                            },
                            error: function (xhr) {
                                Swal.fire('Error', 'Something went wrong!', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
```
