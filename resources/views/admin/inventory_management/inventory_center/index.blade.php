@extends('admin.layouts.main')
@section('title', 'Inventory Center')

@section('content')
    <div class="container-fluid">
        <div class="card p-4">

                    <ul class="nav nav-tabs mb-4" id="coaTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="all-accounts-tab" data-bs-toggle="tab" href="#all-inventories" role="tab">
                        All Inventories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="listing-tab" data-bs-toggle="tab" href="#inventory-listing" role="tab">
                        Inventory Listing
                    </a>
                </li>
            </ul>

            <div class="tab-content overflow-visible">
                <div class="tab-pane fade show active" id="all-inventories" role="tabpanel">
                    <div class="vendor-category">
                        @foreach($inventoryCategories as $category)
                            @include('admin.inventory_management.inventory_center.partials.accordian', ['category' => $category])
                        @endforeach
                    </div>
                </div>

                <div class="tab-pane fade" id="inventory-listing" role="tabpanel">
                    <div class="action d-flex justify-content-end">
                        <a class="btn btn-primary btn-md text-white" href="{{ route('inventory.inventory-management.create') }}">
                            <b>Create Inventory Account</b> 
                        </a>
                     
                    </div><br>

                       <div class="table-responsive">
                        <table id="file-datatable"
                            class="border-top-0 table table-bordered text-nowrap key-buttons text-center border-bottom">
                            <thead>
                                <tr>
                                    <th class="heading_style">Code</th>
                                    <th class="heading_style">Item Name</th>
                                    <th class="heading_style">Category</th>
                                    <th class="heading_style">Account Type</th>
                                    <th class="heading_style">Detail Type</th>
                                    <th class="heading_style">Inventory Type</th>
                                    <th class="heading_style">Status</th>
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

    @include('admin.inventory_management.inventory_center.partials.modal-form')
    @include('admin.inventory_management.inventory_center.partials.modal-edit-form')
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
    </style>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            // open modal if form has validation errors
            if ($('.modal#createCategory').find('.text-danger').length > 0)
                $('.modal#createCategory').modal('show');
            if ($('.modal#editCategory').find('.text-danger').length > 0)
                $('.modal#editCategory').modal('show');

            // edit button AJAX logic
            $('.edit-btn').on('click', function () {
                var id = $(this).data('id');
                $('#inventoryEditForm').attr('action', '/inventory/inventory-center/' + id);
                $.ajax({
                    url: '/inventory/inventory-center/' + id + '/edit',
                    type: 'GET',
                    success: function (data) {
                        $('#editName').val(data.name);
                        // console.log();

                        $('#editCategoryField').val(getCategory(data.parent_id));
                        $('#editCategory').modal('show');
                    },
                    error: function () {
                        alert('Failed to fetch category data.');
                    }
                });
            });

            // delete confirmation
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

            // auto-set parent ID in modal on create
            $('.create-btn').on('click', function () {
                $('#category').val($(this).data('id')).trigger('change');
                $('#createCategory').modal('show');
            });

            // close modal
            $('.cancel-modal').on('click', function () {
                $('#createCategory').modal('hide');
                $('#editCategory').modal('hide');
             //data tables


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
                    url: "{{ route('datatable.get-inventorys') }}",
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}" }
                },
               columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'item_name', name: 'item_name' },
                        { data: 'inventory_type', name: 'inventory_type' },
                        { data: 'account_type_id', name: 'account_type_id' },
                        { data: 'detail_type_id', name: 'detail_type_id' },
                        { data: 'category_id', name: 'category_id' },
                        { data: 'status', name: 'status', orderable: false, searchable: false }, // added
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                ],

                columnDefs: [
                    { "visible": false }
                ]
            });
    });

        function getCategory(id) {
            return $('#category option[value="' + id + '"]').text().trim();
        }
    </script>
@endsection
