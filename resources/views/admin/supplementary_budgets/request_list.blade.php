@extends('admin.layouts.main')
@section('title', 'Suppliementary Budget Request')

@section('content')
    <div class="container-fluid">
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="border-top-0 table table-bordered text-nowrap key-buttons border-bottom"
                                id="supplementary-table">
                                <thead>
                                    <tr>
                                        <th>Sr#</th>
                                        <th>Budget</th>
                                        <th>Category</th>
                                        <th>Subcategory</th>
                                        <th>Month</th>
                                        <th>Requested Amount</th>
                                        <th>Requested By</th>
                                        <th>Approved By</th>
                                        <th>Status</th>
                                        <th>Reason</th>

                                        {{-- <th>Variance</th> --}}
                                        <th>Action</th>
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

<!-- Shared Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reason</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="reasonText"></p>
      </div>
    </div>
  </div>
</div>


@endsection
@section('js')
    <script>
        $(document).ready(function() {
            // DataTable initialization
            var tableData = $('#supplementary-table').DataTable({
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
                    url: "{{ route('inventory.supplimentary.requests.list') }}",
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
                        data: 'budget_name',
                        name: 'budget_name'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'subcatgory_name',
                        name: 'subcatgory_name'
                    },

                    {
                        data: 'month',
                        name: 'month'
                    },

                    {
                        data: 'requested_amount',
                        name: 'requested_amount'
                    },

                    {
                        data: 'requested_by_user',
                        name: 'requested_by_user'
                    },

                    {
                        data: 'approved_by_user',
                        name: 'approved_by_user'
                    },

                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'reason',
                        name: 'reason'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [{
                    "visible": false
                }]
            });
        });


        // Approved Status

        $(document).on('click', '.approve_status, .reject_status', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const route = $(this).attr('href'); 
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to update the status!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Update it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: route,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                Swal.fire('Updated', response.success, 'success');
                            $('#supplementary-table').DataTable().ajax.reload();
                            } else {
                                Swal.fire('Not Update', response.error, 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'Something went wrong!', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.view-reason', function () {
            let reason = $(this).data('reason');
            $('#reasonText').text(reason);
        });


    </script>
@endsection

@section('css')

@endsection
