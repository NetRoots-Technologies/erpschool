@extends('admin.layouts.main')
@section('title', 'Budget Expense')

@section('content')
    <div class="container-fluid">
        <div class="row w-100 text-center">
            {{-- @if (Gate::allows('InventoryCategory-create')) --}}
                <div class="col-auto mb-3">
                    <a href="{{route('inventory.expense.create')}}" class="btn btn-primary">Add Expense</a>
                </div>
            {{-- @endif --}}
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="budegtExpense"
                                class="border-top-0 table table-bordered text-nowrap key-buttons border-bottom">
                                <thead>
                                    <tr>
                                        <th class="heading_style">No</th>
                                        <th class="heading_style">Budget</th>
                                        <th class="heading_style">cagetory</th>
                                        <th class="heading_style">Subcatgory</th>
                                          <th class="heading_style">Expense Date</th>
                                        <th class="heading_style">Expense Amount</th>
                                      
                                        <th class="heading_style">Description</th>
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
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            // DataTable initialization
            var tableData = $('#budegtExpense').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'collection',
                        text: 'Export',
                        buttons: [{
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
                    url: "{{ route('inventory.expense.index') }}",
                    type: "GET",
                    data: {
                        _token: "{{ csrf_token() }}"
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'budget_title',
                        name: 'budget_title'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'subcategory_name',
                        name: 'subcategory_name'
                    },

                    {
                        data: 'expense_date',
                        name: 'expense_date'
                    },

                     {
                        data: 'expense_amount',
                        name: 'expense_amount'
                    },

                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [{
                        targets: [0, 1, 2],
                        className: 'text-center'
                    }, // Center all columns
                    {
                        targets: '_all',
                        visible: true
                    } // Ensure all columns are visible
                ]
            });

            // Delete functionality
            $(document).on('click', '.delete', function() {
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
                            success: function(response) {
                                Swal.fire('Deleted!', response.success, 'success');
                                $('#budegtExpense').DataTable().ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire('Error', 'Something went wrong!', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
