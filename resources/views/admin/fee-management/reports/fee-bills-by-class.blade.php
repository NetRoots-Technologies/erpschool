@extends('admin.layouts.main')

@section('title', 'Fee Bills By Class')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="page-leftheader"></div>
                    <h4 class="page-title mb-0">Fee Bills By Class</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.reports') }}">Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Fee Bills By Class</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Options</h3>
                </div>
                <div class="card-body">
                    <di class="row">
                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label for="class">Status</label>
                                <select class="form-control select2" id="status">
                                    <option value="" selected>--Select Status--</option>
                                    <option value="paid">Paid</option>
                                    <option value="generated">Generated</option>
                                    <option value="partially_paid">Partially Paid</option>
                                </select>
                            </div>
                        </div> --}}

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter_month" class="form-label">Filter by Month</label>
                                <input type="month" class="form-control" id="filter_month" value="{{ date('Y-m') }}">
                                <small class="form-text text-muted">Filter will apply automatically on current month</small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="class">Class</label>
                                <select class="form-control select2" id="class_id">
                                    <option value="" selected>--Select class--</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <button type="button" id="resetFilters" class="btn btn-sm btn-info">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                        </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Fee Billing Status</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-vcenter text-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>Father Name</th>
                                        <th>Class</th>
                                        <th>Session</th>
                                        <th>Bill Date</th>
                                        <th>Challan Number</th>
                                        <th>Status</th>
                                        <th>Bill Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Outstanding Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>

                                <tfoot>
                                    <td colspan="8"></td>
                                    <td colspan="3">Outstanding Amount : Rs. <span id="outstanding_amount">0</span></td>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endsection
    @section('css')
        <style>
            .badge {
                color: #212529 !important;
            }

            .badge-success {
                background-color: #28a745 !important;
                color: #212529 !important;
            }

            .badge-danger {
                background-color: #dc3545 !important;
                color: #212529 !important;
            }

            .badge-warning {
                background-color: #ffc107 !important;
                color: #212529 !important;
            }

            .badge-info {
                background-color: #17a2b8 !important;
                color: #212529 !important;
            }

            .badge-secondary {
                background-color: #6c757d !important;
                color: #212529 !important;
            }
        </style>
    @endsection

    @section('js')
        <script>
            $("document").ready(function() {
                var table = $('.table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('admin.fee-management.reports.fee-bills-by-class') }}",
                        data: function(d) {
                            // d.status = $('#status').val();
                            d.filter_month = $('#filter_month').val();
                            d.class_id = $('#class_id').val();
                        }
                    },

                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        {
                            extend: 'copy',
                            text: 'Copy'
                        },
                        {
                            extend: 'csv',
                            text: 'CSV'
                        },
                        {
                            extend: 'excel',
                            text: 'Excel'
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF'
                        },
                        {
                            extend: 'print',
                            text: 'Print'
                        }
                    ],

                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],


                    columns: [{
                            data: 'student_id',
                            name: 'student_id',
                            orderable: true,
                            searchable: true
                        },

                        {
                            data: 'student_name',
                            name: 'student_name',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'father_name',
                            name: 'father_name',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'class',
                            name: 'class',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'session',
                            name: 'session',
                            orderable: true,
                            searchable: true
                        },

                        {
                            data: 'billing_month',
                            name: 'billing_month',
                            orderable: true,
                            searchable: true
                        },

                        {
                            data: 'challan_number',
                            name: 'challan_number',
                            orderable: true,
                            searchable: true
                        },

                        {
                            data: 'status',
                            name: 'status',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'total_amount',
                            name: 'total_amount',
                            orderable: true,
                            searchable: true
                        },

                        {
                            data: 'paid_amount',
                            name: 'paid_amount',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'outstanding_amount',
                            name: 'outstanding_amount',
                            orderable: true,
                            searchable: true
                        }

                        

                    ],

                    footerCallback: function(row, data, start, end, display) {
                       let api = this.api();
                       let total = api
                        .column(8, { page: 'current' })
                        .data()
                        .reduce(function (a, b) {
                            return Number(a) + Number(b);
                        }, 0);
                        $('#outstanding_amount').text(total.toLocaleString());
                    }
                });

                    $('#class_id').change(function() {
                        table.ajax.reload();
                    });

                $('#resetFilters').click(function() {
                    // $('#status').val('').trigger('change');
                    // $('#filter_month').val('').trigger('change');
                    $('#class_id').val('').trigger('change');
                    table.ajax.reload();
                });
            });
        </script>
    @endsection
