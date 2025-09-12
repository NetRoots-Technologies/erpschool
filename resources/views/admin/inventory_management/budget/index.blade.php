@extends('admin.layouts.main')
@section('title', 'Budget')

@section('content')
    <div class="container-fluid">
        <div class="row w-100 text-center">
            @if (Gate::allows('Budget-create'))

            <div class="col-auto mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Create New
                    Budget</button>
            </div>
            @endif
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="file-datatable"
                                class="border-top-0 table table-bordered text-nowrap key-buttons border-bottom">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th class="heading_style">Title</th>
                                        <th class="heading_style">Category</th>
                                        <th class="heading_style">Budget Duration</th>
                                        <th class="heading_style">Cost Center</th>
                                        <th class="heading_style">Amount</th>
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

    {{-- Modals --}}
    <!-- Modal for create -->
    <div class="modal" id="createModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Create Budget</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <form id="createform" method="POST" action="{{ route('inventory.budget.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="department_create_label">Title</label>
                                    <input type="text" required class="form-control" id="title" name="title">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="department_create_label">Start and End Period</label>
                                    <select required name="timeFrame" class="form-select" id="timeFrame">
                                        <option value="">Select Time Frame</option>
                                        <option value="custom">Custom</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="quarterly">Quarterly</option>
                                        <option value="biAnnual">Bi-Annual</option>
                                        <option value="annual">Annual</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="department_create_label">Start date</label>
                                    <input type="date" required class="form-control startDate bg-white flat-picker"
                                        id="startDate" name="startDate">
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="department_create_label"> End date</label>
                                    <input type="date" required class="form-control endDate bg-white flat-picker"
                                        id="endDate" name="endDate">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="department_create_label">Amount</label>
                                    <input type="number" required class="form-control" id="amount" name="amount" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="department_create_label">Category</label>
                                    <select name="category" required id="category" class="form-select select2">
                                        <option value="">Select Category</option>
                                        @foreach ($category as $item)
                                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="department_create_label">Cost Center</label>
                                    <select name="costCenter" required id="costCenter" class="form-select select2">
                                        <option value="">Select Cost Center</option>
                                        {{-- <option value="">{{$departments}}</option> --}}
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">
                                                {{ $department->name . ' (' . ($department->branch->name ?? 'No Branch') . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group text-right d-flex justify-content-end">
                                    <input id="create-form-submit" type="submit" class="btn btn-primary me-2"
                                        value="Submit">
                                    <button type="button" class="btn btn-danger modalclose"
                                        data-bs-dismiss="modal">Cancel</button>
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
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Edit Budget</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <form id="editform">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="edit_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="department_create_label">Title</label>
                                    <input type="text" required class="form-control" id="edit_title" name="title">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="department_create_label">Start and End Period</label>
                                    <select required name="timeFrame" class="form-select" id="edit_timeFrame">
                                        <option value="">Select Time Frame</option>
                                        <option value="custom">Custom</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="quarterly">Quarterly</option>
                                        <option value="biAnnual">Bi-Annual</option>
                                        <option value="annual">Annual</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="department_create_label">Start date</label>
                                    <input type="date" required class="form-control startDate bg-white flat-picker"
                                        id="edit_startDate" name="startDate">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="department_create_label"> End date</label>
                                    <input type="date" required class="form-control endDate bg-white flat-picker"
                                        id="edit_endDate" name="endDate">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="department_create_label">Amount</label>
                                    <input type="number" required class="form-control" id="edit_amount" name="amount"
                                        min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="department_create_label">Category</label>
                                    <select name="category" required id="edit_category" class="form-select select2">
                                        <option value="">Select Category</option>
                                        @foreach ($category as $item)
                                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="department_create_label">Cost Center</label>
                                    <select name="costCenter" required id="edit_costCenter" class="form-select select2">
                                        <option value="">Select Cost Center</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">
                                                {{ $department->name . ' (' . ($department->branch->name ?? 'No Branch') . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-danger edit-cancel" data-dismiss="modal">Cancel</button>
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
            var tableData = $('#file-datatable').DataTable({
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
                    url: "{{ route('datatable.get-data-budget') }}",
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}" }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'title', name: 'title' },
                    { data: 'category', name: 'category' },
                    {
                        data: 'timeFrame', name: 'timeFrame', render: function (data, type, row, meta) {
                            return data ? data.toUpperCase() : '';
                        }
                    },
                    { data: 'cost_center', name: 'cost_center' },
                    { data: 'amount', name: 'amount', orderable: true },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                columnDefs: [
                    { "visible": false }
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

            //form submission

            $('#create-form-submit').on('click', function (e) {
                e.preventDefault();
                $(this).closest('form').find('.modalclose').trigger('click');
                var url = "{{ route('inventory.budget.store') }}";
                if (!$('#createform').valid()) {
                    return false;
                }
                var loader = $('<div class="loader"></div>').appendTo('body');
                $.ajax({
                    type: "post",
                    "url": url,
                    data: $('#createform').serialize(),
                    success: function (response) {
                        loader.remove();
                        $('#createform').trigger("reset");
                        tableData.ajax.reload();
                        toastr.success('Department Added successfully.');
                    },
                    error: function (xhr) {
                        loader.remove();
                        if (xhr.status === 422) {
                            // Laravel validation error response
                            const errors = xhr.responseJSON.errors;
                            for (const field in errors) {
                                if (errors.hasOwnProperty(field)) {
                                    toastr.error(errors[field][0]); // Show first error for each field
                                }
                            }
                        } else {
                            toastr.error('Something went wrong. Please try again.');
                        }
                    }
                });
                return false;
            });

            // Populate modal fields for edit
            $(document).on('click', '.budget_edit', function () {
                const budget = $(this).data('budget-edit');
                // console.log('Editing budget:', budget);

                if (!budget || !budget.id) {
                    Swal.fire('Error', 'Invalid budget data.', 'error');
                    return;
                }

                $('#edit_id').val(budget.id);
                $('#edit_title').val(budget.title);
                $('#edit_timeFrame').val(budget.timeFrame);
                $('#edit_amount').val(budget.amount);
                $('#edit_startDate').val(budget.startDate);
                $('#edit_endDate').val(budget.endDate);
                $('#edit_category').val(budget.b_category_id);
                $('#edit_costCenter').val(budget.department_id);
                $('#editform').attr('action', '{{ route("inventory.budget.update", ":id") }}'.replace(':id', budget.id));

                // Show modal
                const editModal = new bootstrap.Modal(document.getElementById('myModal'));
                editModal.show();

                // Initialize endDate and readonly state based on current edit_timeFrame
                handleTimeFrameChange($('#edit_timeFrame'));
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
                        $('#file-datatable').DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        // console.log(xhr.responseJSON);
                        let errorMessage = 'Failed to update budget.';
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('<br>');
                        } else if (xhr.status === 419) {
                            errorMessage = 'CSRF token mismatch. Please refresh the page.';
                        } else if (xhr.status === 404) {
                            errorMessage = 'Budget not found.';
                        }
                        Swal.fire('Error', errorMessage, 'error');
                    }
                });
            });

            // Delete functionality
            $(document).on('click', '.delete', function () {
                const formId = $(this).data('id');
                const route = $('#' + formId).data('route');

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
                                $('#file-datatable').DataTable().ajax.reload();
                            },
                            error: function (xhr) {
                                Swal.fire('Error', 'Something went wrong!', 'error');
                            }
                        });
                    }
                });
            });

            // Function to calculate end date based on time frame and start date
            function calculateEndDate(startDate, timeFrame) {
                if (!startDate) return '';

                const start = new Date(startDate);
                const end = new Date(start);

                switch (timeFrame) {
                    case "monthly":
                        end.setMonth(end.getMonth() + 1);
                        end.setDate(end.getDate() - 1); // Last day of the month
                        break;
                    case "quarterly":
                        end.setMonth(end.getMonth() + 3);
                        end.setDate(end.getDate() - 1); // Last day of the quarter
                        break;
                    case "biAnnual":
                        end.setMonth(end.getMonth() + 6);
                        end.setDate(end.getDate() - 1); // Last day of the 6-month period
                        break;
                    case "annual":
                        end.setFullYear(end.getFullYear() + 1);
                        end.setDate(end.getDate() - 1); // Last day of the year
                        break;
                    default:
                        return '';
                }

                return end.toISOString().split('T')[0];
            }

            // Function to handle time frame change
            function handleTimeFrameChange($form) {
                const timeFrame = $form.val();
                const $startDate = $form.closest('form').find('.startDate');
                const $endDate = $form.closest('form').find('.endDate');

                switch (timeFrame) {
                    case "custom":
                        $endDate.closest('div[class*="col"]').slideDown();
                        $startDate.closest('div[class*="col"]').addClass('col-md-6');
                        $endDate.prop('readonly', false);
                        break;
                    case "monthly":
                    case "quarterly":
                    case "biAnnual":
                    case "annual":
                        $endDate.closest('div[class*="col"]').slideDown();
                        $startDate.closest('div[class*="col"]').addClass('col-md-6');
                        $endDate.prop('readonly', true);

                        // Calculate and set end date if start date is available
                        const startDateValue = $startDate.val();
                        if (startDateValue) {
                            const endDateValue = calculateEndDate(startDateValue, timeFrame);
                            $endDate.val(endDateValue);
                        }
                        break;
                    default:
                        $endDate.closest('div[class*="col"]').slideUp(function () {
                            $startDate.closest('div[class*="col"]').removeClass('col-md-6');
                        });
                        $endDate.prop('readonly', false);
                }
            }

            $('#createform #timeFrame,#editform #edit_timeFrame').on('change', function () {
                handleTimeFrameChange($(this));
            });

            // Handle start date change to update end date for non-custom time frames
            $('#createform #startDate,#editform #edit_startDate').on('change', function () {
                const $form = $(this).closest('form');
                const timeFrame = $form.find('select[id*="timeFrame"]').val();
                const startDate = $(this).val();
                const $endDate = $form.find('.endDate');

                if (timeFrame && timeFrame !== 'custom' && startDate) {
                    const endDateValue = calculateEndDate(startDate, timeFrame);
                    $endDate.val(endDateValue);
                }
            });

            //select 2
            $('#createModal').on('shown.bs.modal', function () {
                $('.select2').select2({
                    dropdownParent: $('#createModal')
                });
            });

            $('#myModal').on('shown.bs.modal', function () {
                $('.select2').select2({
                    dropdownParent: $('#myModal')
                });
            });

        });

    </script>
@endsection
@section('css')
    <style>
        .select2-selection--single {
            height: auto !important;
        }

        div[class*="col"]:has(.endDate) {
            display: none;
        }
    </style>
@endsection
