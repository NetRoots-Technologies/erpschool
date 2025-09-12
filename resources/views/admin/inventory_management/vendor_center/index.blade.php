@extends('admin.layouts.main')
@section('title', 'Vendor Center')

@section('content')
    <div class="container-fluid">
        <div class="card p-4">

            <ul class="nav nav-tabs mb-4" id="coaTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" id="all-accounts-tab" data-bs-toggle="tab" href="#all-vendors" role="tab">All Vendors</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link active" id="listing-tab" data-bs-toggle="tab" href="#vendors-listing"
                        role="tab">Vendor Listing</a>
                </li>
            </ul>
            <div class="tab-content overflow-visible">
                <div class="tab-pane fade " id="all-vendors" role="tabpanel">
                    <div class="vendor-category">
                        @foreach($vendorsCatgories as $category)
                            @if($category->level == 1)
                                @include('admin.inventory_management.vendor_center.partials.accordian', ['category' => $category])
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade show active" id="vendors-listing" role="tabpanel">
                    <div class="action  d-flex justify-content-end mb-3">
                        <a class="btn btn-primary btn-md text-white" href="{{route('inventory.vendor-management.create')}}">
                            Create Vendors
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table id="file-datatable"
                            class="border-top-0 table table-bordered text-nowrap key-buttons text-center border-bottom">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th class="heading_style">Name</th>
                                    <th class="heading_style">Vendor Category</th>
                                    <th class="heading_style">Email</th>
                                    <th class="heading_style">City</th>
                                    <th class="heading_style">Status</th>
                                    <th class="heading_style">Mobile No</th>
                                    <th class="heading_style">Action</th>

                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="switchCheckDefault">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('admin.inventory_management.vendor_center.partials.modal-form')
    @include('admin.inventory_management.vendor_center.partials.modal-edit-form')

@endsection

@section('css')
    <style>
        .nav-tabs .nav-link.active {
            color: #0d6efd !important;
            border: 1px solid #0d6efd !important;
            background-color: #eaf4ff;
            font-weight: bold;
        }

        .accordion .dropdown .dropdown-toggle::after {
            display: none;
        }

        .accordion ul {
            list-style-type: none !important;
        }
    </style>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            //open modal if error
            if ($('.modal#createCategory').find('.text-danger').length > 0)
                $('.modal#createCategory').modal('show');
            if ($('.modal#editCategory').find('.text-danger').length > 0)
                $('.modal#editCategory').modal('show');


            //ajax for edit form
            $('.edit-btn').on('click', function () {
                var id = $(this).data('id');
                $('#vendorEditForm').attr('action', '/inventory/vendor-category/' + id);
                $.ajax({
                    url: '/inventory/vendor-category/' + id + '/edit',
                    type: 'GET',
                    success: function (data) {
                        $('#editName').val(data.name);
                        // console.log(getCategory(data.parent_id));
                        $('#editCategoryField').val(getCategory(data.parent_id));
                        $('#editCategory').modal('show');
                    },
                    error: function (xhr) {
                        alert('Failed to fetch category data.');
                    }
                });
            });

            //warning for delete
            $('.delete-btn').on('click', function (e) {
                e.preventDefault();
                const form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            //clear data on Create
            $('.create-btn').on('click', function () {
                $('#category').val($(this).data('id')).trigger('change');
                $('#createCategory').modal('show');
            });

            //close modal
            $('.cancel-modal').on('click', function () {
                $('#createCategory').modal('hide');
                $('#editCategory').modal('hide');
            });

            //data tables
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
                    url: "{{ route('datatable.get-vendors') }}",
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}" }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'detail_type', name: 'company_name' },
                    { data: 'email', name: 'email' },
                    { data: 'city', name: 'city' },
                    { data: 'status', name: 'state', orderable: false, searchable: false },
                    { data: 'mobileNo', name: 'mobileNo', orderable: true },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                columnDefs: [
                    { "visible": false }
                ]
            });

            //delete-vendor
            $(document).on('click', '.delete-op', function (e) {
                e.preventDefault();

                const button = $(this);
                const form = button.closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.off('submit');
                        form.submit();
                    }
                });


            });

            //status change
            $(document).on('change', '#status-switch', function () {
                // alert('hello');
                const status = $(this).is(':checked') ? 1 : 0;
                // alert(status);
                const id = $(this).data('id');

                // alert(id);
                $.ajax({
                    url: `/inventory/vendor-management/${id}/toggle-status`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',  // For Laravel CSRF protection
                        status: status
                    },
                    success: function (response) {
                        Swal.fire('Success', response.message, 'success');
                    },
                    error: function (xhr) {
                        Swal.fire('Error', 'Something went wrong!', 'error');
                    }
                });

            });
        });


        function getCategory(id) {
            return $('#category option[value="' + id + '"]').text().trim();
        }
    </script>
@endsection